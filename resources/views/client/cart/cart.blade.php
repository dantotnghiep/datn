@extends('client.layouts.master')
@section('content')
    @include('client.layouts.partials.lelf-navbar')

    <!-- =============== Cart area start =============== -->
    <div class="cart-area mt-100 ml-110">
        <div class="container">
            <!-- Add CSRF token meta tag -->
            <meta name="csrf-token" content="{{ csrf_token() }}">

            <!-- Add this alert section -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!-- End alert section -->

            <div class="row justify-content-center">
                <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-8">
                    <button type="button" id="select-all-btn" class="btn btn-secondary">Chọn tất cả</button>
                    <button type="button" id="deselect-all-btn" class="btn btn-secondary">Bỏ chọn tất cả</button>
                    @if ($cartItems->count() > 0)
                        <form action="{{ route('cart.checkout') }}" method="GET" id="checkout-form">
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
                                                <input type="checkbox" name="selected_items[]" value="{{ $item->id }}"
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
                                                    <input type="text" name="quantity" min="1"
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
                                                    class="d-inline">
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
                                        <h5 class="coupon-title">Coupon Code</h5>

                                        <form action="{{ route('cart.apply-coupon') }}" method="POST"
                                            class="coupon-input">
                                            @csrf
                                            <select name="discount_code" class="form-select" style="min-width: 200px;">
                                                <option value="">-- Chọn mã giảm giá --</option>
                                                @foreach ($discounts as $discount)
                                                    <option value="{{ $discount->code }}">
                                                        {{ $discount->code }}
                                                        (Giảm {{ number_format($discount->sale) }}%
                                                        @if ($discount->minOrderValue > 0)
                                                            - Đơn tối thiểu {{ number_format($discount->minOrderValue) }}đ
                                                        @endif)
                                                        - HSD: {{ $discount->endDate->format('d/m/Y') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary">Áp dụng</button>
                                        </form>

                                        <!-- Thêm debug info -->
                                        <div style="display: none;">
                                            <p>Debug Info:</p>
                                            <p>Current time: {{ now() }}</p>
                                            <p>Available discounts: {{ $discounts->count() }}</p>
                                            @foreach ($discounts as $discount)
                                                <p>
                                                    Code: {{ $discount->code }} <br>
                                                    Start: {{ $discount->startDate }} <br>
                                                    End: {{ $discount->endDate }}
                                                </p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-8 col-lg-8">
                                    <table class="table total-table">
                                        <tbody>
                                            <tr>
                                                <td class="tt-left">Cart Subtotal</td>
                                                <td></td>
                                                <td class="tt-right">{{ number_format($total) }} VND</td>
                                            </tr>
                                            @if (isset($discountAmount) && $discountAmount > 0)
                                                <tr>
                                                    <td class="tt-left">Discount ({{ session('discount_code') }})</td>
                                                    <td></td>
                                                    <td class="tt-right">-{{ number_format($discountAmount) }} VND</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td class="tt-left">Total</td>
                                                <td></td>
                                                <td class="tt-right"><strong>{{ number_format($finalTotal) }} VND</strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="cart-proceed-btns mt-4">
                                <button type="submit" class="cart-proceed">Checkout</button>
                                <a href="{{ route('client.index') }}" class="continue-shop">Continue Shopping</a>
                            </div>
                        </form>
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
                checkbox.addEventListener('change', updateCartTotal);
            });

            // Cập nhật giỏ hàng khi trang tải lần đầu
            updateCartTotal();

            // Select/Deselect all buttons
            document.getElementById('select-all-btn').addEventListener('click', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                updateCartTotal();
            });

            document.getElementById('deselect-all-btn').addEventListener('click', function() {
                checkboxes.forEach(checkbox => {
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
                // Tính tổng cho các mục đã chọn (cho quá trình thanh toán)
                let selectedTotal = 0;
                const selectedItems = document.querySelectorAll('.select-item:checked');

                selectedItems.forEach(checkbox => {
                    const row = checkbox.closest('tr');
                    const quantityInput = row.querySelector('.quantity-input');
                    const quantity = parseInt(quantityInput.value) || 0;
                    const price = parseFloat(quantityInput.dataset.price) || 0;
                    selectedTotal += quantity * price;
                });

                // Tính tổng cho tất cả các mục trong giỏ hàng
                let allItemsTotal = 0;
                const allQuantityInputs = document.querySelectorAll('.quantity-input');

                allQuantityInputs.forEach(input => {
                    const quantity = parseInt(input.value) || 0;
                    const price = parseFloat(input.dataset.price) || 0;
                    allItemsTotal += quantity * price;
                });

                // Cập nhật Cart Subtotal với tổng tất cả mục
                const subtotalElement = document.querySelector('.total-table tbody tr:first-child .tt-right');
                if (subtotalElement) {
                    subtotalElement.textContent = `${numberFormat(allItemsTotal)} VND`;
                }

                // Cập nhật Final Total
                const finalTotalElement = document.querySelector('.total-table tbody tr:last-child .tt-right strong');
                if (finalTotalElement) {
                    let finalTotal = allItemsTotal;

                    // Kiểm tra và áp dụng giảm giá nếu có
                    const discountRow = document.querySelector('.total-table tbody tr:nth-child(2)');
                    if (discountRow && discountRow.querySelector('.tt-left').textContent.includes('Discount')) {
                        const discountText = discountRow.querySelector('.tt-right').textContent;
                        const discountAmount = parseFloat(discountText.replace(/[^0-9]/g, '')) || 0;
                        finalTotal = allItemsTotal - discountAmount;
                    }

                    finalTotalElement.textContent = `${numberFormat(finalTotal)} VND`;
                }
            }

            function numberFormat(number) {
                return new Intl.NumberFormat('vi-VN').format(Math.round(number));
            }
        });
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
