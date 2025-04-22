@extends('admin.layouts.master')

@section('content')
<div class="cr-main-content">
    <div class="container-fluid">
        <div class="cr-page-title cr-page-title-2">
            <div class="cr-breadcrumb">
                <h5>Thêm mã giảm giá</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Carrot</a></li>
                    <li><a href="{{ route('admin.discounts.index') }}">Danh sách mã giảm giá</a></li>
                    <li>Thêm mới</li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="cr-card card-default">
                    <div class="cr-card-content">
                        <form action="{{ route('admin.discounts.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="code">Mã giảm giá</label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                               id="code" name="code" value="{{ old('code') }}" required>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="type">Loại giảm giá</label>
                                        <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Theo phần trăm</option>
                                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="sale">Giá trị giảm</label>
                                        <input type="number" step="0.01" class="form-control @error('sale') is-invalid @enderror"
                                               id="sale" name="sale" value="{{ old('sale') }}" required>
                                        <small class="form-text text-muted">Nhập % nếu giảm theo phần trăm, hoặc số tiền nếu giảm cố định</small>
                                        @error('sale')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="startDate">Ngày bắt đầu</label>
                                        <input type="datetime-local" class="form-control @error('startDate') is-invalid @enderror"
                                               id="startDate" name="startDate" value="{{ old('startDate') }}" required>
                                        @error('startDate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="endDate">Ngày kết thúc</label>
                                        <input type="datetime-local" class="form-control @error('endDate') is-invalid @enderror"
                                               id="endDate" name="endDate" value="{{ old('endDate') }}" required>
                                        @error('endDate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="maxUsage">Giới hạn sử dụng</label>
                                        <input type="number" class="form-control @error('maxUsage') is-invalid @enderror"
                                               id="maxUsage" name="maxUsage" value="{{ old('maxUsage') }}">
                                        <small class="form-text text-muted">Để trống nếu không giới hạn</small>
                                        @error('maxUsage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="user_limit">Giới hạn sử dụng mỗi người dùng</label>
                                        <input type="number" class="form-control @error('user_limit') is-invalid @enderror"
                                               id="user_limit" name="user_limit" value="{{ old('user_limit') }}">
                                        <small class="form-text text-muted">Để trống nếu không giới hạn</small>
                                        @error('user_limit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="is_public">Trạng thái công khai</label>
                                        <select class="form-control @error('is_public') is-invalid @enderror" id="is_public" name="is_public" required>
                                            <option value="1" {{ old('is_public', '1') == '1' ? 'selected' : '' }}>Công khai</option>
                                            <option value="0" {{ old('is_public') == '0' ? 'selected' : '' }}>Riêng tư</option>
                                        </select>
                                        @error('is_public')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="minOrderValue">Giá trị đơn hàng tối thiểu (VNĐ)</label>
                                        <input type="number" class="form-control @error('minOrderValue') is-invalid @enderror"
                                               id="minOrderValue" name="minOrderValue" value="{{ old('minOrderValue') }}">
                                        @error('minOrderValue')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="maxDiscount">Giảm giá tối đa (VNĐ)</label>
                                        <input type="number" class="form-control @error('maxDiscount') is-invalid @enderror"
                                               id="maxDiscount" name="maxDiscount" value="{{ old('maxDiscount') }}">
                                        @error('maxDiscount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="status">Trạng thái</label>
                                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="applicable_products">Sản phẩm áp dụng</label>
                                        <select class="form-control @error('applicable_products') is-invalid @enderror" 
                                                id="applicable_products" name="applicable_products[]" multiple>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Để trống nếu áp dụng cho tất cả sản phẩm</small>
                                        @error('applicable_products')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="applicable_categories">Danh mục áp dụng</label>
                                        <select class="form-control @error('applicable_categories') is-invalid @enderror" 
                                                id="applicable_categories" name="applicable_categories[]" multiple>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Để trống nếu áp dụng cho tất cả danh mục</small>
                                        @error('applicable_categories')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group text-end">
                                <button type="submit" class="btn btn-primary">Tạo mã giảm giá</button>
                                <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize select2 for multiple select
    $('#applicable_products, #applicable_categories').select2({
        placeholder: 'Chọn...',
        allowClear: true
    });
});
</script>
@endsection