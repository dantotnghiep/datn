<section class="py-0">
    <div class="container-small">
        <div class="ecommerce-topbar">
            <nav class="navbar navbar-expand-lg navbar-light px-0">
                <div class="row gx-0 gy-2 w-100 flex-between-center">
                    <div class="col-auto"><a class="text-decoration-none" href="{{ route('home') }}">
                            <div class="d-flex align-items-center"><img
                                    src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/icons/logo.png') }}"
                                    alt="phoenix" width="27" />
                                <h5 class="logo-text ms-2">phoenix</h5>
                            </div>
                        </a></div>
                    <div class="col-auto order-md-1">
                        <ul class="navbar-nav navbar-nav-icons flex-row me-n2">
                            <li class="nav-item"><a
                                    class="nav-link px-2 icon-indicator icon-indicator-primary"
                                    href="{{route('cart')}}" role="button"><span class="text-body-tertiary"
                                        data-feather="shopping-cart"
                                        style="height:20px;width:20px;"></span>
                                    @if($cartCount > 0)
                                    <span class="icon-indicator-number">{{ $cartCount }}</span>
                                    @endif
                                </a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link px-2" id="navbarDropdownUser"
                                    href="#" role="button" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" aria-haspopup="true"
                                    aria-expanded="false">
                                    <span class="text-body-tertiary" data-feather="user" style="height:20px;width:20px;"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border mt-2"
                                    aria-labelledby="navbarDropdownUser">
                                    <div class="card position-relative border-0">
                                        @auth
                                        <div class="card-body p-0">
                                            <div class="text-center pt-4 pb-3">
                                                <div class="avatar avatar-xl ">
                                                    <img class="rounded-circle" src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/team/72x72/57.webp') }}" alt="" />
                                                </div>
                                                <h6 class="mt-2 text-body-emphasis">{{ Auth::user()->name }}</h6>
                                            </div>
                                        </div>
                                        <div class="overflow-auto scrollbar" style="height: 10rem;">
                                            <ul class="nav d-flex flex-column mb-2 pb-1">
                                                <li class="nav-item">
                                                    <a class="nav-link px-3 d-block" href="{{route('profile')}}">
                                                        <span class="me-2 text-body align-bottom" data-feather="user"></span>
                                                        <span>Tài khoản</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link px-3 d-block" href="{{route('client.order.list')}}">
                                                        <span class="me-2 text-body align-bottom" data-feather="shopping-bag"></span>
                                                        <span>Đơn hàng</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-footer p-0 border-top border-translucent">
                                            <div class="px-3 py-3">
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-phoenix-secondary d-flex flex-center w-100">
                                                        <span class="me-2" data-feather="log-out"></span>Đăng xuất
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        @else
                                        <div class="card-body p-0">
                                            <div class="text-center pt-4 pb-3">
                                                <div class="avatar avatar-xl">
                                                    <div class="avatar-name rounded-circle">
                                                        <span data-feather="user" style="height:40px;width:40px;"></span>
                                                    </div>
                                                </div>
                                                <h6 class="mt-2 text-body-emphasis">Welcome Guest!</h6>
                                            </div>
                                            <div class="p-3">
                                                <a href="{{ route('login') }}" class="btn btn-phoenix-primary d-flex flex-center w-100 mb-3">
                                                    <span class="me-2" data-feather="log-in"></span>Đăng nhập
                                                </a>
                                                <a href="{{ route('register') }}" class="btn btn-phoenix-secondary d-flex flex-center w-100">
                                                    <span class="me-2" data-feather="user-plus"></span>Đăng ký
                                                </a>
                                            </div>
                                        </div>
                                        @endauth
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="search-box ecommerce-search-box w-100">
                            <form class="position-relative" action="{{ route('product.index') }}" method="GET">
                                <input class="form-control search-input search form-control-sm" type="search"
                                    name="search" placeholder="Search" aria-label="Search" value="{{ request('search') }}" />
                                <button type="submit" class="fas fa-search search-box-icon border-0 bg-transparent"></button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div><!-- end of .container-->
</section>