@extends('client.master')
@section('content')
<div class="pt-5 pb-9">
    <section class="py-0">
        <div class="container-small">
            <nav class="mb-3" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ $product->category->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
            <div class="row g-5 mb-5 mb-lg-8" data-product-details="data-product-details">
                <div class="col-12 col-lg-6">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-2 col-lg-12 col-xl-2">
                            <div class="swiper-products-thumb swiper swiper theme-slider overflow-visible" id="swiper-products-thumb">
                                @foreach($product->images as $image)
                                <div class="swiper-slide">
                                    <img src="{{ asset($image->image_path) }}" alt="{{ $product->name }}" class="img-fluid rounded-3"/>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12 col-md-10 col-lg-12 col-xl-10">
                            <div class="d-flex align-items-center border border-translucent rounded-3 text-center p-5 h-100">
                                <div class="swiper swiper theme-slider" data-thumb-target="swiper-products-thumb">
                                    @foreach($product->images->where('is_primary', true) as $primaryImage)
                                    <div class="swiper-slide">
                                        <img src="{{ asset($primaryImage->image_path) }}" alt="{{ $product->name }}" class="img-fluid"/>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <button class="btn btn-lg btn-outline-warning rounded-pill w-100 me-3 px-2 px-sm-4 fs-9 fs-sm-8">
                            <span class="me-2 far fa-heart"></span>Thêm vào yêu thích
                        </button>
                        <button class="btn btn-lg btn-warning rounded-pill w-100 fs-9 fs-sm-8">
                            <span class="fas fa-shopping-cart me-2"></span>Thêm vào giỏ hàng
                        </button>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="d-flex flex-column justify-content-between h-100">
                        <div>
                            @if($product->is_hot)
                            <div class="d-flex flex-wrap align-items-start mb-3">
                                <span class="badge text-bg-success fs-9 rounded-pill me-2 fw-semibold">#Sản phẩm nổi bật</span>
                            </div>
                            @endif
                            <h3 class="mb-3 lh-sm">{{ $product->name }}</h3>
                            <div class="d-flex flex-wrap align-items-center">
                                @if($variation = $product->variations->first())
                                    @if($variation->sale_price)
                                        <h1 class="me-3">{{ number_format($variation->sale_price) }}đ</h1>
                                        <p class="text-body-quaternary text-decoration-line-through fs-6 mb-0 me-3">
                                            {{ number_format($variation->price) }}đ
                                        </p>
                                        <p class="text-warning fw-bolder fs-6 mb-0">
                                            {{ round((1 - $variation->sale_price / $variation->price) * 100) }}% giảm
                                        </p>
                                    @else
                                        <h1 class="me-3">{{ number_format($variation->price) }}đ</h1>
                                    @endif
                                @endif
                            </div>
                            @if($variation && $variation->stock > 0)
                                <p class="text-success fw-semibold fs-7 mb-2">Còn hàng ({{ $variation->stock }})</p>
                            @else
                                <p class="text-danger fw-semibold fs-7 mb-2">Hết hàng</p>
                            @endif
                            <p class="mb-5 text-body-secondary">{{ $product->description }}</p>
                        </div>
                        
                        <div>
                            @if($product->variations->count() > 0)
                            <div class="mb-3">
                                <p class="fw-semibold mb-2 text-body">Phiên bản: </p>
                                <div class="d-flex align-items-center">
                                    <select class="form-select w-auto" id="variation-select">
                                        @foreach($product->variations as $variation)
                                            <option value="{{ $variation->id }}" 
                                                    data-price="{{ $variation->price }}"
                                                    data-sale-price="{{ $variation->sale_price }}"
                                                    data-stock="{{ $variation->stock }}">
                                                {{ $variation->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif

                            <div class="row g-3 g-sm-5 align-items-end">
                                <div class="col-12 col-sm">
                                    <p class="fw-semibold mb-2 text-body">Số lượng: </p>
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="d-flex flex-between-center" data-quantity="data-quantity">
                                            <button class="btn btn-phoenix-primary px-3" data-type="minus">
                                                <span class="fas fa-minus"></span>
                                            </button>
                                            <input class="form-control text-center input-spin-none bg-transparent border-0 outline-none"
                                                style="width:50px;" type="number" min="1" value="1" />
                                            <button class="btn btn-phoenix-primary px-3" data-type="plus">
                                                <span class="fas fa-plus"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Phần mô tả chi tiết -->
    <section class="py-0">
        <div class="container-small">
            <ul class="nav nav-underline fs-9 mb-4" id="productTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="description-tab" data-bs-toggle="tab" href="#tab-description" role="tab" aria-controls="tab-description" aria-selected="true">Mô tả</a>
                </li>
            </ul>
            <div class="tab-content" id="productTabContent">
                <div class="tab-pane pe-lg-6 pe-xl-12 fade show active text-body-emphasis" id="tab-description" role="tabpanel" aria-labelledby="description-tab">
                    {!! $product->description !!}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
