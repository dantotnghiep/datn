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

    <!-- =============== Cart area start =============== -->
    <div class="cart-area mt-100 ml-110">
        <div class="container">
            <!-- Add CSRF token meta tag -->
            <meta name="csrf-token" content="{{ csrf_token() }}">

            <div class="row justify-content-center">
                <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-8">
                    <button type="button" id="select-all-btn" class="btn btn-secondary">Chọn tất cả</button>
                    <button type="button" id="deselect-all-btn" class="btn btn-secondary">Bỏ chọn tất cả</button>
                    @if ($cartItems->count() > 0)
                        <div class="cart-content">
                            <!-- Bảng sản phẩm -->
                            <table class="table cart-table">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Product Details</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Subtotal</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach ($cartItems as $item)
                                        @php
                                            $variation = $item->variation;
                                            $product = $variation->product;
                                            $mainImage = $product->images()->where('is_main', 1)->first();
                                            $subtotal = $item->price * $item->quantity;
                                            $total += $subtotal;
                                        @endphp
                                        <tr>
                                            <td style="text-align: center; vertical-align: middle;">
                                                <input type="checkbox" data-item-id="{{ $item->id }}"
                                                    class="select-item" style="width: 20px; height: 20px;">
                                            </td>
                                            <td class="image-col">
                                                <img src="{{ $mainImage ? $mainImage->url : 'default-image.jpg' }}"
                                                    alt="{{ $item->product_name }}" style="max-width: 100px;">
                                            </td>
                                            <td class="product-col">
                                                <div class="product-details">
                                                    <h5>{{ $item->product_name }}</h5>
                                                    <p>Color: {{ $item->color }}</p>
                                                    <p>Size: {{ $item->size }}</p>
                                                </div>
                                            </td>
                                            <td class="price-col">
                                                @if ($variation->sale_price && now()->between($variation->sale_start, $variation->sale_end))
                                                    <del class="text-muted">{{ number_format($variation->price) }} VND</del>
                                                    <div class="text-danger">{{ number_format($variation->sale_price) }}
                                                        VND
                                                    </div>
                                                @else
                                                    <div>{{ number_format($item->price) }} VND</div>
                                                @endif
                                            </td>
                                            <td class="quantity-col">
                                                <div class="quantity">
                                                    <button type="button" class="quantity-btn quantity-decrease">-</button>
                                                    <input type="text" min="1"
                                                        max="{{ $variation->stock }}" value="{{ $item->quantity }}"
                                                        class="quantity-input"
                                                        data-item-id="{{ $item->id }}"
                                                        data-price="{{ $item->price }}"
                                                        data-subtotal-id="subtotal-{{ $item->id }}"
                                                        readonly>
                                                    <button type="button" class="quantity-btn quantity-increase">+</button>
                                                </div>
                                            </td>
                                            <td class="total-col" id="subtotal-{{ $item->id }}">{{ number_format($subtotal) }} VND</td>
                                            <td class="delete-col">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST"
                                                    class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="flaticon-letter-x"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="row mt-60">
                                <div class="col-xxl-4 col-lg-4">
                                    <div class="cart-coupon-input">
                                        <h5 class="coupon-title">Mã giảm giá</h5>

                                        <form action="{{ route('cart.apply-coupon') }}" method="POST"
                                            class="coupon-input">
                                            @csrf
                                            <select name="discount_code" class="form-select" style="min-width: 200px;">
                                                <option value="">-- Chọn mã giảm giá --</option>
                                                @foreach ($discounts as $discount)
                                                    <option value="{{ $discount->code }}" 
                                                        {{ session('discount_code') == $discount->code ? 'selected' : '' }}>
                                                        {{ $discount->code }} -
                                                        @if($discount->type == 'percentage')
                                                            Giảm {{ number_format($discount->sale) }}%
                                                            @if($discount->maxDiscount)
                                                                (Tối đa {{ number_format($discount->maxDiscount) }}đ)
                                                            @endif
                                                        @else
                                                            Giảm {{ number_format($discount->sale) }}đ
                                                        @endif
                                                        @if ($discount->minOrderValue > 0)
                                                            - Đơn tối thiểu {{ number_format($discount->minOrderValue) }}đ
                                                        @endif
                                                        @if ($discount->maxUsage)
                                                            - Còn {{ $discount->maxUsage - $discount->usageCount }} lượt
                                                        @endif
                                                        - HSD: {{ $discount->endDate->format('d/m/Y') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary">Áp dụng</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-xxl-8 col-lg-8">
                                    <table class="table total-table">
                                        <tbody>
                                            <tr>
                                                <td class="tt-left">Tạm tính</td>
                                                <td></td>
                                                <td class="tt-right">{{ number_format($total) }} VNĐ</td>
                                            </tr>
                                            @if (session('discount_code'))
                                                @php
                                                    $appliedDiscount = $discounts->where('code', session('discount_code'))->first();
                                                @endphp
                                                @if($appliedDiscount)
                                                    <tr>
                                                        <td class="tt-left">
                                                            Giảm giá ({{ session('discount_code') }})
                                                            @if($appliedDiscount->type == 'percentage')
                                                                ({{ number_format($appliedDiscount->sale) }}%)
                                                            @endif
                                                        </td>
                                                        <td></td>
                                                        <td class="tt-right text-danger">-{{ number_format($discountAmount) }} VNĐ</td>
                                                    </tr>
                                                @endif
                                            @endif
                                            <tr>
                                                <td class="tt-left">Tổng tiền</td>
                                                <td></td>
                                                <td class="tt-right"><strong>{{ number_format($finalTotal) }} VNĐ</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Form checkout tách biệt -->
                            <form action="{{ route('cart.checkout') }}" method="GET" id="checkout-form" onsubmit="return validateCheckout()">
                                <!-- Hidden inputs để chứa item đã chọn -->
                                @foreach ($cartItems as $item)
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}"
                                        class="hidden-item-{{ $item->id }}" style="display: none;">
                                @endforeach

                                <div class="cart-proceed-btns mt-4">
                                    <button type="submit" class="cart-proceed">Checkout</button>
                                    <a href="{{ route('client.index') }}" class="continue-shop">Continue Shopping</a>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="text-center">
                            <h3>Your cart is empty</h3>
                            <a href="{{ route('client.index') }}" class="btn btn-primary mt-3">Continue Shopping</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- =============== Cart area end =============== -->

    <!-- ===============  newslatter area start  =============== -->
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
    <!-- ===============  newslatter area end  =============== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tự động chọn tất cả các sản phẩm khi trang tải xong
            const checkboxes = document.querySelectorAll('.select-item');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
                checkbox.addEventListener('change', function() {
                    // Khi checkbox thay đổi, cập nhật trạng thái cho checkbox ẩn tương ứng
                    const itemId = this.getAttribute('data-item-id');
                    const hiddenCheckbox = document.querySelector(`.hidden-item-${itemId}`);
                    if (hiddenCheckbox) {
                        hiddenCheckbox.checked = this.checked;
                    }
                    updateCartTotal();
                });
            });

            // Chọn tất cả các checkbox ẩn khi trang load
            const hiddenCheckboxes = document.querySelectorAll('[class^="hidden-item-"]');
            hiddenCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });

            // Cập nhật giỏ hàng khi trang tải lần đầu
            updateCartTotal();

            // Select/Deselect all buttons
            document.getElementById('select-all-btn').addEventListener('click', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                // Đồng bộ hóa với checkbox ẩn
                hiddenCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                updateCartTotal();
            });

            document.getElementById('deselect-all-btn').addEventListener('click', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                // Đồng bộ hóa với checkbox ẩn
                hiddenCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateCartTotal();
            });

            const quantityInputs = document.querySelectorAll('.quantity-input');
            quantityInputs.forEach(input => {
                // The input is now readonly, so we only need to handle programmatic changes
                input.addEventListener('change', handleQuantityChange);
            });

            // Add event handlers for the quantity buttons
            const decreaseButtons = document.querySelectorAll('.quantity-decrease');
            const increaseButtons = document.querySelectorAll('.quantity-increase');

            decreaseButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.parentNode.querySelector('.quantity-input');
                    let value = parseInt(input.value);
                    if (value > 1) {
                        input.value = value - 1;
                        // Trigger the change event manually
                        const event = new Event('change', { bubbles: true });
                        input.dispatchEvent(event);
                    }
                });
            });

            increaseButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.parentNode.querySelector('.quantity-input');
                    let value = parseInt(input.value);
                    let max = parseInt(input.getAttribute('max'));
                    if (value < max) {
                        input.value = value + 1;
                        // Trigger the change event manually
                        const event = new Event('change', { bubbles: true });
                        input.dispatchEvent(event);
                    }
                });
            });

            function handleQuantityChange() {
                const itemId = this.dataset.itemId;
                let quantity = parseInt(this.value) || 0;
                const price = parseFloat(this.dataset.price) || 0;
                const subtotalId = this.dataset.subtotalId;

                // Kiểm tra và giới hạn số lượng
                const maxStock = parseInt(this.getAttribute('max')) || 1;
                if (quantity > maxStock) {
                    quantity = maxStock;
                    this.value = maxStock;
                }
                if (quantity < 1) {
                    quantity = 1;
                    this.value = 1;
                }

                // Cập nhật subtotal cho sản phẩm
                const subtotal = quantity * price;
                const subtotalElement = document.getElementById(subtotalId);
                if (subtotalElement) {
                    subtotalElement.textContent = `${numberFormat(subtotal)} VND`;
                }

                // Gọi hàm cập nhật tổng giỏ hàng
                updateCartTotal();

                // Gửi Ajax request
                const token = document.querySelector('meta[name="csrf-token"]').content;
                fetch(`{{ url('/cart') }}/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity: quantity,
                        _method: 'PUT'
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            console.error('Response error:', err);
                            throw new Error(err.message || 'Lỗi từ máy chủ');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Server response:', data);
                    if (data.success) {
                        // Cập nhật giá trị trong input box nếu có thay đổi từ server
                        if (data.quantity && parseInt(data.quantity) !== quantity) {
                            this.value = data.quantity;
                            // Cập nhật lại subtotal và tổng
                            const newSubtotal = data.quantity * price;
                            if (subtotalElement) {
                                subtotalElement.textContent = `${numberFormat(newSubtotal)} VND`;
                            }
                            updateCartTotal();
                        }
                    } else {
                        console.error('Error:', data.message);
                        // Có thể hiển thị thông báo lỗi
                        alert(data.message || 'Có lỗi xảy ra khi cập nhật giỏ hàng');
                    }
                })
                .catch(error => {
                    console.error('Error details:', error);
                    alert('Có lỗi xảy ra khi cập nhật giỏ hàng: ' + error.message);
                });
            }

            function updateCartTotal() {
                // Tính tổng cho các mục đã chọn
                let selectedTotal = 0;
                const selectedItems = document.querySelectorAll('.select-item:checked');

                selectedItems.forEach(checkbox => {
                    const row = checkbox.closest('tr');
                    const quantityInput = row.querySelector('.quantity-input');
                    const quantity = parseInt(quantityInput.value) || 0;
                    const price = parseFloat(quantityInput.dataset.price) || 0;
                    selectedTotal += quantity * price;
                });

                // Cập nhật Cart Subtotal với tổng các mục đã chọn
                const subtotalElement = document.querySelector('.total-table tbody tr:first-child .tt-right');
                if (subtotalElement) {
                    subtotalElement.textContent = `${numberFormat(selectedTotal)} VND`;
                }

                // Cập nhật Final Total
                const finalTotalElement = document.querySelector('.total-table tbody tr:last-child .tt-right strong');
                if (finalTotalElement) {
                    let finalTotal = selectedTotal;

                    // Kiểm tra và áp dụng giảm giá nếu có
                    const discountRow = document.querySelector('.total-table tbody tr:nth-child(2)');
                    if (discountRow && discountRow.querySelector('.tt-left').textContent.includes('Discount')) {
                        const discountText = discountRow.querySelector('.tt-right').textContent;
                        const discountAmount = parseFloat(discountText.replace(/[^0-9]/g, '')) || 0;
                        finalTotal = selectedTotal - discountAmount;
                    }

                    finalTotalElement.textContent = `${numberFormat(finalTotal)} VND`;
                }
            }

            function numberFormat(number) {
                return new Intl.NumberFormat('vi-VN').format(Math.round(number));
            }
        });

        function validateCheckout() {
            const selectedItems = document.querySelectorAll('.select-item:checked');
            if (selectedItems.length === 0) {
                alert('Vui lòng chọn ít nhất một sản phẩm để tiến hành thanh toán!');
                return false;
            }
            return true;
        }
    </script>

    <style>
        .select-item {
            display: block;
            margin: 0 auto;
        }

        .quantity {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            border: 1px solid #dee2e6;
            margin: 0 5px;
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: bold;
        }

        .quantity-btn:hover {
            background-color: #e9ecef;
        }

        /* No need for browser spinner removal since we're using text input */
    </style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle toast close button
        var toastEl = document.getElementById('stockErrorToast');
        if (toastEl) {
            var closeBtn = toastEl.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    toastEl.style.display = 'none';
                });
            }

            // Auto hide toast after 10 seconds
            setTimeout(function() {
                toastEl.style.display = 'none';
            }, 10000);
        }
    });
</script>
@endsection

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

    /* Các style khác giữ nguyên */
    .select-item {
        display: block;
        margin: 0 auto;
    }

    .quantity {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-input {
        width: 50px;
        text-align: center;
        border: 1px solid #dee2e6;
        margin: 0 5px;
    }

    .quantity-btn {
        width: 30px;
        height: 30px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: bold;
    }

    .quantity-btn:hover {
        background-color: #e9ecef;
    }
</style>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý toast notifications
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

        // Các script khác giữ nguyên
        // ... existing scripts ...
    });
</script>
@endsection