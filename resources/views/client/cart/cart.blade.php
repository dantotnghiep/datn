@extends('client.master')

@push('styles')
<style>
    /* Voucher list styles */
    #voucher-list {
        background-color: white;
        border: 1px solid rgba(0,0,0,0.15);
        box-shadow: 0 6px 12px rgba(0,0,0,0.175);
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        width: 100%;
    }
    #voucher-list .list-group-item {
        cursor: pointer;
        transition: background-color 0.3s;
        border-radius: 0;
    }
    #voucher-list .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .badge.bg-danger {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }
    .voucher-container {
        position: relative;
    }
</style>
@endpush

@section('content')
    <div class="container-small cart">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h2 class="mb-6">Giỏ hàng</h2>
        <div class="row g-5">
            <div class="col-12 col-lg-8">
                <div id="cartTable"
                    data-list='{"valueNames":["products","color","size","price","quantity","total"],"page":10}'>
                    <div class="mb-2">
                        <button type="button" class="btn btn-sm btn-outline-primary me-2" id="select-all-btn">Chọn tất
                            cả</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="deselect-all-btn">Bỏ chọn tất
                            cả</button>
                    </div>
                    <div class="table-responsive scrollbar mx-n1 px-1">
                        <table class="table fs-9 mb-0 border-top border-translucent">
                            <thead>
                                <tr>
                                    <th class="sort white-space-nowrap align-middle fs-10" scope="col"></th>
                                    <th class="sort white-space-nowrap align-middle" scope="col"
                                        style="min-width:250px;">SẢN PHẨM</th>
                                    <th class="sort align-middle text-end" scope="col" style="width:300px;">GIÁ</th>
                                    <th class="sort align-middle ps-5 " scope="col" style="width:300px;">SỐ LƯỢNG</th>
                                    <th class="sort align-middle text-end" scope="col" style="width:250px;">TỔNG TIỀN</th>
                                    <th class="sort text-end align-middle pe-0" scope="col"></th>
                                </tr>
                            </thead>
                            <tbody class="list" id="cart-table-body">
                                @forelse($cartItems as $item)
                                    <tr class="cart-table-row btn-reveal-trigger"
                                        data-product-id="{{ $item->productVariation->product->id }}"
                                        data-variation-id="{{ $item->productVariation->id }}">
                                        <td class="align-middle white-space-nowrap py-0">
                                            <div class="d-flex align-items-center">
                                                <input type="checkbox" class="form-check-input select-item me-2"
                                                    data-product-id="{{ $item->productVariation->product->id }}"
                                                    data-variation-id="{{ $item->productVariation->id }}">
                                                <a class="d-block border border-translucent rounded-2"
                                                    href="{{ route('product.detail', $item->productVariation->product->slug) }}">
                                                    <img src="{{ $item->productVariation->product->first_image }}"
                                                        alt="{{ $item->productVariation->product->name }}" width="53" />
                                                </a>
                                            </div>
                                        </td>
                                        <td class="products align-middle">
                                            <a class="fw-semibold mb-0 line-clamp-2"
                                                href="{{ route('product.detail', $item->productVariation->product->slug) }}">
                                                {{ $item->productVariation->product->name }}
                                            </a>
                                            <div class="text-muted small">{{ $item->productVariation->name }}</div>
                                        </td>
                                            <td class="price align-middle text-body fs-9 fw-semibold text-end">
                                            {{ number_format($item->price) }}đ
                                        </td>
                                        <td class="quantity align-middle fs-8 ps-5">
                                            <div class="input-group input-group-sm flex-nowrap quantity-control">
                                                <button class="btn btn-sm px-2 minus-btn" type="button">-</button>
                                                <input
                                                    class="form-control text-center input-spin-none bg-transparent border-0 px-0 quantity-input"
                                                    type="number" min="1" value="{{ $item->quantity }}"
                                                    aria-label="Số lượng" />
                                                <button class="btn btn-sm px-2 plus-btn" type="button">+</button>
                                            </div>
                                        </td>
                                        <td class="total align-middle fw-bold text-body-highlight text-end">
                                            {{ number_format($item->total) }}đ
                                        </td>
                                        <td class="align-middle white-space-nowrap text-end pe-0 ps-3">
                                            <button
                                                class="btn btn-sm text-body-tertiary text-opacity-85 text-body-tertiary-hover me-2 btn-remove-cart-item"><span
                                                    class="fas fa-trash"></span></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">Giỏ hàng của bạn đang trống.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-between-center mb-3">
                            <h3 class="card-title mb-0">Tổng cộng</h3>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between">
                                <p class="text-body fw-semibold">Tổng tiền :</p>
                                <p class="text-body-emphasis fw-semibold">{{ number_format($total) }}đ</p>
                            </div>
                            @if (isset($discount) && $discount > 0)
                                <div class="d-flex justify-content-between">
                                    <p class="text-body fw-semibold">Giảm giá :</p>
                                    <p class="text-danger fw-semibold">-{{ number_format($discount) }}đ</p>
                                </div>
                            @endif
                        </div>
                        <div class="voucher-container mb-3">
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="Nhập mã giảm giá hoặc click để chọn" id="voucher-input" />
                                <button class="btn btn-phoenix-primary px-5" id="apply-voucher-btn">Áp dụng</button>
                            </div>
                            <div id="voucher-feedback" class="invalid-feedback" style="display: none;"></div>
                            <div id="voucher-list" class="dropdown-menu w-100 position-static border shadow rounded-3 pt-0" style="display: none; max-height: 300px; overflow-y: auto;">
                                <div class="list-group list-group-flush">
                                    <!-- Voucher items will be loaded here -->
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between border-y border-dashed py-3 mb-4">
                            <h4 class="mb-0">Tổng cộng:</h4>
                            <h4 class="mb-">{{ number_format($total) }}đ</h4>
                        </div>
                        <button class="btn btn-primary w-100" id="checkout-btn" disabled type="button">
                            Thanh toán<span class="fas fa-chevron-right ms-1 fs-10"></span>
                        </button>
                        <form id="checkout-form" action="{{ route('cart.checkout.selected') }}" method="POST"
                            style="display:none;">
                            @csrf
                            <input type="hidden" name="selected_items" id="selected_items_input">
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div><!-- end of .container-->
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Xử lý tăng/giảm số lượng
            const quantityControls = document.querySelectorAll('.quantity-control');
            const checkoutBtn = document.getElementById('checkout-btn');
            const itemCheckboxes = document.querySelectorAll('.select-item');

            // Hàm kiểm tra và cập nhật trạng thái nút checkout
            function updateCheckoutButton() {
                const hasSelectedItems = Array.from(itemCheckboxes).some(checkbox => checkbox.checked);
                checkoutBtn.disabled = !hasSelectedItems;
                updateSelectedTotals();
            }

            // Hàm tính và cập nhật tổng tiền cho các sản phẩm được chọn
            function updateSelectedTotals() {
                let selectedTotal = 0;
                itemCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const row = checkbox.closest('tr');
                        const totalCell = row.querySelector('.total');
                        const total = parseInt(totalCell.textContent.replace(/[^\d]/g, ''));
                        selectedTotal += total;
                    }
                });

                // Cập nhật hiển thị tổng tiền cho Items subtotal
                const formattedTotal = new Intl.NumberFormat('vi-VN').format(selectedTotal) + 'đ';

                // Cập nhật Items subtotal
                document.querySelector('.card-body .d-flex:first-child .text-body-emphasis.fw-semibold').textContent = formattedTotal;

                // Cập nhật Total
                document.querySelector('.d-flex.justify-content-between.border-y h4:last-child').textContent = formattedTotal;
            }

            // Thêm sự kiện cho các checkbox
            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateCheckoutButton);
            });

            // Xử lý chọn tất cả và bỏ chọn tất cả
            const selectAllBtn = document.getElementById('select-all-btn');
            const deselectAllBtn = document.getElementById('deselect-all-btn');

            selectAllBtn.addEventListener('click', function() {
                itemCheckboxes.forEach(cb => cb.checked = true);
                updateCheckoutButton();
            });

            deselectAllBtn.addEventListener('click', function() {
                itemCheckboxes.forEach(cb => cb.checked = false);
                updateCheckoutButton();
            });

            // Kiểm tra ban đầu
            updateCheckoutButton();

            quantityControls.forEach(control => {
                const input = control.querySelector('.quantity-input');
                const minusBtn = control.querySelector('.minus-btn');
                const plusBtn = control.querySelector('.plus-btn');
                const row = control.closest('tr');
                const productId = row.dataset.productId;
                const variationId = row.dataset.variationId;

                // Xử lý nút giảm
                minusBtn.addEventListener('click', function() {
                    let value = parseInt(input.value);
                    if (value > 1) {
                        value--;
                        input.value = value;
                        updateCartItem(productId, variationId, value);
                    }
                });

                // Xử lý nút tăng
                plusBtn.addEventListener('click', function() {
                    let value = parseInt(input.value);
                    value++;
                    input.value = value;
                    updateCartItem(productId, variationId, value);
                });

                // Xử lý thay đổi từ input
                input.addEventListener('change', function() {
                    let value = parseInt(this.value);
                    if (value < 1) {
                        value = 1;
                        this.value = 1;
                    }
                    updateCartItem(productId, variationId, value);
                });
            });

            // Hàm cập nhật số lượng sản phẩm
            function updateCartItem(productId, variationId, quantity) {
                fetch('{{ route('cart.update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            variation_id: variationId,
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert(data.message || 'Có lỗi xảy ra');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi cập nhật giỏ hàng');
                    });
            }

            // Xử lý xóa sản phẩm
            const removeButtons = document.querySelectorAll('.btn-remove-cart-item');
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const productId = row.dataset.productId;
                    const variationId = row.dataset.variationId;

                    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
                        removeCartItem(productId, variationId);
                    }
                });
            });

            // Hàm xóa sản phẩm
            function removeCartItem(productId, variationId) {
                fetch('{{ route('cart.remove') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            variation_id: variationId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reload page to update cart UI
                            window.location.reload();
                        } else {
                            alert(data.message || 'Có lỗi xảy ra');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi xóa sản phẩm');
                    });
            }

            // Hàm cập nhật tổng tiền
            function updateCartTotals(totals) {
                // Cập nhật tổng tiền sản phẩm
                document.querySelector('.items-subtotal').textContent = totals.subtotal;
                // Cập nhật tổng tiền giảm giá
                document.querySelector('.discount').textContent = totals.discount;
                // Cập nhật thuế
                document.querySelector('.tax').textContent = totals.tax;
                // Cập nhật phí vận chuyển
                document.querySelector('.shipping').textContent = totals.shipping;
                // Cập nhật tổng tiền cuối cùng
                document.querySelector('.total').textContent = totals.total;
            }

            // Voucher handling
            const voucherInput = document.getElementById('voucher-input');
            const voucherList = document.getElementById('voucher-list');
            const applyVoucherBtn = document.getElementById('apply-voucher-btn');

            // Function to load vouchers
            function loadVouchers() {
                // Show loading state
                const voucherListGroup = voucherList.querySelector('.list-group');
                voucherListGroup.innerHTML = '<div class="list-group-item text-center"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Đang tải mã giảm giá...</div>';
                voucherList.style.display = 'block';

                // Load available promotions with timestamp to prevent caching
                fetch(`{{ route('promotions.available') }}?t=${Date.now()}`)
                    .then(response => {
                        return response.json();
                    })
                    .then(data => {
                        console.log('Promotion response:', data); // Debug log
                        voucherListGroup.innerHTML = '';

                        if (data.success && data.promotions && data.promotions.length > 0) {
                            data.promotions.forEach(promotion => {
                                // Format the discount value
                                let discountValue = '';
                                if (promotion.discount_type === 'percentage') {
                                    discountValue = `${promotion.discount_value}%`;
                                } else {
                                    discountValue = `${new Intl.NumberFormat('vi-VN').format(promotion.discount_value)}đ`;
                                }

                                // Format expiration date
                                let expiryText = 'Không giới hạn';
                                if (promotion.expires_at) {
                                    const expDate = new Date(promotion.expires_at);
                                    expiryText = `HSD: ${expDate.toLocaleDateString('vi-VN')}`;
                                }

                                const voucherItem = document.createElement('a');
                                voucherItem.href = '#';
                                voucherItem.className = 'list-group-item list-group-item-action';
                                voucherItem.innerHTML = `
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">${promotion.code}</h6>
                                            <small class="text-muted">${promotion.description || promotion.name || 'Mã giảm giá'}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-danger">${discountValue}</span>
                                            <small class="d-block text-muted">${expiryText}</small>
                                        </div>
                                    </div>
                                `;

                                voucherItem.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    voucherInput.value = promotion.code;
                                    voucherList.style.display = 'none';
                                    // Optionally, automatically apply the voucher
                                    // applyVoucherBtn.click();
                                });

                                voucherListGroup.appendChild(voucherItem);
                            });
                        } else {
                            voucherListGroup.innerHTML = '<div class="list-group-item">Không có mã giảm giá nào khả dụng</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading promotions:', error);
                        voucherListGroup.innerHTML = '<div class="list-group-item text-danger">Có lỗi xảy ra khi tải mã giảm giá</div>';
                    });
            }

            // Show vouchers when clicking on input
            voucherInput.addEventListener('focus', loadVouchers);

            // Also add a click handler to show vouchers
            voucherInput.addEventListener('click', function(e) {
                if (voucherList.style.display !== 'block') {
                    loadVouchers();
                }
            });

            // Hide voucher list when clicking outside
            document.addEventListener('click', function(e) {
                if (!voucherInput.contains(e.target) && !voucherList.contains(e.target) && voucherList.style.display === 'block') {
                    voucherList.style.display = 'none';
                }
            });

            // Apply voucher
            applyVoucherBtn.addEventListener('click', function() {
                const voucherCode = voucherInput.value.trim();
                const voucherFeedback = document.getElementById('voucher-feedback');
                const selectedItems = Array.from(document.querySelectorAll('.select-item:checked')).map(cb => cb.dataset.variationId);

                // Reset validation state
                voucherInput.classList.remove('is-invalid', 'is-valid');
                voucherFeedback.style.display = 'none';

                if (selectedItems.length === 0) {
                    voucherInput.classList.add('is-invalid');
                    voucherFeedback.textContent = 'Vui lòng chọn ít nhất một sản phẩm để áp dụng mã giảm giá';
                    voucherFeedback.style.display = 'block';
                    return;
                }

                // Show loading indicator
                applyVoucherBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang áp dụng...';
                applyVoucherBtn.disabled = true;

                // Nếu input trống, gọi API xóa mã giảm giá
                const endpoint = voucherCode ? '{{ route('cart.apply-voucher') }}' : '{{ route('cart.remove-voucher') }}';

                fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        code: voucherCode,
                        selected_items: selectedItems
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Response from server:', data);

                    // Reset button state
                    applyVoucherBtn.innerHTML = 'Apply';
                    applyVoucherBtn.disabled = false;

                    if (data.success) {
                        // Show success indication
                        voucherInput.classList.add('is-valid');

                        // Format numbers for display
                        const formatPrice = (price) => new Intl.NumberFormat('vi-VN').format(price) + 'đ';

                        // Cập nhật Items subtotal
                        const subtotalElement = document.querySelector('.card-body .d-flex:first-child .text-body-emphasis.fw-semibold');
                        if (subtotalElement) {
                            subtotalElement.textContent = formatPrice(data.totals.subtotal);
                        }

                        // Xóa thông tin voucher và discount cũ nếu có
                        const existingVoucherInfo = document.querySelector('.applied-voucher-info');
                        if (existingVoucherInfo) {
                            existingVoucherInfo.remove();
                        }
                        const existingDiscountRow = document.querySelector('.discount-row');
                        if (existingDiscountRow) {
                            existingDiscountRow.remove();
                        }

                        // Thêm thông tin mới nếu có mã giảm giá
                        if (data.applied_voucher) {
                            // Thêm thông tin voucher
                            const voucherInfoHtml = `
                                <div class="d-flex justify-content-between align-items-center mb-2 applied-voucher-info">
                                    <p class="text-body mb-0">
                                        <small>Applied voucher: <span class="fw-semibold">${data.applied_voucher}</span></small>
                                    </p>
                                </div>
                            `;

                            // Thêm thông tin discount
                            const discountHtml = `
                                <div class="d-flex justify-content-between discount-row">
                                    <p class="text-body fw-semibold">Discount :</p>
                                    <p class="text-danger fw-semibold">-${formatPrice(data.totals.discount)}</p>
                                </div>
                            `;

                            // Thêm vào DOM
                            const summaryDiv = document.querySelector('.card-body > div:nth-child(2)');
                            summaryDiv.insertAdjacentHTML('afterend', voucherInfoHtml);
                            document.querySelector('.applied-voucher-info').insertAdjacentHTML('afterend', discountHtml);
                        }

                        // Cập nhật Total
                        const totalElement = document.querySelector('.d-flex.justify-content-between.border-y h4:last-child');
                        if (totalElement) {
                            totalElement.textContent = formatPrice(data.totals.total);
                        }

                        // Clear input nếu xóa mã giảm giá
                        if (!data.applied_voucher) {
                            voucherInput.value = '';
                        }
                    } else {
                        // Show error indication
                        voucherInput.classList.add('is-invalid');
                        voucherFeedback.textContent = data.message || 'Có lỗi xảy ra khi áp dụng mã giảm giá';
                        voucherFeedback.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    applyVoucherBtn.innerHTML = 'Apply';
                    applyVoucherBtn.disabled = false;

                    // Show error indication
                    voucherInput.classList.add('is-invalid');
                    voucherFeedback.textContent = 'Có lỗi xảy ra khi xử lý mã giảm giá';
                    voucherFeedback.style.display = 'block';
                });
            });
        });
        document.getElementById('checkout-btn').addEventListener('click', function() {
            const selected = Array.from(document.querySelectorAll('.select-item:checked')).map(cb => cb.dataset
                .variationId);
            document.getElementById('selected_items_input').value = JSON.stringify(selected);
            document.getElementById('checkout-form').submit();
        });
    </script>
@endpush
