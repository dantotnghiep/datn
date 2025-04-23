@extends('client.layouts.master')
@section('content')
    <div class="register-wrapper ml-110 mt-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="register-switcher text-center">
                        <a href="#" class="resister-btn">Xác thực tài khoản</a>
                    </div>
                </div>
            </div>
            <div class="row mt-100 justify-content-center">
                <div class="col-xxl-6 col-xl-6 col-lg-8 col-md-10">
                    <div class="reg-login-forms">
                        <h4 class="reg-login-title text-center">
                            Bạn cần xác thực tài khoản
                        </h4>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            {{-- Error --}}
                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif

                            {{-- Warning --}}
                            @if (session('warning'))
                                <div class="alert alert-warning" role="alert">
                                    {{ session('warning') }}
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    Một liên kết xác minh mới đã được gửi đến địa chỉ email của bạn.
                                </div>
                            @endif

                            Trước khi tiếp tục, vui lòng kiểm tra email của bạn để lấy liên kết xác minh.<br>
                            Nếu bạn không nhận được email,
                            <form class="d-inline" method="POST" action="{{ route('postVerify') }}">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">bấm vào đây để nhận lại
                                    email!</button>.
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===============  newslatter area start  =============== -->
    <div class="newslatter-area ml-110 mt-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="newslatter-wrap text-center">
                        <h5>Kết nối với EG</h5>
                        <h2 class="newslatter-title">Đăng ký nhận bản tin</h2>
                        <p>Chào bạn, đăng ký ngay để nhận áo thun phiên bản giới hạn miễn phí!</p>

                        <form action="#" method="POST">
                            <div class="newslatter-form">
                                <input type="text" placeholder="Nhập email của bạn">
                                <button type="submit">Gửi <i class="bi bi-envelope-fill"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ===============  newslatter area end  =============== -->
@endsection
