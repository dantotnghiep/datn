@extends('admin.layouts.master')

@section('content')
<div class="cr-main-content">
    <div class="container-fluid">
        <div class="cr-page-title cr-page-title-2">
            <div class="cr-breadcrumb">
                <h5>Edit Product</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li>Edit Product</li>
                </ul>
            </div>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Thông tin sản phẩm -->
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" id="name" required>
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="form-control" id="slug" required>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" class="form-control" id="price" required>
            </div>

            <div class="form-group">
                <label for="sale_price">Sale Price</label>
                <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="form-control" id="sale_price">
            </div>

            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}" class="form-control" id="quantity" required>
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">-- Select Category --</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" required>{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="sale_start">Sale Start</label>
                        <input type="datetime-local" name="sale_start" value="{{ old('sale_start', optional($product->sale_start)->format('Y-m-d\TH:i')) }}" class="form-control" id="sale_start">
                    </div>
                    <div class="col-md-6">
                        <label for="sale_end">Sale End</label>
                        <input type="datetime-local" name="sale_end" value="{{ old('sale_end', optional($product->sale_end)->format('Y-m-d\TH:i')) }}" class="form-control" id="sale_end">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Hình ảnh -->
            <div class="form-group">
                <label for="main_image">Main Image</label>
                <input type="file" name="main_image" id="main_image" class="form-control">
                @if ($product->main_image)
                <img src="{{ asset('storage/' . $product->main_image) }}" alt="Main Image" class="img-thumbnail mt-2" style="width: 150px;">
                @endif
            </div>

            <div class="form-group">
                <label for="additional_images">Additional Images</label>
                <input type="file" name="additional_images[]" id="additional_images" class="form-control" multiple>
                @foreach ($product->additionalImages as $image)
                <img src="{{ asset('storage/' . $image->url) }}" alt="Additional Image" class="img-thumbnail mt-2" style="width: 100px;">
                @endforeach
            </div>

            <!-- Thuộc tính -->
            <div class="mt-4">
                <h4>Attributes</h4>
                <div id="attributes">
                    @forelse ($productAttributes as $index => $productAttribute)
                        <div class="attribute-item mt-3" id="attribute-item-{{ $index }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="attribute_{{ $index }}">Choose Attribute</label>
                                    <select name="attributes[{{ $index }}][attribute_id]"
                                        class="form-control select-attribute" data-index="{{ $index }}"
                                        id="attribute_{{ $index }}" required>
                                        @foreach ($attributes as $attribute)
                                            <option value="{{ $attribute->id }}"
                                                {{ $attribute->id == $productAttribute->attribute_id ? 'selected' : '' }}>
                                                {{ $attribute->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 attribute-values-container"
                                    id="attribute-values-container-{{ $index }}">
                                    @if ($productAttribute->attributeValues->isNotEmpty())
                                        <label>Attribute Values:</label>
                                        <div>
                                            @foreach ($productAttribute->attributeValues as $value)
                                                <div class="attribute-value-item d-flex align-items-center">
                                                    <input type="hidden" name="attributes[{{ $index }}][value_ids][]"
                                                        value="{{ $value->id }}">
                                                    <span class="mr-2">{{ $value->value }}</span>
                                                    <button type="button" class="btn btn-sm btn-danger remove-value"
                                                        data-value-id="{{ $value->id }}" data-index="{{ $index }}">X</button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span>No attribute values available.</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No attributes yet. Add one below:</p>
                    @endforelse
                </div>
                <button type="button" id="add-attribute" class="btn btn-secondary mt-3">Add Attribute</button>
            </div>
            
            <div class="col-md-12 mt-4">
                <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
            
            <script>

const attributes = @json($attributes); // Dữ liệu từ backend
let attributeIndex = 0; // Bắt đầu từ 0 để thêm mới các attribute

// Tạo danh sách các attribute đã được hiển thị
const existingAttributeIds = new Set(); // Sử dụng Set để đảm bảo không bị trùng lặp

// Hàm hiển thị các attribute sẵn có
function renderExistingAttributes(productAttributes) {
    productAttributes.forEach((productAttribute) => {
        if (!existingAttributeIds.has(productAttribute.attribute_id)) {
            addAttributeToDOM(productAttribute.attribute_id, productAttribute.values);
            existingAttributeIds.add(productAttribute.attribute_id);
        }
    });
}

// Hàm thêm attribute vào DOM
function addAttributeToDOM(attributeId, attributeValues = []) {
    const selectedAttribute = attributes.find(attr => attr.id == attributeId);

    if (!selectedAttribute) {
        return;
    }

    const attributeHtml = `
        <div class="attribute-item mt-3" id="attribute-item-${attributeIndex}">
            <div class="row">
                <div class="col-md-6">
                    <label for="attribute_${attributeIndex}">Choose Attribute</label>
                    <select name="attributes[${attributeIndex}][attribute_id]" class="form-control select-attribute" data-index="${attributeIndex}" id="attribute_${attributeIndex}" disabled>
                        <option value="${selectedAttribute.id}">${selectedAttribute.name}</option>
                    </select>
                </div>
                <div class="col-md-6 attribute-values-container" id="attribute-values-container-${attributeIndex}">
                    ${attributeValues.map(value => `
                        <div class="attribute-value-item d-flex align-items-center">
                            <input type="hidden" name="attributes[${attributeIndex}][value_ids][]" value="${value.id}">
                            <span class="mr-2">${value.value}</span>
                            <button type="button" class="btn btn-sm btn-danger remove-value" data-value-id="${value.id}" data-index="${attributeIndex}">X</button>
                        </div>
                    `).join('')}
                </div>
            </div>
        </div>`;
    document.getElementById('attributes').insertAdjacentHTML('beforeend', attributeHtml);
    attributeIndex++;
}

// Thêm mới một attribute
document.getElementById('add-attribute').addEventListener('click', function () {
    const attributeHtml = `
        <div class="attribute-item mt-3" id="attribute-item-${attributeIndex}">
            <div class="row">
                <div class="col-md-6">
                    <label for="attribute_${attributeIndex}">Choose Attribute</label>
                    <select name="attributes[${attributeIndex}][attribute_id]" class="form-control select-attribute" data-index="${attributeIndex}" id="attribute_${attributeIndex}" required>
                        <option value="">-- Select Attribute --</option>
                        ${attributes.filter(attr => !existingAttributeIds.has(attr.id))
                            .map(attr => `<option value="${attr.id}">${attr.name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-6 attribute-values-container" id="attribute-values-container-${attributeIndex}">
                </div>
            </div>
        </div>`;
    document.getElementById('attributes').insertAdjacentHTML('beforeend', attributeHtml);
    attributeIndex++;
});

// Xử lý khi chọn một attribute mới
document.getElementById('attributes').addEventListener('change', function (e) {
    if (e.target.classList.contains('select-attribute')) {
        const index = e.target.getAttribute('data-index');
        const container = document.getElementById(`attribute-values-container-${index}`);
        const attributeId = e.target.value;

        // Kiểm tra nếu thuộc tính đã tồn tại
        if (existingAttributeIds.has(parseInt(attributeId))) {
            alert('This attribute is already added.');
            e.target.value = ''; // Reset select field
            container.innerHTML = '';
            return;
        }

        existingAttributeIds.add(parseInt(attributeId));
        container.innerHTML = ''; // Clear container trước khi thêm mới

        const selectedAttribute = attributes.find(attr => attr.id == attributeId);

        if (selectedAttribute) {
            if (selectedAttribute.values && selectedAttribute.values.length > 0) {
                const valueList = selectedAttribute.values.map(value => `
                    <div class="attribute-value-item d-flex align-items-center">
                        <input type="hidden" name="attributes[${index}][value_ids][]" value="${value.id}">
                        <span class="mr-2">${value.value}</span>
                        <button type="button" class="btn btn-sm btn-danger remove-value" data-value-id="${value.id}" data-index="${index}">X</button>
                    </div>
                `).join('');
                container.innerHTML = `<label>Attribute Values:</label><div>${valueList}</div>`;
            } else {
                container.innerHTML = `<label>Attribute Values:</label><div><span>No attribute values available.</span></div>`;
            }
        }
    }
});

// Xóa một attribute value
document.getElementById('attributes').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-value')) {
        const valueItem = e.target.closest('.attribute-value-item');
        valueItem.remove();
    }
});

// Khởi chạy khi load trang (hiển thị các attribute sẵn có)
renderExistingAttributes(@json($productAttributes));
            </script>
            
@endsection
