<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Order_item;
use App\Services\OrderProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\VNPayService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $vnpayService;
    protected $orderProcessingService;

    public function __construct(VNPayService $vnpayService, OrderProcessingService $orderProcessingService)
    {
        $this->vnpayService = $vnpayService;
        $this->orderProcessingService = $orderProcessingService;
    }

    // Hiển thị trang thanh toán
    public function showCheckout()
    {
        try {
            // Kiểm tra xem người dùng đã đăng nhập chưa
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiến hành thanh toán');
            }

            $user = auth()->user();
            $addresses = auth()->user()->addresses; // Lấy tất cả địa chỉ
            $userEmail = $user->email;

            // Lấy thông tin giỏ hàng
            $selectedItems = request()->get('selected_items', []);
            $cartItems = empty($selectedItems) 
                ? Cart::where('user_id', auth()->id())->get()
                : Cart::where('user_id', auth()->id())->whereIn('id', $selectedItems)->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
            }

            // Tính tổng tiền
            $subtotal = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            // Lấy mã giảm giá nếu có
            $discountAmount = session('discount_amount', 0);
            $finalTotal = $subtotal - $discountAmount;

            return view('client.cart.checkout', compact('cartItems', 'user','userEmail', 'addresses', 'subtotal', 'discountAmount', 'finalTotal'));
        } catch (\Exception $e) {
            \Log::error('Error in checkout page: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'address_id' => 'required|exists:addresses,id,user_id,' . auth()->id(),
                'user_email' => 'required|email|max:255',
                'payment_method' => 'required|in:cod,vnpay',
            ]);

            // Lấy thông tin địa chỉ
            $address = Address::where('id', $validated['address_id'])
                ->where('user_id', auth()->id())
                ->firstOrFail();
            $shippingAddress = implode(', ', [$address->street, $address->ward, $address->district, $address->province]);

            // Lấy thông tin giỏ hàng
            $cartItems = Cart::where('user_id', auth()->id())->get();
            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Giỏ hàng trống!');
            }

            // Tính tổng tiền
            $totalAmount = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            // Chuyển đổi Cart items thành định dạng dữ liệu cho order processing
            $cartItemsData = $cartItems->map(function ($item) {
                return [
                    'variation_id' => $item->variation_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price
                ];
            })->toArray();
            
            // Kiểm tra tồn kho trước khi tiếp tục
            $stockValidation = $this->orderProcessingService->validateStock($cartItemsData);
            if (!$stockValidation['valid']) {
                $errorMessage = "Không thể đặt hàng! Một số sản phẩm không đủ số lượng";
                
                // Log lỗi để debug
                \Log::error('Stock validation failed when placing order: ' . json_encode($stockValidation['errors']));
                
                // Đánh dấu session để hiển thị thông báo lỗi
                session()->flash('stock_error', true);
                
                return back()->with('error', $errorMessage)->withInput();
            }

            // Xử lý theo phương thức thanh toán
            if ($validated['payment_method'] === 'vnpay') {
                // Reserve tồn kho khi thanh toán qua VNPay
                $reservedItems = [];
                foreach ($cartItemsData as $item) {
                    $variation = \App\Models\Variation::find($item['variation_id']);
                    if ($variation) {
                        // Lưu trữ thông tin stock cũ trước khi trừ
                        $reservedItems[] = [
                            'variation_id' => $variation->id,
                            'old_stock' => $variation->stock,
                            'quantity' => $item['quantity']
                        ];
                        
                        // Trừ tồn kho
                        $variation->stock -= $item['quantity'];
                        $variation->save();
                        
                        \Log::info('Reserved stock for variation ID: ' . $variation->id . ' - New stock: ' . $variation->stock);
                    }
                }
    

                // Lưu thông tin đơn hàng vào session để dùng sau khi thanh toán
                session([
                    'pending_order' => [
                        'user_name' => $address->recipient_name,
                        'user_phone' => $address->phone,
                        'user_email' => $validated['user_email'],
                        'shipping_address' => $shippingAddress,
                        'payment_method' => 'vnpay',
                        'cart_items' => $cartItemsData,
                        'total_amount' => $totalAmount
                    ],
                    'reserved_items' => $reservedItems // Lưu thông tin các items đã reserve
                ]);

                // Hiển thị thông báo trước khi chuyển đến VNPay
                session()->flash('vnpay_info', true);

                // Tạo URL thanh toán VNPay
                $tempOrderCode = 'TEMP_' . time() . '_' . auth()->id();
                $vnpayUrl = $this->vnpayService->createPaymentUrl([
                    'order_code' => $tempOrderCode,
                    'total_amount' => $totalAmount,
                ]);

                return redirect($vnpayUrl);
            }

            // Xử lý đơn hàng COD
            $orderData = [
                'user_name' => $address->recipient_name,
                'user_phone' => $address->phone,
                'user_email' => $validated['user_email'],
                'shipping_address' => $shippingAddress,
                'payment_method' => 'cod',
                'total_amount' => $totalAmount,
                'payment_status' => 'pending'
            ];

            // Xử lý đơn hàng trực tiếp thay vì qua queue
            $result = $this->orderProcessingService->processOrder($orderData, $cartItemsData, auth()->id());
            
            if ($result['success']) {
                return redirect()->route('cart.index')
                    ->with('success', $result['message']);
            } else {
                return back()->with('error', $result['message'])->withInput();
            }
        } catch (\Exception $e) {
            \Log::error('Order process failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng: ' . $e->getMessage())->withInput();
        }
    }

    // Thêm địa chỉ mới
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'is_default' => 'boolean',
        ]);

        \DB::beginTransaction();
        try {
            if ($request->boolean('is_default')) {
                // Nếu đặt làm mặc định, cập nhật tất cả địa chỉ khác thành không mặc định
                Address::where('user_id', auth()->id())->update(['is_default' => false]);
            }

            $address = Address::create([
                'user_id' => auth()->id(),
                'recipient_name' => $validated['recipient_name'],
                'phone' => $validated['phone'],
                'street' => $validated['street'],
                'ward' => $validated['ward'],
                'district' => $validated['district'],
                'province' => $validated['province'],
                'is_default' => $request->boolean('is_default', false),
            ]);

            \DB::commit();
            return redirect()->route('cart.checkout')->with('success', 'Thêm địa chỉ thành công!');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Thêm địa chỉ thất bại: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        return redirect()->route('cart.index')
            ->with('success', 'Đặt hàng thành công!');
    }

    public function vnpayReturn(Request $request)
    {
        // Validate response từ VNPay
        if (!$this->vnpayService->validateResponse($request)) {
            // Khôi phục lại tồn kho khi thanh toán thất bại
            $this->restoreReservedStock();
            
            return redirect()->route('cart.index')->with('error', 'Thanh toán VNPay không thành công. Vui lòng thử lại.');
        }

        try {
            // Lấy thông tin đơn hàng từ session
            $pendingOrder = session('pending_order');
            if (!$pendingOrder) {
                // Khôi phục lại tồn kho nếu không tìm thấy đơn hàng
                $this->restoreReservedStock();
                
                throw new \Exception('Không tìm thấy thông tin đơn hàng');
            }

            // Chuẩn bị data cho order
            $orderData = [
                'user_name' => $pendingOrder['user_name'],
                'user_phone' => $pendingOrder['user_phone'],
                'user_email' => $pendingOrder['user_email'],
                'shipping_address' => $pendingOrder['shipping_address'],
                'payment_method' => 'vnpay',
                'total_amount' => $pendingOrder['total_amount'],
                'payment_status' => 'completed',
                'vnpay_transaction_no' => $request->vnp_TransactionNo,
                'vnpay_payment_date' => $request->vnp_PayDate
            ];

            // Xử lý đơn hàng trực tiếp mà không cần kiểm tra tồn kho (đã trừ stock ở bước tạo VNPay)
            $result = $this->orderProcessingService->processOrderWithoutStockCheck(
                $orderData, 
                $pendingOrder['cart_items'], 
                auth()->id()
            );
            
            // Xóa thông tin đơn hàng tạm từ session
            session()->forget('pending_order');
            session()->forget('reserved_items');
            
            if ($result['success']) {
                return redirect()->route('cart.index')
                    ->with('success', $result['message']);
            } else {
                // Nếu xử lý đơn hàng thất bại, khôi phục lại tồn kho
                $this->restoreReservedStock();
                
                return redirect()->route('cart.index')
                    ->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            // Khôi phục lại tồn kho khi có lỗi
            $this->restoreReservedStock();
            
            \Log::error('VNPay order creation failed: ' . $e->getMessage());
            return redirect()->route('cart.index')
                ->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng sau khi thanh toán: ' . $e->getMessage());
        }
    }
    
    /**
     * Khôi phục lại tồn kho từ các mục đã reserve trong session
     */
    private function restoreReservedStock()
    {
        $reservedItems = session('reserved_items', []);
        
        if (!empty($reservedItems)) {
            foreach ($reservedItems as $item) {
                try {
                    $variation = \App\Models\Variation::find($item['variation_id']);
                    if ($variation) {
                        // Khôi phục lại stock
                        $variation->stock = $item['old_stock'];
                        $variation->save();
                        
                        \Log::info('Restored stock for variation ID: ' . $variation->id . ' - Reset to: ' . $variation->stock);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to restore stock for variation ID: ' . $item['variation_id'], [
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Xóa thông tin reserved từ session
            session()->forget('reserved_items');
        }
    }
}
