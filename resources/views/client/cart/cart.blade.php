@extends('client.master')
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
        <nav class="mb-3" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#!">Page 1</a></li>
                <li class="breadcrumb-item"><a href="#!">Page 2</a></li>
                <li class="breadcrumb-item active" aria-current="page">Default</li>
            </ol>
        </nav>
        <h2 class="mb-6">Cart</h2>
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
                                        style="min-width:250px;">PRODUCTS</th>
                                    <th class="sort align-middle" scope="col" style="width:180px;">BIẾN THỂ</th>
                                    <th class="sort align-middle text-end" scope="col" style="width:300px;">PRICE</th>
                                    <th class="sort align-middle ps-5" scope="col" style="width:200px;">QUANTITY</th>
                                    <th class="sort align-middle text-end" scope="col" style="width:250px;">TOTAL</th>
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
                                                    <img src="{{ asset(optional($item->productVariation->product->images->first())->image_path ?? 'assets/img/products/default.png') }}"
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
                                        <td class="align-middle white-space-nowrap fs-9 text-body">
                                            @if ($item->productVariation->attributeValues && count($item->productVariation->attributeValues))
                                                {{ $item->productVariation->attributeValues->map(function ($attrVal) {
                                                        return ($attrVal->attribute ? $attrVal->attribute->name . ': ' : '') . $attrVal->value;
                                                    })->implode(' / ') }}
                                            @else
                                                -
                                            @endif
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
                            <h3 class="card-title mb-0">Summary</h3>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between">
                                <p class="text-body fw-semibold">Items subtotal :</p>
                                <p class="text-body-emphasis fw-semibold">{{ number_format($total) }}đ</p>
                            </div>
                            @if (isset($discount) && $discount > 0)
                                <div class="d-flex justify-content-between">
                                    <p class="text-body fw-semibold">Discount :</p>
                                    <p class="text-danger fw-semibold">-{{ number_format($discount) }}đ</p>
                                </div>
                            @endif
                        </div>
                        <div class="input-group mb-3">
                            <input class="form-control" type="text" placeholder="Voucher" id="voucher-input" />
                            <button class="btn btn-phoenix-primary px-5" id="apply-voucher-btn">Apply</button>
                        </div>
                        <div id="voucher-list" class="dropdown-menu w-100 position-static border-0 pt-0" style="display: none;">
                            <div class="list-group list-group-flush rounded-3">
                                <!-- Voucher items will be loaded here -->
                            </div>
                        </div>
                        <div class="d-flex justify-content-between border-y border-dashed py-3 mb-4">
                            <h4 class="mb-0">Total :</h4>
                            <h4 class="mb-">{{ number_format($total) }}đ</h4>
                        </div>
                        <button class="btn btn-primary w-100" id="checkout-btn" disabled type="button">
                            Proceed to check out<span class="fas fa-chevron-right ms-1 fs-10"></span>
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

            voucherInput.addEventListener('focus', function() {
                // Load available promotions
                fetch('{{ route('promotions.available') }}')
                    .then(response => response.json())
                    .then(data => {
                        console.log('Promotion response:', data); // Debug log
                        
                        const voucherListGroup = voucherList.querySelector('.list-group');
                        voucherListGroup.innerHTML = '';

                        if (data.success && data.promotions && data.promotions.length > 0) {
                            data.promotions.forEach(promotion => {
                                const discountType = promotion.discount_type === 'percentage' ? '%' : 'đ';
                                const discountValue = promotion.discount_type === 'percentage' 
                                    ? `${promotion.discount_value}%` 
                                    : `${new Intl.NumberFormat('vi-VN').format(promotion.discount_value)}đ`;

                                const voucherItem = document.createElement('a');
                                voucherItem.href = '#';
                                voucherItem.className = 'list-group-item list-group-item-action';
                                voucherItem.innerHTML = `
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">${promotion.code}</h6>
                                            <small class="text-muted">${promotion.description || 'Không có mô tả'}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-danger">${discountValue}</span>
                                            <small class="d-block text-muted">HSD: ${new Date(promotion.expires_at).toLocaleDateString()}</small>
                                        </div>
                                    </div>
                                `;

                                voucherItem.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    voucherInput.value = promotion.code;
                                    voucherList.style.display = 'none';
                                });

                                voucherListGroup.appendChild(voucherItem);
                            });
                        } else {
                            voucherListGroup.innerHTML = '<div class="list-group-item">Không có mã giảm giá nào khả dụng</div>';
                        }
                        
                        // Show the voucher list
                        voucherList.style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Error loading promotions:', error);
                        const voucherListGroup = voucherList.querySelector('.list-group');
                        voucherListGroup.innerHTML = '<div class="list-group-item text-danger">Có lỗi xảy ra khi tải mã giảm giá</div>';
                        voucherList.style.display = 'block';
                    });
            });

            // Hide voucher list when clicking outside
            document.addEventListener('click', function(e) {
                if (!voucherInput.contains(e.target) && !voucherList.contains(e.target)) {
                    voucherList.style.display = 'none';
                }
            });

            // Apply voucher
            applyVoucherBtn.addEventListener('click', function() {
                const voucherCode = voucherInput.value.trim();
                const selectedItems = Array.from(document.querySelectorAll('.select-item:checked')).map(cb => cb.dataset.variationId);
                
                if (selectedItems.length === 0) {
                    alert('Vui lòng chọn ít nhất một sản phẩm');
                    return;
                }

                // Nếu input trống, gọi API xóa mã giảm giá
                const endpoint = voucherCode ? '{{ route('cart.apply-voucher') }}' : '{{ route('cart.remove-voucher') }}';

                fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
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

                    if (data.success) {
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
                            const firstRow = summaryDiv.querySelector('.d-flex:first-child');
                            firstRow.insertAdjacentHTML('afterend', voucherInfoHtml);
                            document.querySelector('.applied-voucher-info').insertAdjacentHTML('afterend', discountHtml);
                        }

                        // Cập nhật Total
                        const totalElement = document.querySelector('.d-flex.justify-content-between.border-y h4:last-child');
                        if (totalElement) {
                            totalElement.textContent = formatPrice(data.totals.total);
                        }

                        // Hiển thị thông báo
                        alert(data.applied_voucher ? 'Áp dụng mã giảm giá thành công!' : 'Đã xóa mã giảm giá!');
                        
                        // Clear input nếu xóa mã giảm giá
                        if (!data.applied_voucher) {
                            voucherInput.value = '';
                        }
                    } else {
                        // Hiển thị thông báo lỗi
                        alert(data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xử lý mã giảm giá');
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
