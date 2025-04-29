<nav class="navbar navbar-vertical navbar-expand-lg" style="display:none;">
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <!-- scrollbar removed-->
        <div class="navbar-vertical-content">
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                <li class="nav-item">
                    <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-CRM"
                            role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-CRM">
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
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.product-variations.index') }}">
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
                            href="#nv-project-management" role="button" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="nv-project-management">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper"><span
                                        class="fas fa-caret-right dropdown-indicator-icon"></span></div><span
                                    class="nav-link-icon"><span data-feather="clipboard"></span></span><span
                                    class="nav-link-text">Project management</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse"
                                id="nv-project-management">
                                <li class="collapsed-nav-item-title d-none">Project management</li>
                                <li class="nav-item"><a class="nav-link" href="apps/project-management/create-new.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Create
                                                new</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link"
                                        href="apps/project-management/project-list-view.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Project
                                                list view</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link"
                                        href="apps/project-management/project-card-view.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Project
                                                card view</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link"
                                        href="apps/project-management/project-board-view.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Project
                                                board view</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/project-management/todo-list.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Todo
                                                list</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link"
                                        href="apps/project-management/project-details.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Project
                                                details</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                            </ul>
                        </div>
                    </div><!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link label-1" href="apps/gantt-chart.html"
                            role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                        class="fa-solid fa-chart-gantt "></span></span><span
                                    class="nav-link-text-wrapper"><span class="nav-link-text">Gantt
                                        chart</span></span><span
                                    class="badge ms-2 badge badge-phoenix badge-phoenix-warning nav-link-badge">new</span>
                            </div>
                        </a></div><!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-social"
                            role="button" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="nv-social">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper"><span
                                        class="fas fa-caret-right dropdown-indicator-icon"></span></div><span
                                    class="nav-link-icon"><span data-feather="share-2"></span></span><span
                                    class="nav-link-text">Social</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-social">
                                <li class="collapsed-nav-item-title d-none">Social</li>
                                <li class="nav-item"><a class="nav-link" href="apps/social/profile.html">
                                        <div class="d-flex align-items-center"><span
                                                class="nav-link-text">Profile</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/social/settings.html">
                                        <div class="d-flex align-items-center"><span
                                                class="nav-link-text">Settings</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                            </ul>
                        </div>
                    </div><!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-gallery"
                            role="button" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="nv-gallery">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper"><span
                                        class="fas fa-caret-right dropdown-indicator-icon"></span></div><span
                                    class="nav-link-icon"><span data-feather="image"></span></span><span
                                    class="nav-link-text">Gallery</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-gallery">
                                <li class="collapsed-nav-item-title d-none">Gallery</li>
                                <li class="nav-item"><a class="nav-link" href="apps/gallery/album.html">
                                        <div class="d-flex align-items-center"><span
                                                class="nav-link-text">Album</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/gallery/gallery-column.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Gallery
                                                column</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/gallery/gallery-grid.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Gallery
                                                grid</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/gallery/grid-with-title.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Grid with
                                                title</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/gallery/gallery-masonry.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Gallery
                                                masonry</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/gallery/gallery-slider.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Gallery
                                                slider</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                            </ul>
                        </div>
                    </div><!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1"
                            href="#nv-file-manager" role="button" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="nv-file-manager">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper"><span
                                        class="fas fa-caret-right dropdown-indicator-icon"></span></div><span
                                    class="nav-link-icon"><span data-feather="folder"></span></span><span
                                    class="nav-link-text">File manager</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse"
                                id="nv-file-manager">
                                <li class="collapsed-nav-item-title d-none">File manager</li>
                                <li class="nav-item"><a class="nav-link" href="apps/file-manager/grid-view.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Grid
                                                view</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/file-manager/list-view.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">List
                                                view</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                            </ul>
                        </div>
                    </div><!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link label-1" href="{{ route('admin.categories.index') }}"
                            role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                        data-feather="calendar"></span></span><span
                                    class="nav-link-text-wrapper"><span class="nav-link-text">Categories</span></span>
                            </div>
                        </a></div><!-- parent pages-->

                    <!-- Orders menu -->
                    <div class="nav-item-wrapper"><a class="nav-link label-1" href="{{ route('admin.orders.index') }}"
                            role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                        data-feather="shopping-cart"></span></span><span
                                    class="nav-link-text-wrapper"><span class="nav-link-text">Orders</span></span>
                            </div>
                        </a></div><!-- parent pages-->

                    <!-- Inventory Receipts menu -->
                    <div class="nav-item-wrapper"><a class="nav-link label-1" href="{{ route('admin.inventory-receipts.index') }}"
                            role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                        data-feather="truck"></span></span><span
                                    class="nav-link-text-wrapper"><span class="nav-link-text">Inventory Receipts</span></span>
                            </div>
                        </a></div><!-- parent pages-->
                </li>
            </ul>
        </div>
    </div>
</nav>
