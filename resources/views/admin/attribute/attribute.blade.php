@extends('admin.layouts.master')

@section('content')
    <!-- main content -->
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
            <div class="row cr-category">
                <div class="col-xl-4 col-lg-12">
                    <div class="team-sticky-bar">
                        <div class="col-md-12">
                            <div class="cr-cat-list cr-card card-default mb-24px">
                                <div class="cr-card-content">
                                    <div class="cr-cat-form">
                                        <h3>Add New Category</h3>

                                        <form>

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
                                            <th>Sub Categories</th>
                                            <th>Product</th>
                                            <th>Status</th>
                                            <th>Trending</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>Clothes</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-count" title="Total Sub Categories">5</span>
                                                    <span class="cr-sub-cat-tag">T-shirt</span>
                                                    <span class="cr-sub-cat-tag">Shirt</span>
                                                    <span class="cr-sub-cat-tag">Dress</span>
                                                    <span class="cr-sub-cat-tag">Jeans</span>
                                                    <span class="cr-sub-cat-tag">Top</span>
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
                                            <td>Footwear</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-count" title="Total Sub Categories">4</span>
                                                    <span class="cr-sub-cat-tag">Sports</span>
                                                    <span class="cr-sub-cat-tag">Casual</span>
                                                    <span class="cr-sub-cat-tag">safety shoes </span>
                                                    <span class="cr-sub-cat-tag">Sandal</span>
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
                                            <td>Jewelry</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-count" title="Total Sub Categories">4</span>
                                                    <span class="cr-sub-cat-tag">necklace</span>
                                                    <span class="cr-sub-cat-tag">chain</span>
                                                    <span class="cr-sub-cat-tag">rings</span>
                                                    <span class="cr-sub-cat-tag">earings</span>
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
                                            <td>Perfume</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-count" title="Total Sub Categories">4</span>
                                                    <span class="cr-sub-cat-tag">Clothes perfume</span>
                                                    <span class="cr-sub-cat-tag">deodorant </span>
                                                    <span class="cr-sub-cat-tag">Flower fragrance </span>
                                                    <span class="cr-sub-cat-tag">Air Freshener</span>
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
                                            <td>Cosmetics</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-count" title="Total Sub Categories">10</span>
                                                    <span class="cr-sub-cat-tag">Makeup kit</span>
                                                    <span class="cr-sub-cat-tag">Hair gel</span>
                                                    <span class="cr-sub-cat-tag">sunscreen</span>
                                                    <span class="cr-sub-cat-tag">facewash</span>
                                                    <span class="cr-sub-cat-tag">Body shop</span>
                                                    <span class="cr-sub-cat-tag">Lipstick</span>
                                                    <span class="cr-sub-cat-tag">eye liner</span>
                                                    <span class="cr-sub-cat-tag">Hair Shampoo</span>
                                                    <span class="cr-sub-cat-tag">Beauty Cream</span>
                                                    <span class="cr-sub-cat-tag">Skin Serum</span>
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
                                            <td>Glasses</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-count" title="Total Sub Categories">2</span>
                                                    <span class="cr-sub-cat-tag">Sunglasses </span>
                                                    <span class="cr-sub-cat-tag">Lenses </span>
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
                                            <td>Bags</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-count" title="Total Sub Categories">4</span>
                                                    <span class="cr-sub-cat-tag">shopping bag</span>
                                                    <span class="cr-sub-cat-tag">Gym backpack</span>
                                                    <span class="cr-sub-cat-tag">purse </span>
                                                    <span class="cr-sub-cat-tag">wallet </span>
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
                                            <td>Tools</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-count" title="Total Sub Categories">5</span>
                                                    <span class="cr-sub-cat-tag">Drill machine</span>
                                                    <span class="cr-sub-cat-tag">Grinder machine</span>
                                                    <span class="cr-sub-cat-tag">Hammer</span>
                                                    <span class="cr-sub-cat-tag">Wrench</span>
                                                    <span class="cr-sub-cat-tag">breaker machine</span>
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
                                            <td>Digital</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-count" title="Total Sub Categories">5</span>
                                                    <span class="cr-sub-cat-tag">Camera</span>
                                                    <span class="cr-sub-cat-tag">phone</span>
                                                    <span class="cr-sub-cat-tag">laptop</span>
                                                    <span class="cr-sub-cat-tag">watches</span>
                                                    <span class="cr-sub-cat-tag">charger</span>
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
                                            <td>Electrics</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-count" title="Total Sub Categories">3</span>
                                                    <span class="cr-sub-cat-tag">AC</span>
                                                    <span class="cr-sub-cat-tag">TV</span>
                                                    <span class="cr-sub-cat-tag">Cooler</span>
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
                                            <td>Computers</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-count" title="Total Sub Categories">5</span>
                                                    <span class="cr-sub-cat-tag">Dell</span>
                                                    <span class="cr-sub-cat-tag">sony</span>
                                                    <span class="cr-sub-cat-tag">lenovo</span>
                                                    <span class="cr-sub-cat-tag">Samsung</span>
                                                    <span class="cr-sub-cat-tag">lg</span>
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
                                            <td>Furniture</td>
                                            <td>
                                                <span class="cr-sub-cat-list">
                                                    <span class="cr-sub-cat-count" title="Total Sub Categories">2</span>
                                                    <span class="cr-sub-cat-tag">Sofa</span>
                                                    <span class="cr-sub-cat-tag">Chair</span>
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
