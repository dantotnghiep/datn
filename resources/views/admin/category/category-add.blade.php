
@extends('admin.layouts.master')

@section('content')
<div class="cr-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="cr-page-title cr-page-title-2">
            <div class="cr-breadcrumb">
                <h5>Category</h5>
                <ul>
                    <li><a href="index.html">Carrot</a></li>
                    <li>Category</li>
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
                                    <h3>Add New Category</h3>

                                    <form method="POST" action="{{ route('admin.category.store') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label>Name</label>
                                            <div class="col-12">
                                                <input id="slug" onkeyup="ChangeToSlug();" name="name"
                                                    class="form-control here slug-title" type="text value="{{ old('name') }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Slug</label>
                                            <div class="col-12">
                                                <input id="convert_slug" name="slug" class="form-control here set-slug"
                                                    type="text">
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label>Full Description</label>
                                            <div class="col-12">
                                                <textarea id="description" name="description" cols="40"
                                                    rows="4" class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <div class="col-12">
                                                <select id="status" name="status" class="form-control">
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
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
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Actions</th>
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
                                                    <a class="dropdown-item" href="{{ route('admin.category.edit', $category->id) }}">Edit</a>
                                                    <form method="POST" action="{{ route('admin.category.destroy', $category->id) }}" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this category?')">
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
