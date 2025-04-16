@extends('admin.layouts.master')

@section('content')
<div class="cr-main-content">
    <div class="container-fluid">
        <div class="cr-page-title cr-page-title-2">
            <div class="cr-breadcrumb">
                <h5>Chỉnh sửa sản phẩm</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Bảng điều khiển</a></li>
                    <li>Chỉnh sửa sản phẩm</li>
                </ul>
            </div>
        </div>

        @if ($errors->any())
        <div style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            @foreach ($errors->all() as $error)
            <div style="margin-bottom: 5px;">{{ $error }}</div>
            @endforeach
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

            <div class="form-group">
                <label for="name">Tên sản phẩm</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" id="slug" onkeyup="ChangeToSlug();" required>
            </div>

            <div class="form-group">
                <label for="slug">Đường dẫn</label>
                <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="form-control" id="convert_slug" required>
            </div>

            <div class="form-group">
                <label for="price">Giá</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" class="form-control" id="price" required>
            </div>

            <div class="form-group">
                <label for="sale_price">Giá khuyến mãi</label>
                <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="form-control" id="sale_price">
            </div>

            <div class="form-group">
                <label for="quantity">Số lượng</label>
                <input type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}" class="form-control" id="quantity" required>
            </div>

            <div class="form-group">
                <label for="category_id">Danh mục</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea name="description" id="description" class="form-control" required>{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="sale_start">Bắt đầu khuyến mãi</label>
                        <input type="datetime-local" name="sale_start" value="{{ old('sale_start', optional($product->sale_start)->format('Y-m-d\TH:i')) }}" class="form-control" id="sale_start">
                    </div>
                    <div class="col-md-6">
                        <label for="sale_end">Kết thúc khuyến mãi</label>
                        <input type="datetime-local" name="sale_end" value="{{ old('sale_end', optional($product->sale_end)->format('Y-m-d\TH:i')) }}" class="form-control" id="sale_end">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="status">Trạng thái</label>
                <select name="status" id="status" class="form-control">
                    <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Kích hoạt</option>
                    <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

            <div class="form-group">
                <label for="main_image">Hình ảnh chính</label>
                <input type="file" name="main_image" id="main_image" class="form-control">
                @if ($product->main_image)
                <img src="{{ asset('storage/' . $product->main_image) }}" alt="Main Image" class="img-thumbnail mt-2" style="width: 150px;">
                @endif
            </div>

            <div class="form-group">
                <label for="additional_images">Hình ảnh bổ sung</label>
                <input type="file" name="additional_images[]" id="additional_images" class="form-control" multiple>
                @foreach ($product->additionalImages as $image)
                <img src="{{ asset('storage/' . $image->url) }}" alt="Additional Image" class="img-thumbnail mt-2" style="width: 100px;">
                @endforeach
            </div>

            <div class="mt-4">
                <h4>Thuộc tính</h4>
                <div id="attributes">
                    @forelse ($productAttributes as $index => $productAttribute)
                        <div class="attribute-item mt-3" id="attribute-item-{{ $index }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="attribute_{{ $index }}">Chọn thuộc tính</label>
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
                                        <label>Giá trị thuộc tính:</label>
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
                                        <span>Không có giá trị thuộc tính nào.</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>Chưa có thuộc tính nào. Thêm ở bên dưới:</p>
                    @endforelse
                </div>
                <button type="button" id="add-attribute" class="btn btn-secondary mt-3">Thêm thuộc tính</button>
            </div>

            <div class="col-md-12 mt-4">
                <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
            </div>

            <!-- Script phần JavaScript giữ nguyên (không cần dịch) -->

        </form>
    </div>
</div>
@endsection
