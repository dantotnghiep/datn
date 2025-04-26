<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from demo-egenslab.b-cdn.net/html/eg-shop-fashion/v1/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 11 Jan 2025 13:58:45 GMT -->

<head>
    <title>EG Shop Fashion - Multipurpose e-Commerce HTML Template 111111111111</title>

    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('client.layouts.partials.css')

    <!-- Đảm bảo Bootstrap CSS hiện diện -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Thêm Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>

    @include('client.layouts.partials.lelf-navbar')
    <header>
        @include('client.layouts.partials.header')
    </header>
    <!-- ===============Navbar area end=============== -->
    @yield('content')

    @include('client.layouts.partials.footer')

    <!-- jQuery (đảm bảo sẵn có trước khi các script khác chạy) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Đảm bảo Bootstrap JS hiện diện -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <!--Javascript -->
    @include('client.layouts.partials.js')

    @stack('scripts')

    <!-- Đảm bảo có phần này để load scripts -->
    @yield('scripts')
</body>

<!-- Mirrored from demo-egenslab.b-cdn.net/html/eg-shop-fashion/v1/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 11 Jan 2025 13:59:07 GMT -->

</html>