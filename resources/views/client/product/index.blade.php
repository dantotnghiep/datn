@extends('client.master')
@section('content')
    <div class="product-filter-container"><button class="btn btn-sm btn-phoenix-secondary text-body-tertiary mb-5 d-lg-none"
            data-phoenix-toggle="offcanvas" data-phoenix-target="#productFilterColumn"><span
                class="fa-solid fa-filter me-2"></span>Bộ lọc</button>

        @if(request('search'))
        <div class="mb-4">
            <div class="d-flex align-items-center">
                <h5 class="mb-0">Kết quả tìm kiếm: "{{ request('search') }}"</h5>
                <a href="{{ route('product.index', array_filter(request()->except('search'))) }}" class="btn btn-sm btn-phoenix-secondary ms-3">
                    <span class="fas fa-times"></span> Xóa tìm kiếm
                </a>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-lg-3 col-xxl-2 ps-2 ps-xxl-3">
                <div class="phoenix-offcanvas-filter bg-body scrollbar phoenix-offcanvas phoenix-offcanvas-fixed"
                    id="productFilterColumn" style="top: 92px" data-breakpoint="lg">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Bộ lọc</h3><button class="btn d-lg-none p-0"
                            data-phoenix-dismiss="offcanvas"><span class="uil uil-times fs-8"></span></button>
                    </div>

                    <!-- Danh mục -->
                    <a class="btn px-0 d-block collapse-indicator" data-bs-toggle="collapse" href="#collapseCategories"
                        role="button" aria-expanded="true" aria-controls="collapseCategories">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div class="fs-8 text-body-highlight">Danh mục</div><span
                                class="fa-solid fa-angle-down toggle-icon text-body-quaternary"></span>
                        </div>
                    </a>
                    <div class="collapse show" id="collapseCategories">
                        <div class="mb-2">
                            @foreach ($categories ?? [] as $cat)
                                <div class="form-check mb-0">
                                    <input class="form-check-input mt-0" id="category-{{ $cat->id }}" type="radio"
                                        name="category" value="{{ $cat->id }}"
                                        {{ isset($category) && $category->id == $cat->id ? 'checked' : '' }}
                                        onchange="window.location.href='{{ route('product.index', ['category' => $cat->id]) }}'">
                                    <label class="form-check-label d-block lh-sm fs-8 text-body fw-normal mb-0"
                                        for="category-{{ $cat->id }}">{{ $cat->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Sản phẩm nổi bật -->
                    <a class="btn px-0 d-block collapse-indicator" data-bs-toggle="collapse" href="#collapseFeatured"
                        role="button" aria-expanded="true" aria-controls="collapseFeatured">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div class="fs-8 text-body-highlight">Sản phẩm</div><span
                                class="fa-solid fa-angle-down toggle-icon text-body-quaternary"></span>
                        </div>
                    </a>
                    <div class="collapse show" id="collapseFeatured">
                        <div class="mb-2">
                            <div class="form-check mb-0">
                                <input class="form-check-input mt-0" id="featured" type="checkbox" name="featured"
                                    {{ request()->has('featured') ? 'checked' : '' }}
                                    onchange="window.location.href='{{ request()->has('featured')
                                        ? route('product.index', array_filter(request()->except('featured')))
                                        : route('product.index', array_merge(request()->except('page'), ['featured' => 1])) }}'">
                                <label class="form-check-label d-block lh-sm fs-8 text-body fw-normal mb-0"
                                    for="featured">Sản phẩm nổi bật</label>
                            </div>
                        </div>
                    </div>

                    <!-- Sắp xếp -->
                    <a class="btn px-0 d-block collapse-indicator" data-bs-toggle="collapse" href="#collapseSort"
                        role="button" aria-expanded="true" aria-controls="collapseSort">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div class="fs-8 text-body-highlight">Sắp xếp theo</div><span
                                class="fa-solid fa-angle-down toggle-icon text-body-quaternary"></span>
                        </div>
                    </a>
                    <div class="collapse show" id="collapseSort">
                        <div class="mb-2">
                            <div class="form-check mb-0">
                                <input class="form-check-input mt-0" id="latest" type="radio" name="sort"
                                    value="latest"
                                    {{ request('sort') == 'latest' || !request()->has('sort') ? 'checked' : '' }}
                                    onchange="window.location.href='{{ route('product.index', array_merge(request()->except('page', 'sort'), ['sort' => 'latest'])) }}'">
                                <label class="form-check-label d-block lh-sm fs-8 text-body fw-normal mb-0"
                                    for="latest">Mới nhất</label>
                            </div>
                            <div class="form-check mb-0">
                                <input class="form-check-input mt-0" id="price_asc" type="radio" name="sort"
                                    value="price_asc" {{ request('sort') == 'price_asc' ? 'checked' : '' }}
                                    onchange="window.location.href='{{ route('product.index', array_merge(request()->except('page', 'sort'), ['sort' => 'price_asc'])) }}'">
                                <label class="form-check-label d-block lh-sm fs-8 text-body fw-normal mb-0"
                                    for="price_asc">Giá tăng dần</label>
                            </div>
                            <div class="form-check mb-0">
                                <input class="form-check-input mt-0" id="price_desc" type="radio" name="sort"
                                    value="price_desc" {{ request('sort') == 'price_desc' ? 'checked' : '' }}
                                    onchange="window.location.href='{{ route('product.index', array_merge(request()->except('page', 'sort'), ['sort' => 'price_desc'])) }}'">
                                <label class="form-check-label d-block lh-sm fs-8 text-body fw-normal mb-0"
                                    for="price_desc">Giá giảm dần</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="phoenix-offcanvas-backdrop d-lg-none" data-phoenix-backdrop style="top: 92px"></div>
            </div>
            <div class="col-lg-9 col-xxl-10">
                <div class="row gx-3 gy-6 mb-8">
                    @forelse($products ?? [] as $product)
                        <div class="col-12 col-sm-6 col-md-4 col-xxl-3">
                            <div class="product-card-container h-100">
                                <div class="position-relative text-decoration-none product-card h-100">
                                    <div class="d-flex flex-column justify-content-between h-100">
                                        <div>
                                            <div
                                                class="border border-1 border-translucent rounded-3 position-relative mb-3">
                                                <img class="img-fluid" src="{{ $product->first_image }}"
                                                    alt="{{ $product->name }}" />

                                                @if ($product->is_hot)
                                                    <span class="badge text-bg-success fs-10 product-verified-badge">Nổi
                                                        bật
                                                        <span class="fas fa-check ms-1"></span>
                                                    </span>
                                                @endif
                                            </div>

                                            <a class="stretched-link"
                                                href="{{ route('product.detail', $product->slug) }}">
                                                <h6 class="mb-2 lh-sm line-clamp-3 product-name">{{ $product->name }}</h6>
                                            </a>
                                        </div>
                                        <div>
                                            @if ($product->min_price == $product->max_price)
                                                <h6 class="text-danger mb-0 fw-bold">
                                                    {{ number_format($product->min_price, 0, ',', '.') }}</h5>
                                                @else
                                                    <h6 class="text-danger mb-0 fw-bold">
                                                        {{ number_format($product->min_price, 0, ',', '.') }} -
                                                        {{ number_format($product->max_price, 0, ',', '.') }}</h5>
                                            @endif

                                            @if ($product->variations_count > 0)
                                                <p class="text-body-tertiary fw-semibold fs-9 lh-1 mb-0">
                                                    {{ $product->variations_count }} loại sản phẩm</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <h3>Không tìm thấy sản phẩm nào</h3>
                                <p>Vui lòng thử lại với bộ lọc khác</p>
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="d-flex justify-content-center">
                    {{ isset($products) ? $products->withQueryString()->links() : '' }}
                </div>
            </div>
        </div>
    </div><!-- end of .container-->
@endsection
