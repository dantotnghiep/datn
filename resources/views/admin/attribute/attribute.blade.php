@extends('admin.layouts.master')

@section('content')
    <!-- main content -->
    <div class="cr-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="cr-page-title cr-page-title-2">
                <div class="cr-breadcrumb">
                    <h5>Attribute</h5>
                    <ul>
                        <li><a href="index.html">Carrot</a></li>
                        <li>Attribute</li>
                    </ul>
                </div>
            </div>
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
                <div class="col-xl-4 col-lg-12">
                    <div class="team-sticky-bar">
                        <div class="col-md-12">
                            <div class="cr-cat-list cr-card card-default mb-24px">
                                <div class="cr-card-content">
                                    <div class="cr-cat-form">
                                        <h3>Add New Attribute</h3>

                                        <form action="{{ route('admin.attribute.store') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Attribute Name</label>
                                                <div class="col-12">
                                                    <input id="slug" onkeyup="ChangeToSlug();" name="name"
                                                        class="form-control here slug-title" type="text">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Slug</label>
                                                <div class="col-12">
                                                    <input id="convert_slug" name="slug"
                                                        class="form-control here set-slug" type="text">
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
                <div class="col-xl-8 col-lg-12">
                    <div class="cr-cat-list cr-card card-default">
                        <div class="cr-card-content ">
                            <div class="table-responsive tbl-800">
                                <table id="cat_data_table" class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Attribute Values</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($attributes as $attribute)
                                            <tr>
                                                <td>{{ $attribute->name }}</td>
                                                <td>
                                                    @if ($attribute->values->isEmpty())
                                                        <span class="text-muted">No values available</span>
                                                    @else
                                                        <span class="cr-sub-cat-list">
                                                            @foreach ($attribute->values as $value)
                                                                <span class="cr-sub-cat-tag">{{ $value->value }}</span>
                                                            @endforeach
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="ri-settings-3-line"
                                                            style="border: none;padding: 15px 30px;font-size: 20px;background-color: white;"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false" data-display="static">

                                                        </button>

                                                        <div class="dropdown-menu">
                                                            <!-- Nút Edit -->
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.attribute.edit', $attribute->id) }}">Edit</a>

                                                            <!-- Nút Delete -->
                                                            <form
                                                                action="{{ route('admin.attribute.destroy', $attribute->id) }}"
                                                                method="POST" class="dropdown-item p-0 m-0">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa thuộc tính này?')">
                                                                    Delete
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
