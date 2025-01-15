@extends('admin.layouts.master')

@section('content')
    <div class="cr-main-content">
        <div class="container-fluid">

            <div class="cr-page-title cr-page-title-2">
                <div class="cr-breadcrumb">
                    <h5>Product List</h5>
                    <ul>
                        <li><a href="index.html">Carrot</a></li>
                        <li>Product List</li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="cr-card card-default product-list">
                        <div class="cr-card-content ">
                            <div class="table-responsive">
                                <table id="product_list" class="table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Offer</th>
                                            <th>Purchased</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td><img class="tbl-thumb" src="/be/assets/img/product/1.jpg" alt="Product Image">
                                            </td>
                                            <td>Mens t-shirt</td>
                                            <td>$20</td>
                                            <td>25% OFF</td>
                                            <td>61</td>
                                            <td>5421</td>
                                            <td><span class="active">active</span></td>
                                            <td>05/11/2023</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
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
                                            <td><img class="tbl-thumb" src="/be/assets/img/product/2.jpg" alt="Product Image">
                                            </td>
                                            <td>Sofa seat furniture</td>
                                            <td>$400</td>
                                            <td>30% OFF</td>
                                            <td>80</td>
                                            <td>25</td>
                                            <td><span class="pending">pending</span></td>
                                            <td>12/08/2022</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
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
                                            <td><img class="tbl-thumb" src="/be/assets/img/product/3.jpg" alt="Product Image">
                                            </td>
                                            <td>Night lamp bedroom</td>
                                            <td>$59</td>
                                            <td>30% OFF</td>
                                            <td>100</td>
                                            <td>56</td>
                                            <td><span class="disable">disable</span></td>
                                            <td>25/05/2021</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
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
                                            <td><img class="tbl-thumb" src="/be/assets/img/product/4.jpg" alt="Product Image">
                                            </td>
                                            <td>Round Cap hoodies</td>
                                            <td>$10</td>
                                            <td>30% OFF</td>
                                            <td>250</td>
                                            <td>568</td>
                                            <td><span class="active">active</span></td>
                                            <td>13/09/2019</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
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
                                            <td><img class="tbl-thumb" src="/be/assets/img/product/5.jpg" alt="Product Image">
                                            </td>
                                            <td>Digital watch black</td>
                                            <td>$582</td>
                                            <td>30% OFF</td>
                                            <td>220</td>
                                            <td>264</td>
                                            <td><span class="pending">pending</span></td>
                                            <td>18/02/2023</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
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
                                            <td><img class="tbl-thumb" src="/be/assets/img/product/6.jpg"
                                                    alt="Product Image"></td>
                                            <td>Digital camera dslr</td>
                                            <td>$1254</td>
                                            <td>20% OFF</td>
                                            <td>154</td>
                                            <td>365</td>
                                            <td><span class="disable">disable</span></td>
                                            <td>16/11/2021</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
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
                                            <td><img class="tbl-thumb" src="/be/assets/img/product/7.jpg"
                                                    alt="Product Image"></td>
                                            <td>Headphone beater m3</td>
                                            <td>$68</td>
                                            <td>30% OFF</td>
                                            <td>159</td>
                                            <td>789</td>
                                            <td><span class="active">active</span></td>
                                            <td>23/04/2024</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
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
                                            <td><img class="tbl-thumb" src="/be/assets/img/product/8.jpg"
                                                    alt="Product Image"></td>
                                            <td>Camera dron fly b2</td>
                                            <td>$120</td>
                                            <td>80% OFF</td>
                                            <td>12</td>
                                            <td>325</td>
                                            <td><span class="active">active</span></td>
                                            <td>21/06/2023</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
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
                                            <td><img class="tbl-thumb" src="/be/assets/img/product/9.jpg"
                                                    alt="Product Image"></td>
                                            <td>Drill machine dregon g1</td>
                                            <td>$20</td>
                                            <td>10% OFF</td>
                                            <td>254</td>
                                            <td>36</td>
                                            <td><span class="pending">pending</span></td>
                                            <td>12/12/2022</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
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
                                            <td><img class="tbl-thumb" src="/be/assets/img/product/10.jpg"
                                                    alt="Product Image"></td>
                                            <td>Fly cry dron camera</td>
                                            <td>$548</td>
                                            <td>50% OFF</td>
                                            <td>25</td>
                                            <td>12</td>
                                            <td><span class="disable">disable</span></td>
                                            <td>12/05/2023</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
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
                                            <td><img class="tbl-thumb" src="/be/assets/img/product/5.jpg"
                                                    alt="Product Image"></td>
                                            <td>Black watches s20</td>
                                            <td>$254</td>
                                            <td>30% OFF</td>
                                            <td>65</td>
                                            <td>12</td>
                                            <td><span class="active">active</span></td>
                                            <td>15/06/2012</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
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
                                            <td><img class="tbl-thumb" src="/be/assets/img/product/6.jpg"
                                                    alt="Product Image"></td>
                                            <td>Dslr digital camera</td>
                                            <td>$325</td>
                                            <td>40% OFF</td>
                                            <td>265</td>
                                            <td>21</td>
                                            <td><span class="active">active</span></td>
                                            <td>25/04/2019</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
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
                                            <td><img class="tbl-thumb" src="/be/assets/img/product/7.jpg"
                                                    alt="Product Image"></td>
                                            <td>Headphone drag g9</td>
                                            <td>$65</td>
                                            <td>40% OFF</td>
                                            <td>487</td>
                                            <td>35</td>
                                            <td><span class="disable">disable</span></td>
                                            <td>25/08/2022</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
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
                                            <td><img class="tbl-thumb" src="/be/assets/img/product/8.jpg"
                                                    alt="Product Image"></td>
                                            <td>Orbite dron ss2</td>
                                            <td>$754</td>
                                            <td>40% OFF</td>
                                            <td>12</td>
                                            <td>48</td>
                                            <td><span class="active">active</span></td>
                                            <td>15/06/2015</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
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
                                            <td><img class="tbl-thumb" src="/be/assets/img/product/9.jpg"
                                                    alt="Product Image"></td>
                                            <td>Drill machine br6</td>
                                            <td>$548</td>
                                            <td>30% OFF</td>
                                            <td>250</td>
                                            <td>84</td>
                                            <td><span class="active">active</span></td>
                                            <td>16/11/2023</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
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
