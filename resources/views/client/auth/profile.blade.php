@extends('client.layouts.master')
@section('content')
    <div class="profile-wrapper ml-110 mt-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-xl-6 col-lg-8 col-md-10">
                    <div class="reg-login-forms">
                        <h4 class="reg-login-title text-center mb-4">
                            Your Profile
                        </h4>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="reg-input-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" 
                                       class="@error('name') is-invalid @enderror" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="reg-input-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" value="{{ auth()->user()->email }}" 
                                       class="form-control" disabled>
                            </div>

                            <div class="reg-input-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" 
                                       class="@error('phone') is-invalid @enderror">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="reg-input-group">
                                <label for="address">Address</label>
                                <input type="text" id="address" name="address" value="{{ old('address', auth()->user()->address) }}" 
                                       class="@error('address') is-invalid @enderror">
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="reg-input-group reg-submit-input d-flex align-items-center">
                                <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                            </div>
                        </form>

                        <div class="mt-4 text-center">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 