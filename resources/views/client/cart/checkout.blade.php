@extends('client.layouts.master')
@section('content')
@include('client.layouts.partials.lelf-navbar')

<!-- Toast Notifications -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999 !important;">
    @if(session('success'))
    <div id="successToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true" style="background-color: rgba(25, 135, 84, 0.95); color: white;">
        <div class="toast-header" style="background-color: rgba(25, 135, 84, 0.95); color: white;">
            <strong class="me-auto">Thành công!</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div id="errorToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true" style="background-color: rgba(220, 53, 69, 0.95); color: white;">
        <div class="toast-header" style="background-color: rgba(220, 53, 69, 0.95); color: white;">
            <strong class="me-auto">Lỗi!</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {!! session('error') !!}
        </div>
    </div>
    @endif
</div>

@if(session('stock_error') || (session('error') && strpos(session('error'), 'không đủ số lượng') !== false))
<!-- Toast notification đơn giản -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999 !important;">
    <div id="stockErrorToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true" style="display: block; background-color: rgba(220, 53, 69, 0.85); color: white; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); min-width: 250px; border-radius: 4px;">
        <div class="toast-header" style="background-color: rgba(220, 53, 69, 0.9); color: white;">
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <p style="color: white; margin-bottom: 0;">Không thể đặt hàng! Một số sản phẩm không đủ số lượng.</p>
        </div>
    </div>
</div>
@endif

<div class="checkout-area ml-110 mt-100">
    <div class="container">
        <div class="row">
            <div class="col-xxl-8 col-xl-8">
                <form id="payment-form" action="{{ route('order.store') }}" method="POST">
                    @csrf
                    <!-- Thêm input hidden để truyền selected_items -->
                    @foreach ($cartItems as $item)
                        <input type="hidden" name="selected_items[]" value="{{ $item->id }}">
                    @endforeach
                    <!-- Thông báo -->
                    @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                    <div class="alert alert-danger">{!! session('error') !!}</div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <h5 class="checkout-title">Billing Details</h5>
                    <div class="mb-4">
                        <h5>Người đặt hàng</h5>
                        <p><strong>Tên:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Số điện thoại:</strong> {{ $user->phone ?? 'Chưa cung cấp' }}</p>
                    </div>
                    @if ($addresses->isEmpty())
                    <div class="alert alert-warning">
                        Bạn chưa có địa chỉ nào. Vui lòng thêm địa chỉ để tiếp tục!
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        Thêm địa chỉ mới
                    </button>
                    @else
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="eg-input-group">
                                <label for="address_id">Chọn địa chỉ giao hàng</label>
                                <select name="address_id" id="address_id" class="form-select" required>
                                    @foreach ($addresses as $address)
                                    <option value="{{ $address->id }}"
                                        {{ $address->is_default ? 'selected' : '' }}>
                                        {{ $address->recipient_name }} - {{ $address->phone }} -
                                        {{ $address->street }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                Thêm địa chỉ mới
                            </button>

                            <!-- Thêm trường email -->
                            <div class="col-lg-12 mt-3">
                                <div class="eg-input-group">
                                    <label for="user_email">Email</label>
                                    <input type="email" class="form-control" id="user_email" name="user_email" value="{{ $userEmail }}" required>
                                    @error('user_email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Phương thức thanh toán -->
                    <div class="payment-methods mt-4">
                        <div class="form-check payment-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="paymentCOD" value="cod" checked>
                            <label class="form-check-label" for="paymentCOD">Cash on Delivery</label>
                            <p>Pay with cash upon delivery.</p>
                        </div>
                        <div class="form-check payment-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="paymentVNPay" value="vnpay">
                            <label class="form-check-label" for="paymentVNPay">Thanh toán qua VNPay</label>
                            <p>Thanh toán an toàn với VNPay.</p>
                        </div>
                    </div>

                    <div class="place-order-btn mt-4">
                        <button type="submit" class="place-order-btn">Place Order</button>
                    </div>
                    @endif
                </form>

                <!-- Modal thêm địa chỉ -->
                <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('order.storeAddress') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addAddressModalLabel">Thêm địa chỉ mới</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="recipient_name" class="form-label">Tên người nhận</label>
                                        <input type="text" class="form-control" id="recipient_name" name="recipient_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                        <input type="text" class="form-control" id="phone" name="phone" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="street" class="form-label">Đường</label>
                                        <input type="text" class="form-control" id="street" name="street" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ward" class="form-label">Phường/Xã</label>
                                        <input type="text" class="form-control" id="ward" name="ward" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="district" class="form-label">Quận/Huyện</label>
                                        <input type="text" class="form-control" id="district" name="district" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="province" class="form-label">Tỉnh/Thành phố</label>
                                        <input type="text" class="form-control" id="province" name="province" required>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1">
                                        <label class="form-check-label" for="is_default">Đặt làm địa chỉ mặc định</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-primary">Lưu địa chỉ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Giữ nguyên phần Order Summary -->
            <div class="col-xxl-4 col-xl-4">
                <div class="order-summary">
                    <div class="added-product-summary">
                        <h5 class="checkout-title">Order Summary</h5>
                        <ul class="added-products">
                            @foreach ($cartItems as $item)
                            @php
                            $product = $item->variation->product ?? null;
                            $mainImage = $product ? $product->images->where('is_main', 1)->first() : null;
                            @endphp
                            <li class="single-product">
                                <div class="product-img">
                                    <img src="{{ $mainImage ? asset($mainImage->url) : asset('default-image.jpg') }}"
                                        alt="{{ $item->product_name }}">
                                </div>
                                <div class="product-info">
                                    <h5 class="product-title"><a href="#">{{ $item->product_name }}</a></h5>
                                    <div class="product-total">
                                        <div class="quantity">
                                            <span>Số lượng: {{ $item->quantity }}</span>
                                        </div>
                                    </div>
                                    <div class="quantity">
                                        <strong>Giá: <span class="product-price">{{ number_format($item->price, 2) }}</span></strong>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="total-cost-summary">
                        <ul>
                            <li class="subtotal">Subtotal <span>{{ number_format($subtotal, 2) }}</span></li>
                            @if ($discountAmount > 0)
                            <li>Discount <span>-{{ number_format($discountAmount, 2) }}</span></li>
                            @endif
                            <li>Total <span>{{ number_format($finalTotal, 2) }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Giữ nguyên newsletter -->
<div class="newslatter-area ml-110 mt-100">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="newslatter-wrap text-center">
                    <h5>Connect To EG</h5>
                    <h2 class="newslatter-title">Join Our Newsletter</h2>
                    <p>Hey you, sign up it only, Get this limited-edition T-shirt Free!</p>
                    <form action="#" method="POST">
                        <div class="newslatter-form">
                            <input type="text" placeholder="Type Your Email">
                            <button type="submit">Send <i class="bi bi-envelope-fill"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Toast styles */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
    
    .toast {
        min-width: 300px;
        margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        opacity: 1 !important;
    }
    
    .toast.show {
        display: block;
        opacity: 1;
    }
    
    .toast-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 0.75rem 1rem;
    }
    
    .toast-body {
        padding: 1rem;
        font-size: 0.95rem;
    }
    
    .btn-close-white {
        filter: brightness(0) invert(1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý đóng toast
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach(toast => {
            const closeBtn = toast.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    toast.style.display = 'none';
                });
            }
            
            // Tự động ẩn toast sau 5 giây
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 300);
            }, 5000);
        });

        // Form validation và các xử lý khác
        const form = document.getElementById('payment-form');
        if (form) {
            form.addEventListener('submit', function(event) {
                const addressId = document.getElementById('address_id');
                if (addressId && !addressId.value) {
                    event.preventDefault();
                    alert('Vui lòng chọn hoặc thêm địa chỉ giao hàng!');
                    return;
                }

                const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
                if (!selectedPaymentMethod) {
                    event.preventDefault();
                    alert('Vui lòng chọn phương thức thanh toán!');
                    return;
                }

                if (selectedPaymentMethod !== 'vnpay' && selectedPaymentMethod !== 'cod') {
                    event.preventDefault();
                    alert('Phương thức thanh toán này chưa được hỗ trợ!');
                }
            });
        }
    });
</script>
@endsection