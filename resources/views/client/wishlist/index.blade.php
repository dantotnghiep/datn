@extends('client.master')

@section('content')

    <div class="container-small cart">
      <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
          <li class="breadcrumb-item active" aria-current="page">Yêu thích</li>
        </ol>
      </nav>
      <h2 class="mb-5">Danh sách yêu thích <span class="text-body-tertiary fw-normal ms-2">({{ count($wishlists) }})</span></h2>

      @if(count($wishlists) > 0)
      <div class="border-y border-translucent" id="productWishlistTable" data-list='{"valueNames":["products","color","size","price","quantity","total"],"page":10,"pagination":true}'>
        <div class="table-responsive scrollbar">
          <table class="table fs-9 mb-0">
            <thead>
              <tr>
                <th class="sort white-space-nowrap align-middle fs-10" scope="col" style="width:7%;"></th>
                <th class="sort white-space-nowrap align-middle" scope="col" style="width:30%; min-width:250px;" data-sort="products">Sản phẩm</th>
                <th class="sort align-middle" scope="col" data-sort="color" style="width:16%;">Biến thể</th>
                <th class="sort align-middle text-end" scope="col" data-sort="price" style="width:10%;">Giá</th>
                <th class="sort align-middle text-end pe-0" scope="col" style="width:35%;"> </th>
              </tr>
            </thead>
            <tbody class="list" id="profile-wishlist-table-body">
              @foreach($wishlists as $wishlist)
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="align-middle white-space-nowrap ps-0 py-0">
                  <a class="border border-translucent rounded-2 d-inline-block" href="{{ route('product.detail', $wishlist->productVariation->product->slug) }}">
                    <img src="{{ $wishlist->productVariation->product->first_image }}"
                    alt="{{ $wishlist->productVariation->product->name }}" width="53" />
                  </a>
                </td>
                <td class="products align-middle pe-11">
                  <a class="fw-semibold mb-0 line-clamp-1" href="{{ route('product.detail', $wishlist->productVariation->product->slug) }}">
                    {{ $wishlist->productVariation->product->name }}
                  </a>
                </td>
                <td class="color align-middle white-space-nowrap fs-9 text-body">
                  {{ $wishlist->productVariation->name }}
                </td>
                <td class="price align-middle text-body fs-9 fw-semibold text-end">
                  {{ number_format($wishlist->productVariation->price, 0, ',', '.') }} VNĐ
                </td>
                <td class="total align-middle fw-bold text-body-highlight text-end text-nowrap pe-0">
                  <form action="{{ route('wishlist.remove', $wishlist->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm text-body-quaternary text-body-tertiary-hover me-2">
                      <span class="fas fa-trash"></span>
                    </button>
                  </form>
                  <a href="{{ route('product.detail', $wishlist->productVariation->product->slug) }}" class="btn btn-primary fs-10">
                    <span class="fas fa-shopping-cart me-1 fs-10"></span>Thêm vào giỏ
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="row align-items-center justify-content-between py-2 pe-0 fs-9">
          <div class="col-auto d-flex">
            <p class="mb-0 d-none d-sm-block me-3 fw-semibold text-body" data-list-info="data-list-info"></p>
            <a class="fw-semibold" href="#!" data-list-view="*">Xem tất cả<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
            <a class="fw-semibold d-none" href="#!" data-list-view="less">Xem ít hơn<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
          </div>
          <div class="col-auto d-flex">
            <button class="page-link" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
            <ul class="mb-0 pagination"></ul>
            <button class="page-link pe-0" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
          </div>
        </div>
      </div>
      @else
      <div class="text-center py-5">
        <div class="mb-4">
          <span class="far fa-heart fs-1 text-body-tertiary"></span>
        </div>
        <h4>Danh sách yêu thích của bạn đang trống</h4>
        <p class="text-body-tertiary">Hãy thêm sản phẩm yêu thích để xem ở đây</p>
        <a href="{{ route('product') }}" class="btn btn-primary">Khám phá sản phẩm</a>
      </div>
      @endif
    </div><!-- end of .container-->

@endsection
