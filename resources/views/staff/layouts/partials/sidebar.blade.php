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
                    <a href="#" class="cr-drop-toggle">
                        <i class="ri-dashboard-3-line"></i><span class="condense">Dashboard<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="cr-sb-drop condense">
                        <li><a href="index.html" class="cr-page-link drop"><i
                                    class="ri-checkbox-blank-circle-line"></i>Quản lí đơn hàng</a></li>
                        
                    </ul>
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
