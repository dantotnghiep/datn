<!-- App favicon -->
<link rel="shortcut icon" href="/be/assets/img/favicon/favicon.ico">

<!-- Icon CSS -->
<link href="/be/assets/css/vendor/materialdesignicons.min.css" rel="stylesheet">
<link href="/be/assets/css/vendor/remixicon.css" rel="stylesheet">
<link href="/be/assets/css/vendor/owl.carousel.min.css" rel="stylesheet">

<!-- Vendor CSS -->
<link href='/be/assets/css/vendor/datatables.bootstrap5.min.css' rel='stylesheet'>
<link href='/be/assets/css/vendor/responsive.datatables.min.css' rel='stylesheet'>
<link href='/be/assets/css/vendor/daterangepicker.css' rel='stylesheet'>
<link href="/be/assets/css/vendor/simplebar.css" rel="stylesheet">
<link href="/be/assets/css/vendor/bootstrap.min.css" rel="stylesheet">
<link href="/be/assets/css/vendor/apexcharts.css" rel="stylesheet">
<link href="/be/assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet">


<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/dist/tailwind.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/dist/tailwind.min.css" rel="stylesheet">

<!-- Main CSS -->
<link id="main-css" href="/be/assets/css/style.css" rel="stylesheet">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .menu-item {
    position: relative;
}

.submenu {
    list-style: none;
    position: absolute;
    left: 0;
    top: 100%;
    /* background-color: #cfcfcf; */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 0;
    margin: 0;
    display: none; /* Ẩn submenu ban đầu */
    z-index: 1000;
}

.submenu li {
    border-bottom: 1px solid #ddd;

}

.submenu li:last-child {
    border-bottom: none;
}

.submenu a {
    text-decoration: none;
    color: #333;
    padding: 10px 20px;
    display: block;
}

.submenu a:hover {
    /* background-color: #ffc7c7; */
    margin: 5px ;
    
    
}
.submenu {
    position: relative; /* Giữ nguyên submenu bên trong parent */
    left: 30px; /* Dịch submenu sang bên trái 30px */
    display: none; /* Ban đầu ẩn submenu */
}

.submenu.show {
    display: block; /* Hiển thị submenu khi được kích hoạt */
}
.drop-arrow {
    font-size: 12px; /* Kích thước của mũi tên */
    color: #ecf0f1; /* Màu sắc của mũi tên */
    margin-right: 5px; /* Khoảng cách với chữ */
    transition: transform 0.3s ease; /* Hiệu ứng xoay mũi tên */
}

.cr-page-link.active .drop-arrow {
    transform: rotate(180deg); /* Xoay mũi tên khi menu mở */
}
.drop-arrow {
    transition: transform 0.3s ease; /* Hiệu ứng mượt khi xoay */
}

.rotate-180 {
    transform: rotate(180deg); 
}




</style>

