<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from demo-egenslab.b-cdn.net/html/eg-shop-fashion/v1/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 11 Jan 2025 13:58:45 GMT -->

<head>
    <title>EG Shop Fashion - Multipurpose e-Commerce HTML Template 111111111111</title>

    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    @include('client.layouts.partials.css')
</head>

<body>

    @include('client.layouts.partials.lelf-navbar')
    <header>
        @include('client.layouts.partials.header')
    </header>
    <!-- ===============Navbar area end=============== -->
    @yield('content')

    @include('client.layouts.partials.footer')

    <!--Javascript -->
    @include('client.layouts.partials.js')
    <!-- JavaScript -->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css" />
    <!-- Default theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/default.min.css" />
    <!-- Semantic UI theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/semantic.min.css" />
    <!-- Bootstrap theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/bootstrap.min.css" />
    <script>
        function AddCart(id) {
            $.ajax({
                url: 'Add-Cart/' + id,
                type: 'GET',
            }).done(function(response) {
                RenderCart(response);
                alertify.success('Da them moi san pham');
            });
        }
        $("#change-item-cart").on("click", ".cart-product-delete-btn i", function(){
            $.ajax({
                url: 'Delete-Item-Cart/' + $(this).data("id"),
                type: 'GET',
            }).done(function(response) {
                RenderCart(response);
                alertify.success('Da xoa san pham');
            });
        });

        function RenderCart(response){
            $("#change-item-cart").empty();
                $("#change-item-cart").html(response);
                $("#total-quanty-show").text($("#total-quanty-cart").val());
                
        }

    </script>

</body>

<!-- Mirrored from demo-egenslab.b-cdn.net/html/eg-shop-fashion/v1/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 11 Jan 2025 13:59:07 GMT -->

</html>
