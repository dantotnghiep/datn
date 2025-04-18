<div class="container-fluid">
    <div class="cr-header-items">
        <div class="left-header">
            <a href="javascript:void(0)" class="cr-toggle-sidebar">
                <span class="outer-ring">
                    <span class="inner-ring"></span>
                </span>
            </a>
            <div class="header-search-box">
                <div class="header-search-drop">
                    <a href="javascript:void(0)" class="open-search"><i class="ri-search-line"></i></a>
                    <form class="cr-search">
                        <input class="search-input" type="text" placeholder="Tìm kiếm...">
                        <a href="javascript:void(0)" class="search-btn"><i class="ri-search-line"></i>
                        </a>
                    </form>
                </div>
            </div>
        </div>
        <div class="right-header">
            <div class="cr-right-tool cr-flag-drop language">
                <div class="cr-hover-drop">
                    <div class="cr-hover-tool">
                        <img class="flag" src="/be/assets/img/flag/us.png" alt="flag">
                    </div>
                    <div class="cr-hover-drop-panel right">
                        <ul>
                            <li><a href="javascript:void(0)"><img class="flag" src="/be/assets/img/flag/us.png" alt="flag">Tiếng Anh</a></li>
                            <li><a href="javascript:void(0)"><img class="flag" src="/be/assets/img/flag/in.png" alt="flag">Tiếng Hindi</a></li>
                            <li><a href="javascript:void(0)"><img class="flag" src="/be/assets/img/flag/de.png" alt="flag">Tiếng Đức</a></li>
                            <li><a href="javascript:void(0)"><img class="flag" src="/be/assets/img/flag/it.png" alt="flag">Tiếng Ý</a></li>
                            <li><a href="javascript:void(0)"><img class="flag" src="/be/assets/img/flag/jp.png" alt="flag">Tiếng Nhật</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="cr-right-tool apps">
                <div class="cr-hover-drop">
                    <div class="cr-hover-tool">
                        <i class="ri-apps-2-line"></i>
                    </div>
                    <div class="cr-hover-drop-panel right">
                        <h6 class="title">Ứng dụng</h6>
                        <ul>
                            <li><a href="javascript:void(0)"><img class="app" src="/be/assets/img/apps/1.png" alt="flag">Ứng dụng 1</a></li>
                            <li><a href="javascript:void(0)"><img class="app" src="/be/assets/img/apps/2.png" alt="flag">Ứng dụng 2</a></li>
                            <li><a href="javascript:void(0)"><img class="app" src="/be/assets/img/apps/3.png" alt="flag">Ứng dụng 3</a></li>
                            <li><a href="javascript:void(0)"><img class="app" src="/be/assets/img/apps/4.png" alt="flag">Ứng dụng 4</a></li>
                            <li><a href="javascript:void(0)"><img class="app" src="/be/assets/img/apps/5.png" alt="flag">Ứng dụng 5</a></li>
                            <li><a href="javascript:void(0)"><img class="app" src="/be/assets/img/apps/6.png" alt="flag">Ứng dụng 6</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="cr-right-tool display-screen">
                <a class="cr-screen full" href="javascript:void(0)"><i class="ri-fullscreen-line"></i></a>
                <a class="cr-screen reset" href="javascript:void(0)"><i class="ri-fullscreen-exit-line"></i></a>
            </div>
            <div class="cr-right-tool">
                <a class="cr-notify" href="javascript:void(0)">
                    <i class="ri-notification-2-line"></i>
                    <span class="label"></span>
                </a>
            </div>
            <div class="cr-right-tool display-dark">
                <a class="cr-mode dark" href="javascript:void(0)"><i class="ri-moon-clear-line"></i></a>
                <a class="cr-mode light" href="javascript:void(0)"><i class="ri-sun-line"></i></a>
            </div>
            <div class="cr-right-tool cr-user-drop">
                <div class="cr-hover-drop">
                    <div class="cr-hover-tool">
                        <img class="user" src="/be/assets/img/user/1.jpg" alt="user">
                    </div>
                    <div class="cr-hover-drop-panel right">
                        <div class="details">
                            <h6>Wiley Waites</h6>
                            <p>wiley@example.com</p>
                        </div>
                        <ul class="border-top">
                            <li><a href="team-profile.html">Hồ sơ</a></li>
                            <li><a href="faq.html">Trợ giúp</a></li>
                            <li><a href="chatapp.html">Tin nhắn</a></li>
                            <li><a href="project-overview.html">Dự án</a></li>
                            <li><a href="team-update.html">Cài đặt</a></li>
                        </ul>
                        <ul class="border-top">
                            <li>
                                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; padding: 0;">
                                        <i class="ri-logout-circle-r-line"></i> Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
