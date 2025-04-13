@extends('admin.layouts.master')

@section('content')
<div class="cr-main-content">
    <div class="container-fluid">
        <!-- Tiêu đề trang & breadcrumb -->
        <div class="cr-page-title cr-page-title-2">
            <div class="cr-breadcrumb">
                <h5>Danh mục</h5>
                <ul>
                    <li><a href="index.html">Carrot</a></li>
                    <li>Danh mục</li>
                </ul>
            </div>
        </div>
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if ($errors->any())
        <div style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            @foreach ($errors->all() as $error)
            <div style="margin-bottom: 5px;">{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <div class="row cr-category">
            <div class="col-xl-4 col-lg-12">
                <div class="team-sticky-bar">
                    <div class="col-md-12">
                        <div class="cr-cat-list cr-card card-default mb-24px">
                            <div class="cr-card-content">
                                <div class="cr-cat-form">
                                    <h3>Chỉnh sửa danh mục</h3>

                                    <form method="POST" action="{{ route('admin.category.update',$category->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label>Tên</label>
                                            <div class="col-12">
                                                <input id="name" name="name"
                                                    class="form-control here slug-title" type="text" value="{{ old('name',$category->name) }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Slug</label>
                                            <div class="col-12">
                                                <input id="slug" name="slug" class="form-control here set-slug"
                                                    type="text" value="{{ old('slug', $category->slug) }}">
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label>Mô tả đầy đủ</label>
                                            <div class="col-12">
                                                <textarea id="description" name="description" cols="40"
                                                    rows="4" class="form-control">{{ old('description', $category->description) }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="status">Trạng thái</label>
                                            <div class="col-12">
                                                <select id="status" name="status" class="form-control">
                                                    <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                                    <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 d-flex">
                                                <button type="submit" class="cr-btn-primary">Cập nhật</button>
                                            </div>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-12">
                <div class="cr-cat-list cr-card card-default">
                    <div class="cr-card-content ">
                        <div class="table-responsive tbl-800">
                            <table id="cat_data_table" class="table">
                                <thead>
                                    <tr>
                                        <th>Tên</th>
                                        <th>Mô tả</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($categories as $category )
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            <span class="cr-sub-cat-list">
                                                {{ $category->description }}
                                            </span>
                                        </td>

                                        <td class="{{ $category->status === 'active' ? 'active' : 'inactive' }}">{{ ucfirst($category->status) }}</td>

                                        <td>
                                            <div>
                                                <button type="button"
                                                    class="ri-settings-3-line"
                                                    style="border: none;padding: 15px 30px;font-size: 20px;background-color: white;"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false" data-display="static">
                                                    
                                                </button>

                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('admin.category.edit', $category->id) }}">Chỉnh sửa</a>
                                                    <form method="POST" action="{{ route('admin.category.destroy', $category->id) }}" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này không?')">
                                                            Xóa
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
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
