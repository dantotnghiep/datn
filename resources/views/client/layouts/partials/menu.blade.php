<nav class="navbar-responsive-navitems navbar-expand navbar-light bg-body-emphasis justify-content-between">
    <div class="container-small d-flex flex-between-center" data-navbar="data-navbar">
        <div class="dropdown"><button class="btn text-body ps-0 pe-5 text-nowrap dropdown-toggle dropdown-caret-none"
                data-category-btn="data-category-btn" data-bs-toggle="dropdown"><span class="fas fa-bars me-2"></span>Danh
                mục</button>
            <div class="dropdown-menu border border-translucent py-0 category-dropdown-menu">
                <div class="card border-0 scrollbar" style="max-height: 657px;">
                    <div class="card-body p-6 pb-3">
                        <div class="row gx-7 gy-5 mb-5">
                            @foreach ($categories->chunk(3) as $categoryChunk)
                                @foreach ($categoryChunk as $category)
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="text-primary me-2" data-feather="home"
                                                style="stroke-width:3;"></span>
                                            <h6 class="text-body-highlight mb-0 text-nowrap">{{ $category->name }}</h6>
                                        </div>
                                        <div class="ms-n2">
                                            @foreach ($category->products->take(4) as $product)
                                                <a class="text-body-emphasis d-block mb-1 text-decoration-none bg-body-highlight-hover px-2 py-1 rounded-2"
                                                    href="">{{ $product->name }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ul class="navbar-nav justify-content-end align-items-center">
            <li class="nav-item" data-nav-item="data-nav-item"><a class="nav-link ps-0 active"
                    href="{{ route('home') }}">Trang chủ</a></li>
            <li class="nav-item" data-nav-item="data-nav-item"><a class="nav-link" href="{{ route('product') }}">Sản
                    phẩm</a></li>
            <li class="nav-item" data-nav-item="data-nav-item"><a class="nav-link" href="{{ route('wishlist') }}">Yêu
                    thích</a></li>
        </ul>
    </div>
</nav>
