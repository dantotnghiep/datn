@extends('admin.layouts.master')

@section('content')
    <!-- Nội dung chính -->
    <div class="cr-main-content">
        <div class="container-fluid">
            <!-- Tiêu đề trang & breadcrumb -->
            <div class="cr-page-title cr-page-title-2">
                <div class="cr-breadcrumb">
                    <h5>Giá trị thuộc tính</h5>
                    <ul>
                        <li><a href="index.html">Carrot</a></li>
                        <li>Giá trị thuộc tính</li>
                    </ul>
                </div>
            </div>

            <!-- Hiển thị thông báo -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row cr-category">
                <!-- Form thêm giá trị thuộc tính -->
                <div class="col-xl-4 col-lg-12">
                    <div class="team-sticky-bar">
                        <div class="col-md-12">
                            <div class="cr-cat-list cr-card card-default mb-24px">
                                <div class="cr-card-content">
                                    <div class="cr-cat-form">
                                        <h3>Thêm giá trị thuộc tính</h3>
                                        <form action="{{ route('admin.attribute-values.store') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Thuộc tính</label>
                                                <div class="col-12">
                                                    <select name="attribute_id" class="form-control form-select" required>
                                                        <option value="">Chọn thuộc tính</option>
                                                        @foreach ($attributes as $attribute)
                                                            <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Giá trị thuộc tính</label>
                                                <div class="col-12">
                                                    <input name="value" id="slug" onkeyup="ChangeToSlug();" class="form-control here slug-title" type="text" placeholder="Nhập giá trị thuộc tính" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Slug (Không bắt buộc)</label>
                                                <div class="col-12">
                                                    <input id="convert_slug" name="slug" class="form-control here set-slug" type="text" placeholder="Nhập slug nếu có (Không bắt buộc)">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12 d-flex">
                                                    <button type="submit" class="cr-btn-primary">Thêm mới</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách giá trị thuộc tính -->
                <div class="col-xl-8 col-lg-12">
                    <div class="cr-cat-list cr-card card-default">
                        <div class="cr-card-content">
                            <div class="table-responsive tbl-800">
                                <table id="cat_data_table" class="table">
                                    <thead>
                                        <tr>
                                            <th>Giá trị thuộc tính</th>
                                            <th>Thuộc tính</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($attributeValues as $value)
                                            <tr>
                                                <td>{{ $value->value }}</td>
                                                <td>{{ $value->attribute->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Hiển thị nếu không có dữ liệu -->
                            @if ($attributeValues->isEmpty())
                                <div class="alert alert-info mt-3">
                                    Không có giá trị thuộc tính nào.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
