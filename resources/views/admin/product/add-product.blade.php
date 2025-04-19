@extends('admin.layouts.master')

@section('content')
    <div class="cr-main-content">
        <div class="container-fluid">
            <div class="cr-page-title cr-page-title-2">
                <div class="cr-breadcrumb">
                    <h5>Thêm sản phẩm</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Bảng điều khiển</a></li>
                        <li>Thêm sản phẩm</li>
                    </ul>
                </div>
            </div>

            @if($errors->any())
                <div style="background-color: #000; color: #fff; padding: 15px; margin-bottom: 20px;">
                    <p>Gỡ lỗi: Tìm thấy {{ count($errors->all()) }} lỗi</p>
                    @foreach($errors->all() as $error)
                        <p>- {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if ($errors->any())
                <div id="error-anchor" style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
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

            <div class="row">
                <div class="col-md-12">
                    <div class="cr-card card-default">
                        <div class="cr-card-content">
                            <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3 mt-4">
                                    <div class="col-md-6">
                                        <label for="name">Tên sản phẩm</label>
                                        <input type="text" name="name" class="form-control" id="slug"
                                            onkeyup="ChangeToSlug();" required value="{{ old('name') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="slug">Đường dẫn (Slug)</label>
                                        <input type="text" name="slug" class="form-control" id="convert_slug"
                                            required value="{{ old('slug') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="category_id">Danh mục</label>
                                        <select name="category_id" id="category_id" class="form-control" required>
                                            <option value="">-- Chọn danh mục --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="status">Trạng thái</label>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Kích hoạt</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="description">Mô tả</label>
                                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                    </div>

                                    <hr class="my-4">
                                    <h4>Hình ảnh</h4>

                                    <div class="col-md-6">
                                        <label for="main_image">Hình ảnh chính</label>
                                        <input type="file" name="main_image" class="form-control" accept="image/*" required>
                                        <div class="mt-2" id="main_image_preview"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="additional_images">Hình ảnh phụ</label>
                                        <input type="file" name="additional_images[]" class="form-control" accept="image/*" multiple>
                                        <div class="mt-2" id="additional_images_preview"></div>
                                    </div>

                                    <hr class="my-4">
                                    <h4>Thuộc tính</h4>

                                    @foreach ($attributes as $attribute)
                                        <div class="col-md-6">
                                            <label>{{ $attribute->name }}</label>
                                            <select name="attributes[{{ $attribute->id }}][]" class="form-control attribute-select"
                                                data-attribute-id="{{ $attribute->id }}" multiple>
                                                @foreach ($attribute->values as $value)
                                                    <option value="{{ $value->id }}"
                                                        data-value-name="{{ $value->value }}">{{ $value->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach

                                    <hr class="my-4">
                                    <h4>Biến thể</h4>
                                    <div class="col-12">
                                        <button type="button" class="btn btn-secondary mb-3" id="generate-variations">Tạo biến thể</button>
                                        <div id="variations-container">
                                            <!-- Các biến thể sẽ được thêm vào đây -->
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
