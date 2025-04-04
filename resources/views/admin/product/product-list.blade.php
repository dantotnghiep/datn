@extends('admin.layouts.master')

@section('content')
    <div class="cr-main-content">
        <div class="container-fluid">

            <div class="cr-page-title cr-page-title-2">
                <div class="cr-breadcrumb">
                    <h5>Product List</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Carrot</a></li>
                        <li>Product List</li>
                    </ul>
                </div>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="cr-card card-default product-list">
                        <div class="cr-card-content">
                            <div class="table-responsive">
                                <table id="product_list" class="table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Price Range</th>
                                            <th>Total Stock</th>
                                            <th>Status</th>
                                            <th>Variation Of Product</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $product)
                                            <tr>
                                                <td>
                                                    @php
                                                        $mainImage = $product->images()->where('is_main', 1)->first();
                                                    @endphp
                                                    <img class="custom-img"
                                                        src="{{ $mainImage ? $mainImage->url : 'default-image.jpg' }}"
                                                        alt="{{ $product->product_name }}">
                                                </td>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ Str::limit($product->description, 50) }}</td>
                                                <td>
                                                    @php
                                                        $minPrice = $product->variations->min('price');
                                                        $maxPrice = $product->variations->max('price');
                                                    @endphp
                                                    {{ number_format($minPrice, 0, ',', '.') }} VNĐ -
                                                    {{ number_format($maxPrice, 0, ',', '.') }} VNĐ
                                                </td>
                                                <td>{{ $product->variations->sum('stock') }}</td>
                                                <td>
                                                    <span
                                                        class="{{ $product->status == 'active' ? 'active' : 'inactive' }}">
                                                        {{ ucfirst($product->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('product.variations', $product->id) }}">Variation Of
                                                        Product</a>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <button type="button" class="ri-settings-3-line"
                                                            style="border: none;padding: 15px 30px;font-size: 20px;"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false" data-display="static">

                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.product.edit', $product->id) }}">Edit</a>
                                                            <form action="{{ route('products.destroy', $product->id) }}"
                                                                method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="dropdown-item" type="submit"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">Delete</button>
                                                            </form>
                                                        </div>

                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No products available</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
