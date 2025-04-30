<nav class="navbar navbar-vertical navbar-expand-lg" style="display:none;">
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <!-- scrollbar removed-->
        <div class="navbar-vertical-content">
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                <li class="nav-item">
                    <div class="nav-item-wrapper"><a class="nav-link label-1" href="{{ route('admin.dashboard') }}"
                            role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                        data-feather="home"></span></span><span class="nav-link-text-wrapper"><span
                                        class="nav-link-text">Dashboard</span></span>
                            </div>
                        </a></div><!-- parent pages-->
                    <div class="nav-item-wrapper">
                        <a class="nav-link dropdown-indicator label-1" href="#nv-CRM" role="button"
                            data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-CRM">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper"><span
                                        class="fas fa-caret-right dropdown-indicator-icon"></span></div><span
                                    class="nav-link-icon"><span data-feather="package"></span></span><span
                                    class="nav-link-text">Products</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-CRM">
                                <li class="collapsed-nav-item-title d-none">Products</li>
                                <li class="nav-item"><a class="nav-link"
                                        href="{{ route('admin.product-variations.index') }}">
                                        <div class="d-flex align-items-center"><span
                                                class="nav-link-text">Inventory</span></div>
                                    </a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.products.index') }}">
                                        <div class="d-flex align-items-center"><span
                                                class="nav-link-text">Products</span></div>
                                    </a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.attributes.index') }}">
                                        <div class="d-flex align-items-center"><span
                                                class="nav-link-text">Attributes</span></div>
                                    </a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.promotions.index') }}">
                                        <div class="d-flex align-items-center"><span
                                                class="nav-link-text">Promotions</span></div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div><!-- parent pages-->

                    <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1"
                            href="#nv-authentication" role="button" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="nv-authentication">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper"><span
                                        class="fas fa-caret-right dropdown-indicator-icon"></span></div><span
                                    class="nav-link-icon"><span data-feather="lock"></span></span><span
                                    class="nav-link-text">Authentication</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse"
                                id="nv-authentication">
                                <li class="collapsed-nav-item-title d-none">Authentication</li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.auth.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Users</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>



                    <div class="nav-item-wrapper"><a class="nav-link label-1"
                            href="{{ route('admin.categories.index') }}" role="button" data-bs-toggle=""
                            aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                        data-feather="calendar"></span></span><span class="nav-link-text-wrapper"><span
                                        class="nav-link-text">Categories</span></span>
                            </div>
                        </a></div><!-- parent pages-->

                    <!-- Orders menu -->
                    <div class="nav-item-wrapper">
                        <a class="nav-link dropdown-indicator label-1" href="#nv-orders" role="button"
                            data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-orders">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper"><span
                                        class="fas fa-caret-right dropdown-indicator-icon"></span></div><span
                                    class="nav-link-icon"><span data-feather="shopping-cart"></span></span><span
                                    class="nav-link-text">Orders</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-orders">
                                <li class="collapsed-nav-item-title d-none">Orders</li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.orders.index') }}">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">All
                                                Orders</span></div>
                                    </a>
                                </li>
                                <li class="nav-item"><a class="nav-link"
                                        href="{{ route('admin.orders.cancelled') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Cancellation Requests</span>
                                            <span class="badge bg-danger ms-2">New</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div><!-- parent pages-->

                    <!-- Inventory Receipts menu -->
                    <div class="nav-item-wrapper"><a class="nav-link label-1"
                            href="{{ route('admin.inventory-receipts.index') }}" role="button" data-bs-toggle=""
                            aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                        data-feather="truck"></span></span><span class="nav-link-text-wrapper"><span
                                        class="nav-link-text">Inventory Receipts</span></span>
                            </div>
                        </a></div><!-- parent pages-->
                </li>
            </ul>
        </div>
    </div>
</nav>
