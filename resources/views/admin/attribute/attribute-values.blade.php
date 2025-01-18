@extends('admin.layouts.master')

@section('content')
    <!-- main content -->
    <div class="cr-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="cr-page-title cr-page-title-2">
                <div class="cr-breadcrumb">
                    <h5>Sub Category</h5>
                    <ul>
                        <li><a href="index.html">Carrot</a></li>
                        <li>Sub Category</li>
                    </ul>
                </div>
            </div>
            <div class="row cr-category">
                <div class="col-xl-4 col-lg-12">
                    <div class="team-sticky-bar">
                        <div class="col-md-12">
                            <div class="cr-cat-list cr-card card-default mb-24px">
                                <div class="cr-card-content">
                                    <div class="cr-cat-form">
                                        <h3>Add Sub Category</h3>
                                        <form>
                                            <div class="form-group">
                                                <label>Name</label>
                                                <div class="col-12">
                                                    <select class="form-control form-select">
                                                        <option>Select Main Category</option>
                                                        <option>Clothes</option>
                                                        <option>Jewellery</option>
                                                        <option>Furniture</option>
                                                        <option>Perfume</option>
                                                        <option>Footwear</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Name</label>
                                                <div class="col-12">
                                                    <input id="text" name="text"
                                                        class="form-control here slug-title" type="text">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Slug</label>
                                                <div class="col-12">
                                                    <input id="slug" name="slug" class="form-control here set-slug"
                                                        type="text">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label>Sort Description</label>
                                                <div class="col-12">
                                                    <textarea id="sortdescription" name="sortdescription" cols="40" rows="2" class="form-control"></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label>Full Description</label>
                                                <div class="col-12">
                                                    <textarea id="fulldescription" name="fulldescription" cols="40" rows="4" class="form-control"></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label>Product Tags <span>( Type and
                                                        make comma to separate tags )</span></label>
                                                <div class="col-12">
                                                    <input type="text" class="form-control" id="group_tag"
                                                        name="group_tag" value="" placeholder=""
                                                        data-role="tagsinput">
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
                                            <th>Main Categories</th>
                                            <th>Product</th>
                                            <th>Status</th>
                                            <th>Trending</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>T-shirt</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-tag">Clothes</span>
                                                </span>
                                            </td>
                                            <td>28</td>
                                            <td class="active">ACTIVE</td>
                                            <td><span class="badge badge-success">Top</span></td>
                                            <td>
                                                <div>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                        data-display="static">
                                                        <span class="sr-only"><i class="ri-settings-3-line"></i></span>
                                                    </button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Sport shoes</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-tag">Footwear</span>
                                                </span>
                                            </td>
                                            <td>68</td>
                                            <td class="active">ACTIVE</td>
                                            <td><span class="badge bg-primary">Medium</span></td>
                                            <td>
                                                <div>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                        data-display="static">
                                                        <span class="sr-only"><i class="ri-settings-3-line"></i></span>
                                                    </button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Gold ring</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-tag">Jewelry</span>
                                                </span>
                                            </td>
                                            <td>68</td>
                                            <td><span class="disable">Inactive</span></td>
                                            <td><span class="badge badge-success">Top</span></td>
                                            <td>
                                                <div>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i class="ri-settings-3-line"></i></span>
                                                    </button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Fogy deo</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-tag">Perfume</span>
                                                </span>
                                            </td>
                                            <td>38</td>
                                            <td class="active">ACTIVE</td>
                                            <td><span class="badge bg-primary">Medium</span></td>
                                            <td>
                                                <div>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i class="ri-settings-3-line"></i></span>
                                                    </button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Facewash</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-tag">Cosmetics</span>
                                                </span>
                                            </td>
                                            <td>18</td>
                                            <td class="active">ACTIVE</td>
                                            <td><span class="badge bg-danger">Low</span></td>
                                            <td>
                                                <div>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i class="ri-settings-3-line"></i></span>
                                                    </button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Sun Glasses</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-tag">Glasses</span>
                                                </span>
                                            </td>
                                            <td>82</td>
                                            <td><span class="inactive">Inactive</span></td>
                                            <td><span class="badge bg-primary">Medium</span></td>
                                            <td>
                                                <div>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i class="ri-settings-3-line"></i></span>
                                                    </button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Hand Bags</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-tag">Bags</span>
                                                </span>
                                            </td>
                                            <td>18</td>
                                            <td class="active">ACTIVE</td>
                                            <td><span class="badge badge-success">Top</span></td>
                                            <td>
                                                <div>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i class="ri-settings-3-line"></i></span>
                                                    </button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Drilling machine</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-tag">Tools</span>
                                                    <span class="cr-sub-cat-tag">Electrics</span>
                                                </span>
                                            </td>
                                            <td>68</td>
                                            <td class="active">ACTIVE</td>
                                            <td><span class="badge bg-primary">Medium</span></td>
                                            <td>
                                                <div>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i class="ri-settings-3-line"></i></span>
                                                    </button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Smart watches</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-tag">Digital</span>
                                                </span>
                                            </td>
                                            <td>48</td>
                                            <td><span class="inactive">Inactive</span></td>
                                            <td><span class="badge badge-success">Top</span></td>
                                            <td>
                                                <div>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i class="ri-settings-3-line"></i></span>
                                                    </button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Cooler</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-tag">Electrics</span>
                                                </span>
                                            </td>
                                            <td>28</td>
                                            <td class="active">ACTIVE</td>
                                            <td><span class="badge bg-primary">Medium</span></td>
                                            <td>
                                                <div>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i class="ri-settings-3-line"></i></span>
                                                    </button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Lenovo</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-tag">Computers</span>
                                                    <span class="cr-sub-cat-tag">Digital</span>
                                                </span>
                                            </td>
                                            <td>65</td>
                                            <td class="active">ACTIVE</td>
                                            <td><span class="badge bg-primary">Medium</span></td>
                                            <td>
                                                <div>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i class="ri-settings-3-line"></i></span>
                                                    </button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Sofa seat</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-tag">Furniture</span>
                                                </span>
                                            </td>
                                            <td>48</td>
                                            <td class="active">ACTIVE</td>
                                            <td><span class="badge bg-primary">Medium</span></td>
                                            <td>
                                                <div>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i class="ri-settings-3-line"></i></span>
                                                    </button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
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
