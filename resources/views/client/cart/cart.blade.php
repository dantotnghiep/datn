@extends('client.layouts.master')
@section('content')
    @include('client.layouts.partials.lelf-navbar')


    <!-- =============== Cart area start =============== -->
    <div class="cart-area mt-100 ml-110">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-8">
                    <div class="cart-controls mb-3">
                        <button type="button" id="selectAll" class="btn btn-secondary">Chọn tất cả</button>
                        <button type="button" id="deleteSelected" class="btn btn-danger">Xóa đã chọn</button>
                    </div>
                    <table class="table cart-table">
                        <thead>
                            <tr>
                                <th scope="col" >
                                </th>
                                <th scope="col">Image</th>
                                <th scope="col">Product Title</th>
                                <th scope="col">Unite Price</th>
                                <th scope="col">Discount Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Subtotal</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input item-checkbox text-center"
                                           value="{{ $item->id }}" data-price="{{ $item->getFinalPriceAttribute() }}">
                                </td>
                                <td class="image-col">
                                    <img src="{{ asset($item->main_image) }}" alt="{{ $item->name }}">
                                </td>
                                <td class="product-col">
                                    <a href="" class="product-title">
                                        {{ $item->name }}
                                    </a>
                                </td>
                                <td class="unite-col">
                                    @if($item->is_on_sale)
                                        <del><span class="unite-price-del">${{ number_format($item->price, 2) }}</span></del>
                                    @endif
                                    <span class="unite-price">${{ number_format($item->getFinalPriceAttribute(), 2) }}</span>
                                </td>
                                <td class="discount-col">
                                    @if($item->is_on_sale)
                                        <span class="discount-price">-{{ $item->discount_percent }}%</span>
                                    @endif
                                </td>
                                <td class="quantity-col">
                                    <div class="quantity-wrapper" style="display: flex; align-items: center;">
                                        <button type="button" class="quantity-btn minus-btn" style="padding: 5px 10px; border: 1px solid #ddd; background: #f8f9fa;">-</button>
                                        <input type="number" class="quantity-input" min="1" max="90"
                                               oninput="validity.valid||(value='')"
                                               value="{{ $item->quantity }}"
                                               data-id="{{ $item->id }}"
                                               data-price="{{ $item->getFinalPriceAttribute() }}"
                                               style="width: 60px; text-align: center; margin: 0 5px;">
                                        <button type="button" class="quantity-btn plus-btn" style="padding: 5px 10px; border: 1px solid #ddd; background: #f8f9fa;">+</button>
                                    </div>
                                </td>
                                <td class="total-col">$<span class="item-total">{{ number_format($item->total, 2) }}</span></td>
                                <td class="delete-col">
                                    <div class="delete-icon">
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="delete-form" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0">
                                                <i class="flaticon-letter-x"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-60">
                <div class="col-xxl-4 col-lg-4">
                    <div class="cart-coupon-input">
                        <h5 class="coupon-title">Coupon Code</h5>
                        <form class="coupon-input d-flex align-items-center">
                            <input type="text" placeholder="Coupon Code">
                            <button type="submit">Apply Code</button>
                        </form>
                    </div>
                </div>
                <div class="col-xxl-8 col-lg-8">
                    <table class="table total-table">
                        <tbody>
                            <tr>
                                <td class="tt-left">Shipping</td>
                                <td>
                                    <ul class="cart-cost-list">
                                        <li>Shipping Fee</li>
                                        <li>Total ( tax excl.)</li>
                                        <li>Total ( tax incl.)</li>
                                        <li>Taxes</li>
                                    </ul>
                                </td>
                                <td class="tt-right cost-info-td">
                                    <ul class="cart-cost">
                                        <li>Free</li>
                                        <li>$15</li>
                                        <li>$15</li>
                                        <li>$5</li>
                                        <li>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td class="tt-left">Subtotal</td>
                                <td>

                                </td>
                                <td class="tt-right">$162.70</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="cart-proceed-btns">
                        <a href="checkout.html" class="cart-proceed">Proceed to Checkout</a>
                        <a href="product.html" class="continue-shop">Continue to shopping</a>
                    </div>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInputs = document.querySelectorAll('.quantity-input');

    quantityInputs.forEach(input => {
        const minusBtn = input.previousElementSibling;
        const plusBtn = input.nextElementSibling;

        // Xử lý nút giảm
        minusBtn.addEventListener('click', function() {
            let currentValue = parseInt(input.value) || 1;
            if (currentValue > 1) {
                input.value = currentValue - 1;
                input.dispatchEvent(new Event('change'));
            }
        });

        // Xử lý nút tăng
        plusBtn.addEventListener('click', function() {
            let currentValue = parseInt(input.value) || 1;
            if (currentValue < 90) {
                input.value = currentValue + 1;
                input.dispatchEvent(new Event('change'));
            }
        });

        // Existing input event listener
        input.addEventListener('input', function() {
            if (this.value < 1) {
                this.value = 1;
            }
            if (this.value > 90) {
                this.value = 90;
            }
        });

        // Existing change event listener
        input.addEventListener('change', function() {
            const itemId = this.dataset.id;
            const quantity = Math.max(1, Math.min(90, parseInt(this.value) || 1));
            this.value = quantity;
            const price = parseFloat(this.dataset.price);

            // Gửi request cập nhật số lượng
            fetch(`/cart/update/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const totalElement = this.closest('tr').querySelector('.item-total');
                    totalElement.textContent = data.total.toFixed(2);

                    const cartTotalElement = document.querySelector('.cart-total');
                    if (cartTotalElement) {
                        cartTotalElement.textContent = data.cart_total.toFixed(2);
                    }
                } else {
                    alert('Có lỗi xảy ra khi cập nhật số lượng');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi cập nhật số lượng');
            });
        });
    });

    // Checkbox handling
    const checkAll = document.getElementById('checkAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const selectAllBtn = document.getElementById('selectAll');
    const deleteSelectedBtn = document.getElementById('deleteSelected');

    // Handle "Check All" checkbox
    checkAll.addEventListener('change', function() {
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Handle "Select All" button
    selectAllBtn.addEventListener('click', function() {
        checkAll.checked = true;
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
    });

    // Handle "Delete Selected" button
    deleteSelectedBtn.addEventListener('click', function() {
        const selectedIds = Array.from(itemCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            alert('Vui lòng chọn sản phẩm để xóa');
            return;
        }

        if (confirm('Bạn có chắc muốn xóa các sản phẩm đã chọn?')) {
            fetch('/cart/remove-multiple', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Reload the page to reflect changes
                    window.location.reload();
                } else {
                    alert('Có lỗi xảy ra khi xóa sản phẩm');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi xóa sản phẩm');
            });
        }
    });

    // Xử lý form xóa sản phẩm
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            fetch(this.action, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Xóa dòng sản phẩm khỏi bảng
                    this.closest('tr').remove();

                    // Nếu muốn reload trang
                    window.location.reload();
                } else {
                    alert('Có lỗi xảy ra khi xóa sản phẩm');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi xóa sản phẩm');
            });
        });
    });
});
</script>
@endpush
