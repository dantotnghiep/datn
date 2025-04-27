<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">
<head>
   @include('client.layouts.partials.head')
</head>
<body>
    <main class="main" id="top">
        @include('client.layouts.partials.header')
        @include('client.layouts.partials.menu')
        <div class="ecommerce-homepage pt-5 mb-9">
            @yield('content')
        </div>
        @include('client.layouts.partials.footer')
    </main>
    @include('client.layouts.partials.scripts')
</body>

</html>
