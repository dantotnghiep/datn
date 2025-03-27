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

    <!--Javascript -->
    @include('client.layouts.partials.js')

    @stack('scripts')

    <!-- Đảm bảo có phần này để load scripts -->
    @yield('scripts')
</body>

<!-- Mirrored from demo-egenslab.b-cdn.net/html/eg-shop-fashion/v1/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 11 Jan 2025 13:59:07 GMT -->

</html>
