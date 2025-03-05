@extends('admin.layouts.master')

@section('content')
    <!-- main content -->
    <div class="cr-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="cr-page-title cr-page-title-2">
                <div class="cr-breadcrumb">
                    <h5>Attribute Values</h5>
                    <ul>
                        <li><a href="index.html">Carrot</a></li>
                        <li>Attribute Values</li>
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
                <!-- Form thêm Attribute Value -->
                <div class="col-xl-4 col-lg-12">
                    <div class="team-sticky-bar">
                        <div class="col-md-12">
                            <div class="cr-cat-list cr-card card-default mb-24px">
                                <div class="cr-card-content">
                                    <div class="cr-cat-form">
                                        <h3>Add Attribute Value</h3>
                                        <form action="{{ route('admin.attribute-values.store') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Attribute</label>
                                                <div class="col-12">
                                                    <select name="attribute_id" class="form-control form-select" required>
                                                        <option value="">Select Attribute</option>
                                                        @foreach ($attributes as $attribute)
                                                            <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Attribute Value</label>
                                                <div class="col-12">
                                                    <input name="value" id="slug" onkeyup="ChangeToSlug();" class="form-control here slug-title" type="text" placeholder="Enter Attribute Value" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Slug (Optional)</label>
                                                <div class="col-12">
                                                    <input id="convert_slug" name="slug" class="form-control here set-slug" type="text" placeholder="Enter Slug (Optional)">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12 d-flex">
                                                    <button type="submit" class="cr-btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách Attribute Values -->
                <div class="col-xl-8 col-lg-12">
                    <div class="cr-cat-list cr-card card-default">
                        <div class="cr-card-content">
                            <div class="table-responsive tbl-800">
                                <table id="cat_data_table" class="table">
                                    <thead>
                                        <tr>
                                            <th>Attribute Value</th>
                                            <th>Attribute</th>
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
                            <!-- Hiển thị thông báo nếu không có dữ liệu -->
                            @if ($attributeValues->isEmpty())
                                <div class="alert alert-info mt-3">
                                    No Attribute Values found.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
