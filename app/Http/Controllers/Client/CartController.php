<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\VNPayService;
use Illuminate\Support\Facades\DB;
use App\Models\Promotion;
use App\Models\UsedPromotion;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    protected $vnpayService;

    public function __construct(VNPayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    public function index()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)
            ->with(['productVariation.product', 'productVariation.attributeValues.attribute'])
            ->get();
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->total;
        }

        return view('client.cart.cart', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'required|exists:product_variations,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $variation = ProductVariation::find($request->variation_id);

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $existingCart = Cart::where('user_id', $user->id)
            ->where('product_variation_id', $request->variation_id)
            ->first();

        // Tính tổng số lượng sẽ có trong giỏ hàng sau khi thêm
        $totalQuantity = $request->quantity;
        if ($existingCart) {
            $totalQuantity += $existingCart->quantity;
        }

        // Kiểm tra tổng số lượng không vượt quá số lượng tồn kho
        if ($totalQuantity > $variation->stock) {
            return back()->with('error', 'Số lượng sản phẩm trong giỏ hàng vượt quá số lượng tồn kho. Số lượng tối đa có thể thêm là: ' . ($variation->stock - ($existingCart ? $existingCart->quantity : 0)));
        }

        if ($existingCart) {
            // Nếu đã có thì cập nhật số lượng
            $existingCart->quantity += $request->quantity;
            $existingCart->total = $existingCart->quantity * ($variation->sale_price ?: $variation->price);
            $existingCart->save();
        } else {
            // Nếu chưa có thì tạo mới
            Cart::create([
                'user_id' => $user->id,
                'product_variation_id' => $request->variation_id,
                'quantity' => $request->quantity,
                'price' => $variation->sale_price ?: $variation->price,
                'total' => $request->quantity * ($variation->sale_price ?: $variation->price)
            ]);
        }

        return redirect()->route('cart')->with('success', 'Đã thêm sản phẩm vào giỏ hàng');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'required|exists:product_variations,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $variation = ProductVariation::find($request->variation_id);

        if ($variation->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng sản phẩm không đủ'
            ]);
        }

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_variation_id', $request->variation_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity = $request->quantity;
            $cartItem->total = $request->quantity * ($variation->sale_price ?: $variation->price);
            $cartItem->save();
        }

        // Tính toán lại tổng tiền
        $total = 0;
        $cartItems = Cart::where('user_id', $user->id)->get();
        foreach ($cartItems as $item) {
            $total += $item->total;
        }

        return response()->json([
            'success' => true,
            'totals' => [
                'total' => number_format($total) . 'đ'
            ]
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'required|exists:product_variations,id'
        ]);

        $user = Auth::user();
        Cart::where('user_id', $user->id)
            ->where('product_variation_id', $request->variation_id)
            ->delete();

        // Tính toán lại tổng tiền
        $total = 0;
        $cartItems = Cart::where('user_id', $user->id)->get();
        foreach ($cartItems as $item) {
            $total += $item->total;
        }

        return response()->json([
            'success' => true,
            'totals' => [
                'total' => number_format($total) . 'đ'
            ]
        ]);
    }

    public function clear()
    {
        $user = Auth::user();
        Cart::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa giỏ hàng'
        ]);
    }
    public function saveSelectedItems(Request $request)
    {
        $selected = json_decode($request->input('selected_items'), true);
        session(['selected_cart_items' => $selected]);
        return redirect()->route('checkout');
    }
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $selectedVariationIds = session('selected_cart_items', []);
        if (empty($selectedVariationIds)) {
            return redirect()->route('cart')->with('error', 'Vui lòng chọn sản phẩm để thanh toán');
        }
    
        $selectedItems = Cart::where('user_id', $user->id)
            ->whereIn('product_variation_id', $selectedVariationIds)
            ->with(['productVariation.product', 'productVariation.attributeValues.attribute'])
            ->get();
    
        $subtotal = $selectedItems->sum('total');
        $discount = 0;
        $shippingFee = 0;
        $total = $subtotal - $discount + $shippingFee;
    
        return view('client.cart.checkout', compact(
            'selectedItems', 'subtotal', 'discount', 'shippingFee', 'total'
        ));
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $selectedVariationIds = session('selected_cart_items', []);
            
            if (empty($selectedVariationIds)) {
                return redirect()->route('cart')->with('error', 'Vui lòng chọn sản phẩm để thanh toán');
            }

            $selectedItems = Cart::where('user_id', $user->id)
                ->whereIn('product_variation_id', $selectedVariationIds)
                ->with(['productVariation.product'])
                ->get();

            $subtotal = $selectedItems->sum('total');
            $discount = 0;
            $shippingFee = 0;
            $total = $subtotal - $discount + $shippingFee;

            // Start database transaction
            DB::beginTransaction();

            try {
                // Validate stock and lock rows for update
                foreach ($selectedItems as $item) {
                    $variation = ProductVariation::lockForUpdate()->find($item->product_variation_id);
                    if (!$variation || $variation->stock < $item->quantity) {
                        DB::rollBack();
                        return back()->with('error', "Sản phẩm {$item->productVariation->product->name} không đủ số lượng trong kho.");
                    }
                }

                // Nếu thanh toán qua VNPay
                if ($request->payment_method === 'bank') {
                    // Lưu thông tin đơn hàng vào session để dùng sau khi thanh toán
                    session([
                        'pending_order' => [
                            'user_id' => $user->id,
                            'user_name' => $request->user_name,
                            'user_phone' => $request->user_phone,
                            'province' => $request->province,
                            'district' => $request->district,
                            'ward' => $request->ward,
                            'address' => $request->address,
                            'notes' => $request->notes,
                            'payment_method' => 'bank',
                            'selected_items' => $selectedItems->toArray(),
                            'total' => $total,
                            'discount' => $discount
                        ]
                    ]);

                    // Tạo URL thanh toán VNPay
                    $vnpayUrl = $this->vnpayService->createPaymentUrl([
                        'order_code' => 'TEMP_' . time() . '_' . $user->id,
                        'total_amount' => $total
                    ]);

                    DB::commit();
                    return redirect($vnpayUrl);
                }

                // Xử lý đơn hàng COD
                $order = Order::create([
                    'order_number' => 'ORD-' . time(),
                    'user_id' => $user->id,
                    'status_id' => 1, // Trạng thái mới
                    'user_name' => $request->user_name,
                    'user_phone' => $request->user_phone,
                    'province' => $request->province,
                    'district' => $request->district,
                    'ward' => $request->ward,
                    'address' => $request->address,
                    'notes' => $request->notes,
                    'payment_method' => 'cod',
                    'payment_status' => 'pending',
                    'discount' => $discount,
                    'total' => $subtotal,
                    'total_with_discount' => $total
                ]);

                // Tạo chi tiết đơn hàng và cập nhật tồn kho
                foreach ($selectedItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_variation_id' => $item->product_variation_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total' => $item->total
                    ]);

                    // Cập nhật số lượng tồn kho
                    $variation = ProductVariation::lockForUpdate()->find($item->product_variation_id);
                    $variation->stock -= $item->quantity;
                    $variation->save();

                    // Xóa item khỏi giỏ hàng
                    $item->delete();
                }

                // Xóa session selected items
                session()->forget('selected_cart_items');

                DB::commit();
                return redirect()->route('cart')->with('success', 'Đặt hàng thành công! Mã đơn hàng của bạn là ' . $order->order_number);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng: ' . $e->getMessage())->withInput();
        }
    }

    public function vnpayReturn(Request $request)
    {
        try {
            // Validate response từ VNPay
            if (!$this->vnpayService->validateResponse($request)) {
                return redirect()->route('cart')->with('error', 'Có lỗi xảy ra trong quá trình xác thực thanh toán.');
            }

            // Lấy thông tin đơn hàng từ session
            $pendingOrder = session('pending_order');
            if (!$pendingOrder) {
                throw new \Exception('Không tìm thấy thông tin đơn hàng');
            }

            // Validate stock before proceeding
            $selectedItems = collect($pendingOrder['selected_items']);
            foreach ($selectedItems as $item) {
                $variation = ProductVariation::find($item['product_variation_id']);
                if ($variation->stock < $item['quantity']) {
                    return redirect()->route('cart')->with('error', 'Một số sản phẩm đã hết hàng trong quá trình thanh toán.');
                }
            }

            // Tạo đơn hàng mới
            $order = Order::create([
                'order_number' => 'ORD-' . time(),
                'user_id' => $pendingOrder['user_id'],
                'status_id' => 1, // Trạng thái mới
                'user_name' => $pendingOrder['user_name'],
                'user_phone' => $pendingOrder['user_phone'],
                'province' => $pendingOrder['province'],
                'district' => $pendingOrder['district'],
                'ward' => $pendingOrder['ward'],
                'address' => $pendingOrder['address'],
                'notes' => $pendingOrder['notes'],
                'payment_method' => 'bank',
                'payment_status' => 'completed',
                'paid_at' => now(),
                'discount' => $pendingOrder['discount'],
                'total' => $pendingOrder['total'],
                'total_with_discount' => $pendingOrder['total']
            ]);

            // Tạo chi tiết đơn hàng và cập nhật tồn kho
            foreach ($selectedItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variation_id' => $item['product_variation_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total']
                ]);

                // Cập nhật số lượng tồn kho
                $variation = ProductVariation::find($item['product_variation_id']);
                $variation->stock -= $item['quantity'];
                $variation->save();

                // Xóa item khỏi giỏ hàng
                Cart::where('user_id', $pendingOrder['user_id'])
                    ->where('product_variation_id', $item['product_variation_id'])
                    ->delete();
            }

            // Xóa session
            session()->forget(['pending_order', 'selected_cart_items']);

            return redirect()->route('cart')->with('success', 'Thanh toán thành công! Mã đơn hàng của bạn là ' . $order->order_number);

        } catch (\Exception $e) {
            return redirect()->route('cart')->with('error', 'Có lỗi xảy ra trong quá trình xử lý thanh toán: ' . $e->getMessage());
        }
    }

    public function applyVoucher(Request $request)
    {
        try {
            Log::info('Starting voucher application', ['code' => $request->code]);
            
            $request->validate([
                'code' => 'required|string',
                'selected_items' => 'required|array|min:1'
            ], [
                'selected_items.required' => 'Vui lòng chọn ít nhất một sản phẩm để áp dụng mã giảm giá',
                'selected_items.array' => 'Dữ liệu không hợp lệ',
                'selected_items.min' => 'Vui lòng chọn ít nhất một sản phẩm để áp dụng mã giảm giá'
            ]);

            $user = Auth::user();
            Log::info('Finding promotion with code: ' . $request->code);
            
            $promotion = Promotion::where('code', $request->code)
                ->where('is_active', 1)
                ->where(function($query) {
                    $now = now();
                    $query->whereNull('starts_at')
                        ->orWhere('starts_at', '<=', $now);
                })
                ->where(function($query) {
                    $now = now();
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>=', $now);
                })
                ->where(function($query) {
                    $query->whereNull('usage_limit')
                        ->orWhereRaw('COALESCE(usage_count, 0) < usage_limit');
                })
                ->first();

            Log::info('Found promotion', ['promotion' => $promotion]);

            if (!$promotion) {
                Log::warning('Promotion not found or invalid');
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn'
                ], 400);
            }

            // Kiểm tra xem người dùng đã sử dụng mã này chưa
            $usedPromotion = UsedPromotion::where('user_id', $user->id)
                ->where('promotion_id', $promotion->id)
                ->first();

            if ($usedPromotion) {
                Log::warning('User already used this promotion', ['user_id' => $user->id, 'promotion_id' => $promotion->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã sử dụng mã giảm giá này'
                ], 400);
            }

            // Lấy các sản phẩm được chọn
            $selectedItems = Cart::where('user_id', $user->id)
                ->whereIn('product_variation_id', $request->selected_items)
                ->get();

            if ($selectedItems->isEmpty()) {
                Log::warning('No selected items found');
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm được chọn'
                ], 400);
            }

            $subtotal = $selectedItems->sum('total');
            Log::info('Selected items total: ' . $subtotal);

            // Kiểm tra điều kiện minimum_spend
            if ($promotion->minimum_spend && $subtotal < $promotion->minimum_spend) {
                Log::warning('Order total below minimum spend', [
                    'total' => $subtotal,
                    'minimum_spend' => $promotion->minimum_spend
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Tổng giá trị đơn hàng chưa đạt điều kiện tối thiểu (' . number_format($promotion->minimum_spend) . 'đ)'
                ], 400);
            }

            // Tính toán số tiền giảm giá
            $discountAmount = 0;
            if ($promotion->discount_type === 'percentage') {
                $discountAmount = round($subtotal * ($promotion->discount_value / 100));
                if ($promotion->maximum_discount && $discountAmount > $promotion->maximum_discount) {
                    $discountAmount = $promotion->maximum_discount;
                }
            } else {
                $discountAmount = min($promotion->discount_value, $subtotal);
            }

            // Tính tổng tiền sau khi giảm giá
            $total = $subtotal - $discountAmount;

            Log::info('Calculated totals', [
                'subtotal' => $subtotal,
                'discount' => number_format($discountAmount, 2),
                'total' => $total
            ]);

            $response = [
                'success' => true,
                'applied_voucher' => $request->code,
                'totals' => [
                    'subtotal' => $subtotal,
                    'discount' => $discountAmount,
                    'total' => $total
                ]
            ];

            // Lưu mã giảm giá vào session
            session(['applied_voucher' => $request->code]);
            session(['discount' => $discountAmount]);

            Log::info('Sending response', ['response' => $response]);
            return response()->json($response);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => $e->errors()['selected_items'][0] ?? 'Dữ liệu không hợp lệ'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Voucher application error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi áp dụng mã giảm giá'
            ], 500);
        }
    }

    public function removeVoucher(Request $request)
    {
        $user = Auth::user();
        $selectedItems = Cart::where('user_id', $user->id)
            ->whereIn('product_variation_id', $request->selected_items)
            ->with(['productVariation.product'])
            ->get();

        // Tính tổng tiền các sản phẩm được chọn
        $total = $selectedItems->sum('total');

        // Xóa mã giảm giá khỏi session
        session()->forget('applied_voucher');
        session()->forget('discount');

        return response()->json([
            'success' => true,
            'totals' => [
                'subtotal' => $total,
                'discount' => 0,
                'total' => $total
            ]
        ]);
    }
}
