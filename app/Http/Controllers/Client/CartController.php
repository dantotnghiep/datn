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
use App\Models\Location;

class CartController extends Controller
{
    protected $vnpayService;

    public function __construct(VNPayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
        $this->middleware('auth');
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
        $user = Auth::user();
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
        // Lấy giá trị discount từ session nếu có
        $discount = session('cart_discount', 0);
        $shippingFee = 0;
        $total = $subtotal - $discount + $shippingFee;

        // Lấy thông tin địa chỉ mặc định của user nếu có
        $defaultLocation = Location::where('user_id', $user->id)
            ->where('is_default', true)
            ->first();

        return view('client.cart.checkout', compact(
            'selectedItems', 'subtotal', 'discount', 'shippingFee', 'total', 'defaultLocation'
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

                // Tự động lưu thông tin địa chỉ vào location nếu khác với các địa chỉ đã có
                $locationData = [
                    'province' => $request->province,
                    'district' => $request->district,
                    'ward' => $request->ward,
                    'address' => $request->address,
                    'user_id' => $user->id,
                    'country' => 'Việt Nam',
                ];
                
                // Kiểm tra xem địa chỉ này đã tồn tại chưa
                $existingLocation = Location::where('user_id', $user->id)
                    ->where('province', $request->province)
                    ->where('district', $request->district)
                    ->where('ward', $request->ward)
                    ->where('address', $request->address)
                    ->first();
                
                // Nếu địa chỉ chưa tồn tại, lưu mới
                if (!$existingLocation) {
                    // Nếu là địa chỉ đầu tiên, đặt làm mặc định
                    $locationCount = Location::where('user_id', $user->id)->count();
                    $isDefault = ($locationCount == 0);
                    
                    // Nếu là địa chỉ mặc định, cập nhật các địa chỉ khác thành không mặc định
                    if ($isDefault) {
                        Location::where('user_id', $user->id)->update(['is_default' => false]);
                    }
                    
                    $locationData['is_default'] = $isDefault;
                    
                    // Lưu location mới
                    Location::create($locationData);
                }

                if ($request->payment_method === 'bank') {
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

            // Kiểm tra trạng thái thanh toán từ VNPay
            $vnpResponseCode = $request->vnp_ResponseCode;
            if ($vnpResponseCode != '00') {
                // Trạng thái không thành công (hủy, thất bại, lỗi...)
                return redirect()->route('cart')->with('error', 'Bạn đã hủy thanh toán hoặc thanh toán không thành công.');
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
        $user = Auth::user();

        try {
            $request->validate([
                'code' => 'required|string',
                'selected_items' => 'required|array|min:1'
            ], [
                'selected_items.required' => 'Vui lòng chọn ít nhất một sản phẩm để áp dụng mã giảm giá',
                'selected_items.array' => 'Dữ liệu không hợp lệ',
                'selected_items.min' => 'Vui lòng chọn ít nhất một sản phẩm để áp dụng mã giảm giá'
            ]);

            // Log for debugging
            Log::info('Applying voucher', [
                'code' => $request->code,
                'user_id' => $user->id,
                'selected_items' => $request->selected_items
            ]);

            // Get voucher from database - simplified for now
            $promotion = Promotion::where('code', $request->code)
                ->where('is_active', 1)
                ->first();

            if (!$promotion) {
                Log::info('Voucher not found or inactive');
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá không hợp lệ hoặc không tồn tại'
                ]);
            }

            Log::info('Promotion found', ['promotion' => $promotion->toArray()]);

            // Tính toán giảm giá
            $selectedItems = Cart::where('user_id', $user->id)
                ->whereIn('product_variation_id', $request->selected_items)
                ->get();

            $subtotal = $selectedItems->sum('total');

            // Check minimum spend
            if ($promotion->minimum_spend && $subtotal < $promotion->minimum_spend) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tổng giá trị đơn hàng phải ít nhất ' . number_format($promotion->minimum_spend) . 'đ để sử dụng mã giảm giá này'
                ]);
            }

            // Calculate discount
            $discount = 0;
            if ($promotion->discount_type === 'percentage') {
                $discount = ($subtotal * $promotion->discount_value / 100);
                // Apply maximum discount if set
                if ($promotion->maximum_discount && $discount > $promotion->maximum_discount) {
                    $discount = $promotion->maximum_discount;
                }
            } else {
                $discount = $promotion->discount_value;
            }

            // Lưu thông tin giảm giá vào session
            session(['cart_discount' => $discount]);
            session(['applied_voucher' => $promotion->code]);

            $total = $subtotal - $discount;

            Log::info('Voucher applied successfully', [
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total
            ]);

            return response()->json([
                'success' => true,
                'applied_voucher' => $promotion->code,
                'totals' => [
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'total' => $total
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error applying voucher: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi áp dụng mã giảm giá: ' . $e->getMessage()
            ]);
        }
    }

    public function removeVoucher(Request $request)
    {
        $user = Auth::user();

        // Xóa thông tin giảm giá khỏi session
        session()->forget(['cart_discount', 'applied_voucher']);

        // Tính lại tổng tiền không có giảm giá
        $selectedItems = Cart::where('user_id', $user->id)
            ->whereIn('product_variation_id', $request->selected_items)
            ->get();

        $total = $selectedItems->sum('total');

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
