<!-----------------------------------------------------------------------------------
    Item Name: Carrot - Multipurpose eCommerce HTML Template.
    Author: ashishmaraviya
    Version: 2.1
    Copyright 2024
----------------------------------------------------------------------------------->
<!DOCTYPE html>
<html lang="en" dir="ltr">


<!-- Mirrored from maraviyainfotech.com/projects/carrot/carrot-v21/admin-html/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 11 Jan 2025 14:23:11 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="admin, dashboard, ecommerce, panel" />
    <meta name="description" content="Carrot - Admin.">
    <meta name="author" content="ashishmaraviya">

    <title>Carrot - Admin.</title>

    @include('admin.layouts.partials.css')

</head>

<body>
    <main class="wrapper sb-default ecom">
        <!-- Loader -->
        {{-- <div id="cr-overlay">
            <div class="loader"></div>
        </div> --}}

        <!-- Header -->
        <header class="cr-header">
            @include('admin.layouts.partials.header')
        </header>

        <!-- sidebar -->
        @include('admin.layouts.partials.sidebar')

        <!-- Notify sidebar -->
        @include('admin.layouts.partials.notify-sidebar')

        <!-- Main content -->
        @yield('content')

        <!-- Footer -->
        <footer>
            @include('admin.layouts.partials.footer')
        </footer>

        
    </main>

    @include('admin.layouts.partials.js')
</body>


<!-- Mirrored from maraviyainfotech.com/projects/carrot/carrot-v21/admin-html/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 11 Jan 2025 14:23:11 GMT -->

</html>
