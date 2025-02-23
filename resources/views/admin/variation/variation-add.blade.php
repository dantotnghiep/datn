@extends('admin.layouts.master')

@section('content')
<div class="cr-main-content">
    <div class="container-fluid">
        <div class="cr-page-title">
            <h5>Add Information for Variation</h5>
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
                <label for="sku" class="form-label">SKU</label>
                <input type="text" name="sku" id="sku" class="form-control" required value="">
            </div>
        
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" name="price" id="price" class="form-control" required value="">
            </div>
            <div class="mb-3">
                <label for="sale_price" class="form-label">Sale Price</label>
                <input type="number" name="sale_price" id="sale_price" class="form-control" value="">
            </div>
            <div class="mb-3">
                <label for="sale_start" class="form-label">Sale Start</label>
                <input type="date" name="sale_start" id="sale_start" class="form-control" value="">
            </div>

            <div class="mb-3">
                <label for="sale_end" class="form-label">Sale End</label>
                <input type="date" name="sale_end" id="sale_end" class="form-control" value="">
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" name="stock" id="stock" class="form-control" required value="">
            </div>
        
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>
        
            <button type="submit" class="btn btn-primary">Save Variation</button>
        </form>
    </div>
</div>
@endsection
