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
                                <table id="product_list" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Sale Price</th>
                                            <th>Sale End</th>
                                            <th>Status</th>
                                            <th>Variation Of Product</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $product)
                                            <tr>
                                                <td>
                                                    @if ($product->mainImage)
                                                        <img class="tbl-thumb"
                                                            src="{{ asset('storage/' . $product->mainImage->url) }}"
                                                            alt="{{ $product->name }}">
                                                    @else
                                                        <img class="tbl-thumb" src="/be/assets/img/product/default.jpg"
                                                            alt="No Image Available">
                                                    @endif
                                                </td>
                                                <td>{{ $product->name }}</td>
                                                <td>${{ $product->price }}</td>
                                                <td>
                                                    @if ($product->sale_price)
                                                        ${{ $product->sale_price }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $product->sale_end ? \Carbon\Carbon::parse($product->sale_end)->format('Y-m-d') : 'No End Date' }}

                                                </td>
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
                                                        <button type="button"
                                                            class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false" data-display="static">
                                                            <span class="sr-only"><i class="ri-settings-3-line"></i></span>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="{{ route('admin.product.edit', $product->id) }}">Edit</a>
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
                                                <td colspan="7" class="text-center">No products available</td>
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
