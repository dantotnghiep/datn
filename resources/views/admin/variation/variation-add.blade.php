@extends('admin.layouts.master')

@section('content')
<div class="cr-main-content">
    <div class="container-fluid">
        <div class="cr-page-title">
            <h5>Thêm thông tin cho biến thể</h5>
        </div>
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

        <form action="{{ route('product-variations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
        
            @foreach ($attributes as $attributeGroup)
                @foreach ($attributeGroup as $attribute)
                    <input type="hidden" name="attributes[]" value="{{ $attribute['attribute_value_id'] }}">
                @endforeach
            @endforeach
        
            <div class="mb-3">
                <label for="sku" class="form-label">Mã sản phẩm (SKU)</label>
                <input type="text" name="sku" id="sku" class="form-control" required value="">
            </div>
        
            <div class="mb-3">
                <label for="price" class="form-label">Giá</label>
                <input type="number" name="price" id="price" class="form-control" required value="">
            </div>
            <div class="mb-3">
                <label for="sale_price" class="form-label">Giá khuyến mãi</label>
                <input type="number" name="sale_price" id="sale_price" class="form-control" value="">
            </div>
            <div class="mb-3">
                <label for="sale_start" class="form-label">Ngày bắt đầu khuyến mãi</label>
                <input type="date" name="sale_start" id="sale_start" class="form-control" value="">
            </div>

            <div class="mb-3">
                <label for="sale_end" class="form-label">Ngày kết thúc khuyến mãi</label>
                <input type="date" name="sale_end" id="sale_end" class="form-control" value="">
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Số lượng trong kho</label>
                <input type="number" name="stock" id="stock" class="form-control" required value="">
            </div>
        
            <div class="mb-3">
                <label for="image" class="form-label">Hình ảnh</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>
        
            <button type="submit" class="btn btn-primary">Lưu biến thể</button>
        </form>
    </div>
</div>
@endsection
