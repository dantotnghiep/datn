<div class="cr-sidebar-overlay"></div>
<div class="cr-sidebar" data-mode="light">
    <div class="cr-sb-logo">
        <a href="{{ route('admin.dashboard') }}" class="sb-full"><img src="/be/assets/img/logo/full-logo.png"
                alt="logo"></a>
        <a href="{{ route('admin.dashboard') }}" class="sb-collapse"><img src="/be/assets/img/logo/collapse-logo.png"
                alt="logo"></a>
    </div>
    <div class="cr-sb-wrapper">
        <div class="cr-sb-content">
            <ul class="cr-sb-list">
                <li class="cr-sb-item sb-drop-item">
                    <a href="{{ route('admin.dashboard') }}" class="cr-drop-toggle">
                        <i class="ri-dashboard-3-line"></i><span class="condense">Bảng điều khiển<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="cr-sb-drop condense">
                        <li><a href="index.html" class="cr-page-link drop"><i
                                    class="ri-checkbox-blank-circle-line"></i>Thương mại điện tử</a></li>
                        <li><a href="{{ route('admin.product.product-list') }}" class="cr-page-link drop"><i
                                    class="ri-checkbox-blank-circle-line"></i>Danh sách sản phẩm</a></li>
                        <li class="menu-item">
                            <a href="#"
                                class="cr-page-link drop flex items-center justify-between px-4 py-2 text-gray-200 hover:bg-gray-700 rounded-md"
                                onclick="toggleSubmenu(event)">

                                Thêm sản phẩm
                            </a>
                            <!-- Submenu -->
                            <ul class="submenu hidden ml-6 mt-2 space-y-2 bg-gray-800 rounded-md ">
                                <li>
                                    <a href="{{ route('admin.product.create') }}"
                                        class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                        Thêm sản phẩm
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.attribute-values') }}"
                                        class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                        Giá trị thuộc tính
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li><a href="{{ route('hot-products.index') }}" class="cr-page-link drop"><i
                            class="ri-checkbox-blank-circle-line"></i>Cài đặt trang chủ</a></li>

                        <li><a href="{{ route('admin.category') }}" class="cr-page-link drop"><i
                                    class="ri-checkbox-blank-circle-line"></i>Thêm danh mục</a></li>
                        <li><a href="add-sub-category.html" class="cr-page-link drop"><i
                                    class="ri-checkbox-blank-circle-line"></i>Thêm danh mục con</a></li>
                        <li><a href="{{ route('admin.orders.index') }}" class="cr-page-link drop"><i
                                    class="ri-checkbox-blank-circle-line"></i>Danh sách đơn hàng</a></li>
                        <li class="cr-sb-item sb-subdrop-item">
                            <a href="javascript:void(0)" class="cr-sub-drop-toggle">
                                <i class="ri-shield-user-line"></i><span class="condense">Nhà cung cấp<i
                                        class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                            <ul class="cr-sb-subdrop condense">
                                <li><a href="vendor-profile.html" class="cr-page-link subdrop"><i
                                            class="ri-checkbox-blank-circle-line"></i>Hồ sơ</a></li>
                                <li><a href="vendor-update.html" class="cr-page-link subdrop"><i
                                            class="ri-checkbox-blank-circle-line"></i>Cập nhật nhà cung cấp</a></li>
                                <li><a href="vendor-list.html" class="cr-page-link subdrop"><i
                                            class="ri-checkbox-blank-circle-line"></i>Danh sách nhà cung cấp</a></li>
                                <li><a href="invoice.html" class="cr-page-link subdrop"><i
                                            class="ri-checkbox-blank-circle-line"></i>Hóa đơn</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="cr-sb-item-separator"></li>
                <li class="cr-sb-title condense">Trang</li>
                <li class="cr-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="cr-drop-toggle">
                        <i class="ri-pages-line"></i><span class="condense">Xác thực<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="cr-sb-drop condense">
                        <li><a href="signin.html" class="cr-page-link drop"><i
                                    class="ri-checkbox-blank-circle-line"></i></i>Đăng nhập</a></li>
                        <li><a href="signup.html" class="cr-page-link drop"><i
                                    class="ri-checkbox-blank-circle-line"></i>Đăng ký</a></li>
                        <li><a href="forgot.html" class="cr-page-link drop"><i
                                    class="ri-checkbox-blank-circle-line"></i>Quên mật khẩu</a></li>
                        <li><a href="two-factor.html" class="cr-page-link drop"><i
                                    class="ri-checkbox-blank-circle-line"></i>Xác thực hai yếu tố</a></li>
                        <li><a href="reset-password.html" class="cr-page-link drop"><i
                                    class="ri-checkbox-blank-circle-line"></i>Đặt lại mật khẩu</a></li>
                        <li><a href="remember.html" class="cr-page-link drop"><i
                                    class="ri-checkbox-blank-circle-line"></i>Nhớ tài khoản</a></li>
                    </ul>
                </li>
                <li class="cr-sb-item-separator"></li>
                <li class="cr-sb-title condense">Phần tử</li>
                <li class="cr-sb-item">
                    <a href="{{route('staff.dashboard')}}" class="cr-page-link">
                        <i class="ri-remixicon-line"></i><span class="condense"><span class="hover-title">Nhân viên</span></span></a>
                </li>
                <li class="cr-sb-item">
                    <a href="material-icons.html" class="cr-page-link">
                        <i class="mdi mdi-material-ui"></i><span class="condense"><span class="hover-title">Biểu tượng vật liệu</span></span></a>
                </li>
                <li class="cr-sb-item">
                    <a href="apexchart.html" class="cr-page-link">
                        <i class="ri-bar-chart-grouped-line"></i><span class="condense"><span
                                class="hover-title">Apexcharts</span></span></a>
                </li>
                <li class="cr-sb-item">
                    <a href="buttons.html" class="cr-page-link">
                        <i class="ri-radio-button-line"></i><span class="condense"><span
                                class="hover-title">Nút</span></span></a>
                </li>
                <li class="cr-sb-item">
                    <a href="accordions.html" class="cr-page-link">
                        <i class="ri-play-list-add-line"></i><span class="condense"><span
                                class="hover-title">Accordion</span></span></a>
                </li>
                <li class="cr-sb-item">
                    <a href="typography.html" class="cr-page-link">
                        <i class="ri-file-text-line"></i><span class="condense"><span
                                class="hover-title">Định dạng văn bản</span></span></a>
                </li>
                <li class="cr-sb-item">
                    <a href="alert-popup.html" class="cr-page-link">
                        <i class="ri-file-warning-line"></i><span class="condense"><span class="hover-title">Thông báo cảnh báo</span></span></a>
                </li>
            </ul>
        </div>
    </div>
</div>
<script>
    function toggleSubmenu(event) {
        event.preventDefault(); // Ngăn chặn hành động mặc định của liên kết
        const submenu = event.target.nextElementSibling; // Lấy submenu kế tiếp
        if (submenu && submenu.classList.contains('submenu')) {
            submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
        }
    }

    // Đóng tất cả menu con khi click bên ngoài
    document.addEventListener('click', function(event) {
        const menus = document.querySelectorAll('.submenu');
        menus.forEach(menu => {
            if (!menu.contains(event.target) && !menu.previousElementSibling.contains(event.target)) {
                menu.style.display = 'none';
            }
        });
    });
</script>
