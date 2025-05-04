@extends('admin.master')

@section('title', isset($item) ? 'Edit Inventory Receipt' : 'Create Inventory Receipt')

@section('content')
<div class="content">
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route($route.'.index') }}">Inventory Receipts</a></li>
            <li class="breadcrumb-item active">{{ isset($item) ? 'Edit' : 'Create' }}</li>
        </ol>
    </nav>

    <div class="mb-8">
        <h2 class="mb-2">{{ isset($item) ? 'Edit Inventory Receipt' : 'Create Inventory Receipt' }}</h2>
        <h5 class="text-body-tertiary fw-semibold">{{ isset($item) ? 'Edit data' : 'Create data' }}</h5>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ isset($item) ? route($route.'.update', $item->id) : route($route.'.store') }}" method="POST" enctype="multipart/form-data" id="inventoryReceiptForm">
                @csrf
                @if(isset($item))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <!-- Main receipt information -->
                    @foreach($fields as $field => $options)
                        @if(!in_array($field, ['total_amount', 'created_at', 'status']))
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label fw-bold" for="{{ $field }}">
                                {{ $options['label'] ?? ucfirst($field) }}
                            </label>
                            @if($field === 'user_id')
                                @php
                                    $users = \App\Models\User::whereIn('role_id', [1, 2])->pluck('name', 'id')->toArray();
                                    $options['options'] = $users;
                                @endphp
                            @endif
                            @switch($options['type'] ?? 'text')
                                @case('textarea')
                                    <textarea
                                        class="form-control @error($field) is-invalid @enderror"
                                        id="{{ $field }}"
                                        name="{{ $field }}"
                                        rows="4"
                                    >{{ old($field, isset($item) ? $item->$field : '') }}</textarea>
                                    @break

                                @case('select')
                                    <select
                                        class="form-select @error($field) is-invalid @enderror"
                                        id="{{ $field }}"
                                        name="{{ $field }}"
                                    >
                                        @foreach($options['options'] ?? [] as $value => $label)
                                            <option value="{{ $value }}" {{ old($field, isset($item) ? $item->$field : '') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @break

                                @case('datetime')
                                    <input
                                        type="datetime-local"
                                        class="form-control @error($field) is-invalid @enderror"
                                        id="{{ $field }}"
                                        name="{{ $field }}"
                                        value="{{ old($field, isset($item) ? $item->$field : '') }}"
                                    >
                                    @break

                                @default
                                    <input
                                        type="{{ $options['type'] ?? 'text' }}"
                                        class="form-control @error($field) is-invalid @enderror"
                                        id="{{ $field }}"
                                        name="{{ $field }}"
                                        value="{{ old($field, isset($item) ? $item->$field : '') }}"
                                        @if(isset($options['step'])) step="{{ $options['step'] }}" @endif
                                    >
                            @endswitch

                            @error($field)
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @endif
                    @endforeach
                </div>
                
                <!-- Receipt Items Section -->
                <div class="mt-5">
                    <h4 class="mb-3">Receipt Items</h4>
                    
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered" id="receiptItemsTable">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($receiptItems) && $receiptItems->count() > 0)
                                    @foreach($receiptItems as $index => $receiptItem)
                                        <tr class="item-row">
                                            <td>
                                                <select class="form-select product-select" name="items[{{ $index }}][product_variation_id]" required>
                                                    <option value="">Select Product</option>
                                                    @foreach($productVariations as $variation)
                                                        <option value="{{ $variation['id'] }}" 
                                                            data-price="{{ $variation['price'] }}"
                                                            {{ $receiptItem->product_variation_id == $variation['id'] ? 'selected' : '' }}>
                                                            {{ $variation['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control item-quantity" name="items[{{ $index }}][quantity]" min="1" value="{{ $receiptItem->quantity }}" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control item-unit-cost" name="items[{{ $index }}][unit_cost]" min="0" step="0.01" value="{{ $receiptItem->unit_cost }}" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control item-subtotal" value="{{ $receiptItem->subtotal }}" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-item">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="item-row">
                                        <td>
                                            <select class="form-select product-select" name="items[0][product_variation_id]" required>
                                                <option value="">Select Product</option>
                                                @foreach($productVariations as $variation)
                                                    <option value="{{ $variation['id'] }}" data-price="{{ $variation['price'] }}">
                                                        {{ $variation['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control item-quantity" name="items[0][quantity]" min="1" value="1" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control item-unit-cost" name="items[0][unit_cost]" min="0" step="0.01" value="0.00" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control item-subtotal" value="0.00" readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <button type="button" class="btn btn-primary btn-sm" id="addItemButton">
                                            <i class="fas fa-plus me-1"></i> Thêm sản phẩm
                                        </button>
                                    </td>
                                </tr>
                                <!-- Hidden total amount field -->
                                <input type="hidden" id="totalAmount" name="total_amount" value="{{ isset($item) ? $item->total_amount : '0.00' }}">
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <div class="me-3 bg-light p-3 rounded border">
                                <span class="fw-bold fs-7">Tổng tiền: </span>
                                <span id="displayTotalAmount" class="fs-7 fw-bold text-primary">{{ isset($item) ? number_format($item->total_amount, 2) : '0.00' }}</span>
                                <span class="fs-7 fw-bold text-primary">VND</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route($route.'.index') }}" class="btn btn-phoenix-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        {{ isset($item) ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add new item row
        document.getElementById('addItemButton').addEventListener('click', function() {
            const tbody = document.querySelector('#receiptItemsTable tbody');
            const rowCount = tbody.querySelectorAll('tr').length;
            const newRow = document.createElement('tr');
            newRow.className = 'item-row';
            
            // Generate the select options
            let options = '<option value="">Select Product</option>';
            @foreach($productVariations as $variation)
                options += `<option value="{{ $variation['id'] }}" data-price="{{ $variation['price'] }}">{{ $variation['name'] }}</option>`;
            @endforeach
            
            newRow.innerHTML = `
                <td>
                    <select class="form-select product-select" name="items[${rowCount}][product_variation_id]" required>
                        ${options}
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control item-quantity" name="items[${rowCount}][quantity]" min="1" value="1" required>
                </td>
                <td>
                    <input type="number" class="form-control item-unit-cost" name="items[${rowCount}][unit_cost]" min="0" step="0.01" value="0.00" required>
                </td>
                <td>
                    <input type="number" class="form-control item-subtotal" value="0.00" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            tbody.appendChild(newRow);
            setupEventListeners();
        });
        
        // Setup initial event listeners
        setupEventListeners();
        
        // Calculate initial totals
        calculateRowSubtotals();
        calculateTotal();
        
        function setupEventListeners() {
            // Handle product selection change
            document.querySelectorAll('.product-select').forEach(select => {
                select.addEventListener('change', function() {
                    const row = this.closest('tr');
                    const selectedOption = this.options[this.selectedIndex];
                    const price = selectedOption.getAttribute('data-price') || 0;
                    
                    // Set the unit cost to the product price
                    row.querySelector('.item-unit-cost').value = price;
                    
                    // Calculate subtotal
                    calculateRowSubtotal(row);
                    calculateTotal();
                });
            });
            
            // Handle quantity or price change
            document.querySelectorAll('.item-quantity, .item-unit-cost').forEach(input => {
                input.addEventListener('input', function() {
                    const row = this.closest('tr');
                    calculateRowSubtotal(row);
                    calculateTotal();
                });
            });
            
            // Handle remove item
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    if (document.querySelectorAll('.item-row').length > 1) {
                        row.remove();
                        updateItemIndexes();
                        calculateTotal();
                    } else {
                        // If it's the last row, just clear the selection
                        const select = row.querySelector('.product-select');
                        select.value = '';
                        row.querySelector('.item-quantity').value = 1;
                        row.querySelector('.item-unit-cost').value = 0.00;
                        row.querySelector('.item-subtotal').value = 0.00;
                        calculateTotal();
                    }
                });
            });
        }
        
        function calculateRowSubtotal(row) {
            const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
            const unitCost = parseFloat(row.querySelector('.item-unit-cost').value) || 0;
            const subtotal = quantity * unitCost;
            
            row.querySelector('.item-subtotal').value = subtotal.toFixed(2);
        }
        
        function calculateRowSubtotals() {
            document.querySelectorAll('.item-row').forEach(row => {
                calculateRowSubtotal(row);
            });
        }
        
        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.item-subtotal').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            
            document.getElementById('totalAmount').value = total.toFixed(2);
            document.getElementById('displayTotalAmount').textContent = new Intl.NumberFormat('vi-VN').format(total);
        }
        
        function updateItemIndexes() {
            document.querySelectorAll('.item-row').forEach((row, index) => {
                row.querySelector('.product-select').name = `items[${index}][product_variation_id]`;
                row.querySelector('.item-quantity').name = `items[${index}][quantity]`;
                row.querySelector('.item-unit-cost').name = `items[${index}][unit_cost]`;
            });
        }
    });
</script>
@endpush

@endsection 