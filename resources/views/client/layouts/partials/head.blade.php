<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>MANSO</title>
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('storage/logo/logo2.jpg') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/logo/logo2.jpg') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/logo/logo2.jpg') }}">
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/logo/logo2.jpg') }}">
<link rel="manifest" href="{{ asset('storage/logo/logo2.jpg') }}">
<meta name="msapplication-TileImage"
    content="{{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/favicons/mstile-150x150.png') }}">
<meta name="theme-color" content="#ffffff">
<script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/js/config.js') }}"></script>
<link rel="preconnect" href="https://fonts.googleapis.com/">
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap"
    rel="stylesheet">
<link href="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<link href="{{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/css/theme-rtl.min.css') }}" type="text/css"
    rel="stylesheet" id="style-rtl">
<link href="{{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/css/theme.min.css') }}" type="text/css"
    rel="stylesheet" id="style-default">
<link href="{{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/css/user-rtl.min.css') }}" type="text/css"
    rel="stylesheet" id="user-style-rtl">
<link href="{{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/css/user.min.css') }}" type="text/css"
    rel="stylesheet" id="user-style-default">
<script>
    var phoenixIsRTL = window.config.config.phoenixIsRTL;
    if (phoenixIsRTL) {
        var linkDefault = document.getElementById('style-default');
        var userLinkDefault = document.getElementById('user-style-default');
        linkDefault.setAttribute('disabled', true);
        userLinkDefault.setAttribute('disabled', true);
        document.querySelector('html').setAttribute('dir', 'rtl');
    } else {
        var linkRTL = document.getElementById('style-rtl');
        var userLinkRTL = document.getElementById('user-style-rtl');
        linkRTL.setAttribute('disabled', true);
        userLinkRTL.setAttribute('disabled', true);
    }
</script>
<link href="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/swiper/swiper-bundle.min.css') }}"
    rel="stylesheet">
