@extends('client.master')
@section('content')
    <section class="py-0 px-xl-3">
        <div class="container px-xl-0 px-xxl-3">
            <div class="row g-3 mb-9">
                <div class="col-12">
                    <div class="whooping-banner rounded-3 overflow-hidden" style="width: 100%; height: 475px;">
                        <div class="bg-holder z-n1 product-bg"
                            style="background-image:url({{ asset('storage/banner/banner.jpg') }});">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sản phẩm HOT -->
            @include('client.home.hot-product')

            <!-- Sản phẩm thường -->
            @include('client.home.normal-product')
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // ToastR setup
            toastr.options = {
                "closeButton": true,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        });
    </script>
@endpush
