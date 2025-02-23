@extends('admin.layouts.master')

@section('content')
    <div class="cr-main-content">
        <div class="container-fluid">

            <div class="cr-page-title">
                <div class="cr-breadcrumb">
                    <h5>eCommerce</h5>
                    <ul>
                        <li><a href="index.html">Carrot</a></li>
                        <li>eCommerce</li>
                    </ul>
                </div>
                <div class="cr-tools">
                    <div id="pagedate">
                        <div class="cr-date-range" title="Date">
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="cr-card">
                                <div class="cr-card-content label-card">
                                    <div class="title">
                                        <span class="icon icon-1"><i class="ri-shield-user-line"></i></span>
                                        <div class="growth-numbers">
                                            <h4>Customers</h4>
                                            <h5>857k</h5>
                                        </div>
                                    </div>
                                    <p class="card-groth up">
                                        <i class="ri-arrow-up-line"></i>
                                        32%
                                        <span>Last Month</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="cr-card">
                                <div class="cr-card-content label-card">
                                    <div class="title">
                                        <span class="icon icon-2"><i class="ri-shopping-bag-3-line"></i></span>
                                        <div class="growth-numbers">
                                            <h4>Order</h4>
                                            <h5>08.65k</h5>
                                        </div>
                                    </div>
                                    <p class="card-groth down">
                                        <i class="ri-arrow-down-line"></i>
                                        1.7%
                                        <span>Last Month</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="cr-card">
                                <div class="cr-card-content label-card">
                                    <div class="title">
                                        <span class="icon icon-3"><i class="ri-money-dollar-circle-line"></i></span>
                                        <div class="growth-numbers">
                                            <h4>Revenue</h4>
                                            <h5>$85746</h5>
                                        </div>
                                    </div>
                                    <p class="card-groth down">
                                        <i class="ri-arrow-down-line"></i>
                                        3.8%
                                        <span>Last Month</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="cr-card">
                                <div class="cr-card-content label-card">
                                    <div class="title">
                                        <span class="icon icon-4"><i class="ri-exchange-dollar-line"></i></span>
                                        <div class="growth-numbers">
                                            <h4>Expenses</h4>
                                            <h5>$75462</h5>
                                        </div>
                                    </div>
                                    <p class="card-groth up">
                                        <i class="ri-arrow-up-line"></i>
                                        8%
                                        <span>Last Month</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-8 col-xl-12">
                    <div class="cr-card revenue-overview">
                        <div class="cr-card-header header-575">
                            <h4 class="cr-card-title">Revenue Overview</h4>
                            <div class="header-tools">
                                <a href="javascript:void(0)" class="m-r-10 cr-full-card" title="Full Screen"><i
                                        class="ri-fullscreen-line"></i></a>
                                <div class="cr-date-range date">
                                    <span></span>
                                </div>
                            </div>
                        </div>
                        <div class="cr-card-content">
                            <div class="cr-chart-header">
                                <div class="block">
                                    <h6>Orders</h6>
                                    <h5>825
                                        <span class="up"><i class="ri-arrow-up-line"></i>24%</span>
                                    </h5>
                                </div>
                                <div class="block">
                                    <h6>Revenue</h6>
                                    <h5>$89k
                                        <span class="up"><i class="ri-arrow-up-line"></i>24%</span>
                                    </h5>
                                </div>
                                <div class="block">
                                    <h6>Expence</h6>
                                    <h5>$68k
                                        <span class="down"><i class="ri-arrow-down-line"></i>24%</span>
                                    </h5>
                                </div>
                                <div class="block">
                                    <h6>Profit</h6>
                                    <h5>$21k
                                        <span class="up"><i class="ri-arrow-up-line"></i>24%</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="cr-chart-content">
                                <div id="newrevenueChart" class="mb-m-24"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-6 col-md-12">
                    <div class="cr-card" id="campaigns">
                        <div class="cr-card-header">
                            <h4 class="cr-card-title">Campaigns</h4>
                            <div class="header-tools">
                                <div class="cr-date-range dots">
                                    <span></span>
                                </div>
                            </div>
                        </div>
                        <div class="cr-card-content">
                            <div class="cr-chart-content">
                                <div id="newcampaignsChart"></div>
                            </div>
                            <div class="cr-chart-header-2">
                                <div class="block">
                                    <h6>Social</h6>
                                    <h5><span class="up">94%<i class="ri-arrow-up-line"></i></span>75k</h5>
                                </div>
                                <div class="block">
                                    <h6>Referral</h6>
                                    <h5><span class="down">96%<i class="ri-arrow-down-line"></i></span>54k</h5>
                                </div>
                                <div class="block">
                                    <h6>Organic</h6>
                                    <h5><span class="up">72%<i class="ri-arrow-up-line"></i></span>2.5k</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-6 col-xl-12">
                    <div class="cr-card" id="best_seller_tbl">
                        <div class="cr-card-header">
                            <h4 class="cr-card-title">Best Seller</h4>
                            <div class="header-tools">
                                <a href="javascript:void(0)" class="m-r-10 cr-full-card" title="Full Screen"><i
                                        class="ri-fullscreen-line"></i></a>
                                <div class="cr-date-range dots">
                                    <span></span>
                                </div>
                            </div>
                        </div>
                        <div class="cr-card-content card-default">
                            <div class="best-seller-table">
                                <div class="table-responsive">
                                    <table id="best_seller_data_table" class="table">
                                        <thead>
                                            <tr>
                                                <th>Vendor</th>
                                                <th>Category</th>
                                                <th>Stock</th>
                                                <th>Sales</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><img class="cat-thumb" src="/be/assets/img/clients/1.jpg"
                                                        alt="clients Image"><span class="name">DS Fashion</span>
                                                </td>
                                                <td>
                                                    <span class="cat">
                                                        <a href="product-list.html">Watches</a>
                                                        <a href="product-list.html">Clothes</a>
                                                        <a href="product-list.html">Phones</a>
                                                    </span>
                                                </td>
                                                <td>685</td>
                                                <td>$1254</td>
                                            </tr>
                                            <tr>
                                                <td><img class="cat-thumb" src="/be/assets/img/clients/2.jpg"
                                                        alt="clients Image"><span class="name">Loka
                                                        Creation</span>
                                                </td>
                                                <td>
                                                    <span class="cat">
                                                        <a href="product-list.html">Phone</a>
                                                        <a href="product-list.html">Camera</a>
                                                        <a href="product-list.html">Clothes</a>
                                                        <a href="product-list.html">Phones</a>
                                                    </span>
                                                </td>
                                                <td>874</td>
                                                <td>$1768</td>
                                            </tr>
                                            <tr>
                                                <td><img class="cat-thumb" src="/be/assets/img/clients/3.jpg"
                                                        alt="clients Image"><span class="name">Vorna Arts</span>
                                                </td>
                                                <td>
                                                    <span class="cat">
                                                        <a href="product-list.html">Laptop</a>
                                                        <a href="product-list.html">Furniture</a>
                                                        <a href="product-list.html">Phones</a>
                                                    </span>
                                                </td>
                                                <td>95</td>
                                                <td>$2296</td>
                                            </tr>
                                            <tr>
                                                <td><img class="cat-thumb" src="/be/assets/img/clients/4.jpg"
                                                        alt="clients Image"><span class="name">Lestie
                                                        Shop</span>
                                                </td>
                                                <td>
                                                    <span class="cat">
                                                        <a href="product-list.html">Clothes</a>
                                                        <a href="product-list.html">Phones</a>
                                                    </span>
                                                </td>
                                                <td>354</td>
                                                <td>$2754</td>
                                            </tr>
                                            <tr>
                                                <td><img class="cat-thumb" src="/be/assets/img/clients/5.jpg"
                                                        alt="clients Image"><span class="name">Moris
                                                        Gallery</span>
                                                </td>
                                                <td>
                                                    <span class="cat">
                                                        <a href="product-list.html">Tools</a>
                                                        <a href="product-list.html">Clothes</a>
                                                        <a href="product-list.html">Bags</a>
                                                    </span>
                                                </td>
                                                <td>675</td>
                                                <td>$3105</td>
                                            </tr>
                                            <tr>
                                                <td><img class="cat-thumb" src="/be/assets/img/clients/6.jpg"
                                                        alt="clients Image"><span class="name">Jens
                                                        Fashion.</span>
                                                </td>
                                                <td>
                                                    <span class="cat">
                                                        <a href="product-list.html">Shoes</a>
                                                        <a href="product-list.html">Clothes</a>
                                                        <a href="product-list.html">Jewellery</a>
                                                    </span>
                                                </td>
                                                <td>854</td>
                                                <td>$3854</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 col-xl-12">
                    <div class="cr-card" id="top_product_tbl">
                        <div class="cr-card-header">
                            <h4 class="cr-card-title">Top Product</h4>
                            <div class="header-tools">
                                <a href="javascript:void(0)" class="m-r-10 cr-full-card" title="Full Screen"><i
                                        class="ri-fullscreen-line"></i></a>
                                <div class="cr-date-range dots">
                                    <span></span>
                                </div>
                            </div>
                        </div>
                        <div class="cr-card-content card-default">
                            <div class="top-product-table">
                                <div class="table-responsive">
                                    <table id="top_product_data_table" class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Orders</th>
                                                <th>Stock</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><img class="cat-thumb" src="/be/assets/img/product/1.jpg"
                                                        alt="clients Image"><span class="name">Mens
                                                        t-shirt</span>
                                                </td>
                                                <td>$16</td>
                                                <td>162</td>
                                                <td>456</td>
                                                <td>$3524</td>
                                            </tr>
                                            <tr>
                                                <td><img class="cat-thumb" src="/be/assets/img/product/2.jpg"
                                                        alt="clients Image"><span class="name">Sofa chair</span>
                                                </td>
                                                <td>$52</td>
                                                <td>178</td>
                                                <td>958</td>
                                                <td>$8654</td>
                                            </tr>
                                            <tr>
                                                <td><img class="cat-thumb" src="/be/assets/img/product/3.jpg"
                                                        alt="clients Image"><span class="name">Night Lamp</span>
                                                </td>
                                                <td>$70</td>
                                                <td>198</td>
                                                <td>1524</td>
                                                <td>$1152</td>
                                            </tr>
                                            <tr>
                                                <td><img class="cat-thumb" src="/be/assets/img/product/4.jpg"
                                                        alt="clients Image"><span class="name">Mens
                                                        hoodie</span>
                                                </td>
                                                <td>$16</td>
                                                <td>205</td>
                                                <td>253</td>
                                                <td>$5645</td>
                                            </tr>
                                            <tr>
                                                <td><img class="cat-thumb" src="/be/assets/img/product/5.jpg"
                                                        alt="clients Image"><span class="name">Digital
                                                        Watch</span>
                                                </td>
                                                <td>$559</td>
                                                <td>209</td>
                                                <td>456</td>
                                                <td>$20546</td>
                                            </tr>
                                            <tr>
                                                <td><img class="cat-thumb" src="/be/assets/img/product/6.jpg"
                                                        alt="clients Image"><span class="name">DSLR
                                                        Camera.</span>
                                                </td>
                                                <td>$1546</td>
                                                <td>212</td>
                                                <td>98</td>
                                                <td>$33500</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-8 col-xl-12">
                    <div class="cr-card" id="ordertbl">
                        <div class="cr-card-header">
                            <h4 class="cr-card-title">Recent Orders</h4>
                            <div class="header-tools">
                                <a href="javascript:void(0)" class="m-r-10 cr-full-card" title="Full Screen"><i
                                        class="ri-fullscreen-line"></i></a>
                                <div class="cr-date-range dots">
                                    <span></span>
                                </div>
                            </div>
                        </div>
                        <div class="cr-card-content card-default">
                            <div class="order-table">
                                <div class="table-responsive">
                                    <table id="recent_order_data_table" class="table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Product</th>
                                                <th>Customer</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>vendor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="token">#fx2650</td>
                                                <td><img class="cat-thumb" src="/be/assets/img/product/1.jpg"
                                                        alt="clients Image"><span class="name">Mens
                                                        t-shirt</span>
                                                </td>
                                                <td>Avira Venusio</td>
                                                <td>$15</td>
                                                <td class="cod">COD</td>
                                                <td>Melborn Fashion</td>
                                            </tr>
                                            <tr>
                                                <td class="token">#fx2650</td>
                                                <td><img class="cat-thumb" src="/be/assets/img/product/2.jpg"
                                                        alt="clients Image"><span class="name">Sofa chair</span>
                                                </td>
                                                <td>Zara nails</td>
                                                <td>$52</td>
                                                <td class="pending">Pending</td>
                                                <td>Capital Mines</td>
                                            </tr>
                                            <tr>
                                                <td class="token">#fx2365</td>
                                                <td><img class="cat-thumb" src="/be/assets/img/product/3.jpg"
                                                        alt="clients Image"><span class="name">Night Lamp</span>
                                                </td>
                                                <td>Olive Yew</td>
                                                <td>$69</td>
                                                <td class="wallet">wallet</td>
                                                <td>Bara Electrics</td>
                                            </tr>
                                            <tr>
                                                <td class="token">#fx2205</td>
                                                <td><img class="cat-thumb" src="/be/assets/img/product/4.jpg"
                                                        alt="clients Image"><span class="name">Mens
                                                        hoodie</span>
                                                </td>
                                                <td>Allie Grater</td>
                                                <td>$49</td>
                                                <td class="paid">Paid</td>
                                                <td>Forest clothes</td>
                                            </tr>
                                            <tr>
                                                <td class="token">#fx2187</td>
                                                <td><img class="cat-thumb" src="/be/assets/img/product/5.jpg"
                                                        alt="clients Image"><span class="name">Digital
                                                        Watch</span>
                                                </td>
                                                <td>Stanley Knife</td>
                                                <td>$559</td>
                                                <td class="cod">COD</td>
                                                <td>Samsung Digi</td>
                                            </tr>
                                            <tr>
                                                <td class="token">#fx2050</td>
                                                <td><img class="cat-thumb" src="/be/assets/img/product/6.jpg"
                                                        alt="clients Image"><span class="name">DSLR
                                                        Camera.</span>
                                                </td>
                                                <td>Nick Carlet</td>
                                                <td>$1546</td>
                                                <td class="wallet">Wallet</td>
                                                <td>Canion tech</td>
                                            </tr>
                                            <tr>
                                                <td class="token">#fx1995</td>
                                                <td><img class="cat-thumb" src="/be/assets/img/product/7.jpg"
                                                        alt="clients Image"><span class="name">Head phone</span>
                                                </td>
                                                <td>Moris Nency</td>
                                                <td>$525</td>
                                                <td class="paid">Paid</td>
                                                <td>Beater Digital</td>
                                            </tr>
                                            <tr>
                                                <td class="token">#fx1985</td>
                                                <td><img class="cat-thumb" src="/be/assets/img/product/8.jpg"
                                                        alt="clients Image"><span class="name">Camera
                                                        Dron</span>
                                                </td>
                                                <td>Wiley Waites</td>
                                                <td>$1255</td>
                                                <td class="paid">Paid</td>
                                                <td>Four wing</td>
                                            </tr>
                                            <tr>
                                                <td class="token">#fx1945</td>
                                                <td><img class="cat-thumb" src="/be/assets/img/product/9.jpg"
                                                        alt="clients Image"><span class="name">Drill
                                                        machine</span>
                                                </td>
                                                <td>Sarah Moanees</td>
                                                <td>$684</td>
                                                <td class="pending">pending</td>
                                                <td>Hitachu</td>
                                            </tr>
                                            <tr>
                                                <td class="token">#fx1865</td>
                                                <td><img class="cat-thumb" src="/be/assets/img/product/10.jpg"
                                                        alt="clients Image"><span class="name">Camera
                                                        Dron</span>
                                                </td>
                                                <td>Anne Ortha</td>
                                                <td>$854</td>
                                                <td class="cod">COD</td>
                                                <td>Skyrider tech</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-6 col-md-12">
                    <div class="cr-card" id="fxmap">
                        <div class="cr-card-header">
                            <h4 class="cr-card-title">Top Country</h4>
                            <div class="header-tools">
                                <div class="cr-date-range dots">
                                    <span></span>
                                </div>
                            </div>
                        </div>
                        <div class="cr-card-content">
                            <div class="cr-map-view ecom-map">
                                <div id="world-map" class="world-map"></div>
                            </div>
                            <div class="cr-map-detail">
                                <div class="cr-map-detail">
                                    <div class="title">
                                        <h5>Revenue</h5>
                                        <a href="#" class="visit" title="View all data">view <i
                                                class="ri-arrow-right-line"></i></a>
                                    </div>
                                    <div class="cr-detail-list">
                                        <div class="cr-label">
                                            <div>
                                                <label>India</label>
                                                <span class="down"><i class="ri-arrow-down-line"></i>2.6%</span>
                                            </div>
                                            <p>$958.5k</p>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-secondary" role="progressbar" style="width: 95%"
                                                aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="cr-detail-list">
                                        <div class="cr-label">
                                            <div>
                                                <label>Morocco</label>
                                                <span class="up"><i class="ri-arrow-up-line"></i>5.6%</span>
                                            </div>
                                            <p>$788.7k</p>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 84%"
                                                aria-valuenow="84" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="cr-detail-list">
                                        <div class="cr-label">
                                            <div>
                                                <label>Brazil</label>
                                                <span class="up"><i class="ri-arrow-up-line"></i>3.7%</span>
                                            </div>
                                            <p>$592.2k</p>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 76%"
                                                aria-valuenow="76" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
