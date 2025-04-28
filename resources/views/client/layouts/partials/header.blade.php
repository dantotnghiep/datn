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
                                            <div class="mb-3 mx-3">
                                                <input class="form-control form-control-sm" id="statusUpdateInput" type="text" placeholder="Update your status" />
                                            </div>
                                        </div>
                                        <div class="overflow-auto scrollbar" style="height: 10rem;">
                                            <ul class="nav d-flex flex-column mb-2 pb-1">
                                                <li class="nav-item">
                                                    <a class="nav-link px-3 d-block" href="{{route('profile')}}">
                                                        <span class="me-2 text-body align-bottom" data-feather="user"></span>
                                                        <span>Profile</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link px-3 d-block" href="#!">
                                                        <span class="me-2 text-body align-bottom" data-feather="settings"></span>
                                                        Settings &amp; Privacy
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link px-3 d-block" href="#!">
                                                        <span class="me-2 text-body align-bottom" data-feather="help-circle"></span>
                                                        Help Center
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-footer p-0 border-top border-translucent">
                                            <div class="px-3 py-3">
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-phoenix-secondary d-flex flex-center w-100">
                                                        <span class="me-2" data-feather="log-out"></span>Sign out
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="my-2 text-center fw-bold fs-10 text-body-quaternary">
                                                <a class="text-body-quaternary me-1" href="#!">Privacy policy</a>
                                                &bull;
                                                <a class="text-body-quaternary mx-1" href="#!">Terms</a>
                                                &bull;
                                                <a class="text-body-quaternary ms-1" href="#!">Cookies</a>
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
                                                    <span class="me-2" data-feather="log-in"></span>Sign in
                                                </a>
                                                <a href="{{ route('register') }}" class="btn btn-phoenix-secondary d-flex flex-center w-100">
                                                    <span class="me-2" data-feather="user-plus"></span>Register
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
                            <form class="position-relative">
                                <input class="form-control search-input search form-control-sm" type="search"
                                    placeholder="Search" aria-label="Search" />
                                <span class="fas fa-search search-box-icon"></span>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div><!-- end of .container-->
</section>