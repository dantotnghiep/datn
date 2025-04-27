@extends('client.auth.master')

@section('content')

<div class="col-11 col-sm-10 col-xl-8">
    <div class="card border border-translucent auth-card">
        <div class="card-body pe-md-0">
            <div class="row align-items-center gx-0 gy-7">
                <div
                    class="col-auto bg-body-highlight dark__bg-gray-1100 rounded-3 position-relative overflow-hidden auth-title-box">
                    <div class="bg-holder" style="background-image:url({{asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/bg/38.png')}});">
                    </div>
                    <!--/.bg-holder-->
                    <div
                        class="position-relative px-4 px-lg-7 pt-7 pb-7 pb-sm-5 text-center text-md-start pb-lg-7 card-sign-up">
                        <h3 class="mb-3 text-body-emphasis fs-7">Phoenix Authentication</h3>
                        <p class="text-body-tertiary">Give yourself some hassle-free development process
                            with the uniqueness of Phoenix!</p>
                        <ul class="list-unstyled mb-0 w-max-content w-md-auto">
                            <li class="d-flex align-items-center"><span
                                    class="uil uil-check-circle text-success me-2"></span><span
                                    class="text-body-tertiary fw-semibold">Fast</span></li>
                            <li class="d-flex align-items-center"><span
                                    class="uil uil-check-circle text-success me-2"></span><span
                                    class="text-body-tertiary fw-semibold">Simple</span></li>
                            <li class="d-flex align-items-center"><span
                                    class="uil uil-check-circle text-success me-2"></span><span
                                    class="text-body-tertiary fw-semibold">Responsive</span></li>
                        </ul>
                    </div>
                    <div class="position-relative z-n1 mb-6 d-none d-md-block text-center mt-md-15"><img
                            class="auth-title-box-img d-dark-none"
                            src="{{asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/spot-illustrations/auth.png')}}" alt="" /><img
                            class="auth-title-box-img d-light-none"
                            src="{{asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/spot-illustrations/auth-dark.png')}}" alt="" />
                    </div>
                </div>
                <div class="col mx-auto">
                    <div class="auth-form-box">
                        <div class="text-center mb-7"><a
                                class="d-flex flex-center text-decoration-none mb-4"
                                href="{{ route('home') }}">
                                <div class="d-flex align-items-center fw-bolder fs-3 d-inline-block">
                                    <img src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/icons/logo.png') }}"
                                        alt="phoenix" width="58" /></div>
                            </a>
                            <h3 class="text-body-highlight">Sign Up</h3>
                            <p class="text-body-tertiary">Create your account today</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3 text-start"><label class="form-label"
                                    for="name">Name</label><input class="form-control @error('name') is-invalid @enderror"
                                    id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Name" />
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 text-start"><label class="form-label"
                                    for="email">Email address</label><input class="form-control @error('email') is-invalid @enderror"
                                    id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="name@example.com" />
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label" for="phone">Phone Number</label>
                                <input class="form-control @error('phone') is-invalid @enderror" id="phone" type="text" 
                                       name="phone" value="{{ old('phone') }}" required autocomplete="phone" placeholder="Phone number" />
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-sm-6"><label class="form-label"
                                        for="password">Password</label>
                                    <div class="position-relative">
                                        <input class="form-control @error('password') is-invalid @enderror"
                                            id="password" type="password" name="password" required autocomplete="new-password" placeholder="Password" />
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6"><label class="form-label"
                                        for="password-confirm">Confirm Password</label>
                                    <div class="position-relative">
                                        <input class="form-control" id="password-confirm" type="password" 
                                               name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-check mb-3"><input class="form-check-input"
                                    id="termsService" type="checkbox" required /><label
                                    class="form-label fs-9 text-transform-none" for="termsService">I
                                    accept the <a href="#!">terms </a>and <a
                                        href="#!">privacy policy</a></label></div><button
                                type="submit" class="btn btn-primary w-100 mb-3">Sign up</button>
                            <div class="text-center"><a class="fs-9 fw-bold" href="{{ route('login') }}">Sign
                                    in to an existing account</a></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection