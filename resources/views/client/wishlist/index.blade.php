@extends('client.master')

@section('content')

    <div class="container-small cart">
      <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="#!">Page 1</a></li>
          <li class="breadcrumb-item"><a href="#!">Page 2</a></li>
          <li class="breadcrumb-item active" aria-current="page">Default</li>
        </ol>
      </nav>
      <h2 class="mb-5">Wishlist<span class="text-body-tertiary fw-normal ms-2">(43)</span></h2>
      <div class="border-y border-translucent" id="productWishlistTable" data-list='{"valueNames":["products","color","size","price","quantity","total"],"page":5,"pagination":true}'>
        <div class="table-responsive scrollbar">
          <table class="table fs-9 mb-0">
            <thead>
              <tr>
                <th class="sort white-space-nowrap align-middle fs-10" scope="col" style="width:7%;"></th>
                <th class="sort white-space-nowrap align-middle" scope="col" style="width:30%; min-width:250px;" data-sort="products">PRODUCTS</th>
                <th class="sort align-middle" scope="col" data-sort="color" style="width:16%;">COLOR</th>
                <th class="sort align-middle" scope="col" data-sort="size" style="width:10%;">SIZE</th>
                <th class="sort align-middle text-end" scope="col" data-sort="price" style="width:10%;">PRICE</th>
                <th class="sort align-middle text-end pe-0" scope="col" style="width:35%;"> </th>
              </tr>
            </thead>
            <tbody class="list" id="profile-wishlist-table-body">
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="align-middle white-space-nowrap ps-0 py-0"><a class="border border-translucent rounded-2 d-inline-block" href="product-details.html"><img src="../../../assets/img/products/1.png" alt="" width="53" /></a></td>
                <td class="products align-middle pe-11"><a class="fw-semibold mb-0 line-clamp-1" href="product-details.html">Fitbit Sense Advanced Smartwatch with Tools for Heart Health, Stress Management &amp; Skin Temperature Trends, Carbon/Graphite, One Size (S &amp; L Bands)</a></td>
                <td class="color align-middle white-space-nowrap fs-9 text-body">Pure matte black</td>
                <td class="size align-middle white-space-nowrap text-body-tertiary fs-9 fw-semibold">42</td>
                <td class="price align-middle text-body fs-9 fw-semibold text-end">$57</td>
                <td class="total align-middle fw-bold text-body-highlight text-end text-nowrap pe-0"><button class="btn btn-sm text-body-quaternary text-body-tertiary-hover me-2"><span class="fas fa-trash"></span></button><button class="btn btn-primary fs-10"><span class="fas fa-shopping-cart me-1 fs-10"></span>Add to cart</button></td>
              </tr>
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="align-middle white-space-nowrap ps-0 py-0"><a class="border border-translucent rounded-2 d-inline-block" href="product-details.html"><img src="../../../assets/img/products/7.png" alt="" width="53" /></a></td>
                <td class="products align-middle pe-11"><a class="fw-semibold mb-0 line-clamp-1" href="product-details.html">2021 Apple 12.9-inch iPad Pro (Wi‑Fi, 128GB) - Space Gray</a></td>
                <td class="color align-middle white-space-nowrap fs-9 text-body">Black</td>
                <td class="size align-middle white-space-nowrap text-body-tertiary fs-9 fw-semibold">Pro</td>
                <td class="price align-middle text-body fs-9 fw-semibold text-end">$1,499</td>
                <td class="total align-middle fw-bold text-body-highlight text-end text-nowrap pe-0"><button class="btn btn-sm text-body-quaternary text-body-tertiary-hover me-2"><span class="fas fa-trash"></span></button><button class="btn btn-primary fs-10"><span class="fas fa-shopping-cart me-1 fs-10"></span>Add to cart</button></td>
              </tr>
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="align-middle white-space-nowrap ps-0 py-0"><a class="border border-translucent rounded-2 d-inline-block" href="product-details.html"><img src="../../../assets/img/products/6.png" alt="" width="53" /></a></td>
                <td class="products align-middle pe-11"><a class="fw-semibold mb-0 line-clamp-1" href="product-details.html">PlayStation 5 DualSense Wireless Controller</a></td>
                <td class="color align-middle white-space-nowrap fs-9 text-body">White</td>
                <td class="size align-middle white-space-nowrap text-body-tertiary fs-9 fw-semibold">Regular</td>
                <td class="price align-middle text-body fs-9 fw-semibold text-end">$299</td>
                <td class="total align-middle fw-bold text-body-highlight text-end text-nowrap pe-0"><button class="btn btn-sm text-body-quaternary text-body-tertiary-hover me-2"><span class="fas fa-trash"></span></button><button class="btn btn-primary fs-10"><span class="fas fa-shopping-cart me-1 fs-10"></span>Add to cart</button></td>
              </tr>
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="align-middle white-space-nowrap ps-0 py-0"><a class="border border-translucent rounded-2 d-inline-block" href="product-details.html"><img src="../../../assets/img/products/3.png" alt="" width="53" /></a></td>
                <td class="products align-middle pe-11"><a class="fw-semibold mb-0 line-clamp-1" href="product-details.html">Apple MacBook Pro 13 inch-M1-8/256GB-space</a></td>
                <td class="color align-middle white-space-nowrap fs-9 text-body">Space Gray</td>
                <td class="size align-middle white-space-nowrap text-body-tertiary fs-9 fw-semibold">Pro</td>
                <td class="price align-middle text-body fs-9 fw-semibold text-end">$1,699</td>
                <td class="total align-middle fw-bold text-body-highlight text-end text-nowrap pe-0"><button class="btn btn-sm text-body-quaternary text-body-tertiary-hover me-2"><span class="fas fa-trash"></span></button><button class="btn btn-primary fs-10"><span class="fas fa-shopping-cart me-1 fs-10"></span>Add to cart</button></td>
              </tr>
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="align-middle white-space-nowrap ps-0 py-0"><a class="border border-translucent rounded-2 d-inline-block" href="product-details.html"><img src="../../../assets/img/products/4.png" alt="" width="53" /></a></td>
                <td class="products align-middle pe-11"><a class="fw-semibold mb-0 line-clamp-1" href="product-details.html">Apple iMac 24&quot; 4K Retina Display M1 8 Core CPU, 7 Core GPU, 256GB SSD, Green (MJV83ZP/A) 2021</a></td>
                <td class="color align-middle white-space-nowrap fs-9 text-body">Ocean Blue</td>
                <td class="size align-middle white-space-nowrap text-body-tertiary fs-9 fw-semibold">21&quot;</td>
                <td class="price align-middle text-body fs-9 fw-semibold text-end">$65</td>
                <td class="total align-middle fw-bold text-body-highlight text-end text-nowrap pe-0"><button class="btn btn-sm text-body-quaternary text-body-tertiary-hover me-2"><span class="fas fa-trash"></span></button><button class="btn btn-primary fs-10"><span class="fas fa-shopping-cart me-1 fs-10"></span>Add to cart</button></td>
              </tr>
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="align-middle white-space-nowrap ps-0 py-0"><a class="border border-translucent rounded-2 d-inline-block" href="product-details.html"><img src="../../../assets/img/products/10.png" alt="" width="53" /></a></td>
                <td class="products align-middle pe-11"><a class="fw-semibold mb-0 line-clamp-1" href="product-details.html">Apple Magic Mouse (Wireless, Rechargable) - Silver</a></td>
                <td class="color align-middle white-space-nowrap fs-9 text-body">White</td>
                <td class="size align-middle white-space-nowrap text-body-tertiary fs-9 fw-semibold">Regular</td>
                <td class="price align-middle text-body fs-9 fw-semibold text-end">$30</td>
                <td class="total align-middle fw-bold text-body-highlight text-end text-nowrap pe-0"><button class="btn btn-sm text-body-quaternary text-body-tertiary-hover me-2"><span class="fas fa-trash"></span></button><button class="btn btn-primary fs-10"><span class="fas fa-shopping-cart me-1 fs-10"></span>Add to cart</button></td>
              </tr>
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="align-middle white-space-nowrap ps-0 py-0"><a class="border border-translucent rounded-2 d-inline-block" href="product-details.html"><img src="../../../assets/img/products/8.png" alt="" width="53" /></a></td>
                <td class="products align-middle pe-11"><a class="fw-semibold mb-0 line-clamp-1" href="product-details.html">Amazon Basics Matte Black Wired Keyboard - US Layout (QWERTY)</a></td>
                <td class="color align-middle white-space-nowrap fs-9 text-body">Black</td>
                <td class="size align-middle white-space-nowrap text-body-tertiary fs-9 fw-semibold">MD</td>
                <td class="price align-middle text-body fs-9 fw-semibold text-end">$40</td>
                <td class="total align-middle fw-bold text-body-highlight text-end text-nowrap pe-0"><button class="btn btn-sm text-body-quaternary text-body-tertiary-hover me-2"><span class="fas fa-trash"></span></button><button class="btn btn-primary fs-10"><span class="fas fa-shopping-cart me-1 fs-10"></span>Add to cart</button></td>
              </tr>
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="align-middle white-space-nowrap ps-0 py-0"><a class="border border-translucent rounded-2 d-inline-block" href="product-details.html"><img src="../../../assets/img/products/12.png" alt="" width="53" /></a></td>
                <td class="products align-middle pe-11"><a class="fw-semibold mb-0 line-clamp-1" href="product-details.html">HORI Racing Wheel Apex for PlayStation 4_3, and PC</a></td>
                <td class="color align-middle white-space-nowrap fs-9 text-body">Black</td>
                <td class="size align-middle white-space-nowrap text-body-tertiary fs-9 fw-semibold">45</td>
                <td class="price align-middle text-body fs-9 fw-semibold text-end">$130</td>
                <td class="total align-middle fw-bold text-body-highlight text-end text-nowrap pe-0"><button class="btn btn-sm text-body-quaternary text-body-tertiary-hover me-2"><span class="fas fa-trash"></span></button><button class="btn btn-primary fs-10"><span class="fas fa-shopping-cart me-1 fs-10"></span>Add to cart</button></td>
              </tr>
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="align-middle white-space-nowrap ps-0 py-0"><a class="border border-translucent rounded-2 d-inline-block" href="product-details.html"><img src="../../../assets/img/products/17.png" alt="" width="53" /></a></td>
                <td class="products align-middle pe-11"><a class="fw-semibold mb-0 line-clamp-1" href="product-details.html">Xbox Series S</a></td>
                <td class="color align-middle white-space-nowrap fs-9 text-body">Space Gray</td>
                <td class="size align-middle white-space-nowrap text-body-tertiary fs-9 fw-semibold">sm</td>
                <td class="price align-middle text-body fs-9 fw-semibold text-end">$99</td>
                <td class="total align-middle fw-bold text-body-highlight text-end text-nowrap pe-0"><button class="btn btn-sm text-body-quaternary text-body-tertiary-hover me-2"><span class="fas fa-trash"></span></button><button class="btn btn-primary fs-10"><span class="fas fa-shopping-cart me-1 fs-10"></span>Add to cart</button></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="row align-items-center justify-content-between py-2 pe-0 fs-9">
          <div class="col-auto d-flex">
            <p class="mb-0 d-none d-sm-block me-3 fw-semibold text-body" data-list-info="data-list-info"></p><a class="fw-semibold" href="#!" data-list-view="*">View all<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a><a class="fw-semibold d-none" href="#!" data-list-view="less">View Less<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
          </div>
          <div class="col-auto d-flex"><button class="page-link" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
            <ul class="mb-0 pagination"></ul><button class="page-link pe-0" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
          </div>
        </div>
      </div>
    </div><!-- end of .container-->

@endsection
