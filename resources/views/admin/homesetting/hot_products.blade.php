@extends('admin.layouts.master')

@section('content')
    <div class="cr-main-content">
        <h3>Quản lý Sản phẩm Hot 🔥</h3>

        <!-- Form thêm sản phẩm hot -->
        <form action="{{ route('hot-products.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Chọn sản phẩm:</label>
                <select name="product_id" class="form-control">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Thêm sản phẩm hot</button>
            
        </form>

        <!-- Danh sách sản phẩm hot -->
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên sản phẩm</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hotProducts as $hotProduct)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $hotProduct->product->name }}</td>
                        <td>
                            <form action="{{ route('hot-products.destroy', $hotProduct->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
