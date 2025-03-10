@extends('admin.layouts.master')

@section('content')
    <div class="cr-main-content">
        <div class="container">
            <h2>Quản lý Banner</h2>
        
            <!-- Form thêm banner mới -->
            <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="image" class="form-label">Chọn ảnh banner</label>
                    <input type="file" class="form-control" name="image" required>
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">Link chuyển hướng</label>
                    <input type="text" class="form-control" name="link" required>
                </div>
                <button type="submit" class="btn btn-primary">Thêm Banner</button>
            </form>
        
            <hr>
        
            <!-- Danh sách banner hiện tại -->
            <h3>Danh sách Banner</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Link</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $banner)
                        <tr>
                            <td><img src="{{ asset($banner->image) }}" width="100"></td>
                            <td><a href="{{ url($banner->link) }}" target="_blank">{{ $banner->link }}</a></td>
                            <td>
                                <!-- Form cập nhật ảnh -->
                                <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="file" name="image" class="form-control mb-2">
                                    <button type="submit" class="btn btn-warning btn-sm">Cập nhật ảnh</button>
                                </form>
        
                                <!-- Nút xóa -->
                                <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection