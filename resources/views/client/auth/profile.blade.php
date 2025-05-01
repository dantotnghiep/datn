@extends('client.master ')

@section('content')
    <div class="container-small">
      <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Profile</li>
        </ol>
      </nav>
      <div class="row align-items-center justify-content-between g-3 mb-4">
        <div class="col-auto">
          <h2 class="mb-0">Profile</h2>
        </div>
        <div class="col-auto">
          <div class="row g-2 g-sm-3">
            <div class="col-auto"><button class="btn btn-phoenix-secondary"><span class="fas fa-key me-2"></span>Reset password</button></div>
          </div>
        </div>
      </div>

      @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      <div class="row g-3 mb-6">
        <div class="col-12 col-lg-8">
          <div class="card h-100">
            <div class="card-body">
              <div class="border-bottom border-dashed pb-4">
                <div class="row align-items-center g-3 g-sm-5 text-center text-sm-start">
                  <div class="col-12 col-sm-auto"><input class="d-none" id="avatarFile" type="file" />
                    <label class="cursor-pointer avatar avatar-5xl" for="avatarFile">
                      <img class="rounded-circle" src="{{ asset('assets/img/team/15.webp') }}" alt="" />
                    </label>
                  </div>
                  <div class="col-12 col-sm-auto flex-1">
                    <h3>{{ $user->name }}</h3>
                    <p class="text-body-secondary">Joined {{ $user->created_at->diffForHumans() }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-4">
          <div class="card h-100">
            <div class="card-body">
              <div class="border-bottom border-dashed">
                <h4 class="mb-3">Contact Information</h4>
              </div>
              <div class="border-top border-dashed pt-4">
                <div class="row flex-between-center mb-2">
                  <div class="col-auto">
                    <h5 class="text-body-highlight mb-0">Email</h5>
                  </div>
                  <div class="col-auto"><a class="lh-1" href="mailto:{{ $user->email }}">{{ $user->email }}</a></div>
                </div>
                <div class="row flex-between-center">
                  <div class="col-auto">
                    <h5 class="text-body-highlight mb-0">Phone</h5>
                  </div>
                  <div class="col-auto"><a href="tel:{{ $user->phone }}">{{ $user->phone ?? 'Not provided' }}</a></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div>
        <div class="scrollbar">
          <ul class="nav nav-underline fs-9 flex-nowrap mb-3 pb-1" id="myTab" role="tablist">
            <li class="nav-item"><a class="nav-link text-nowrap active" id="personal-info-tab" data-bs-toggle="tab" href="#tab-personal-info" role="tab" aria-controls="tab-personal-info" aria-selected="true"><span class="fas fa-user me-2"></span>Personal info</a></li>
            <li class="nav-item"><a class="nav-link text-nowrap" id="password-tab" data-bs-toggle="tab" href="#tab-password" role="tab" aria-controls="tab-password" aria-selected="false"><span class="fas fa-lock me-2"></span>Password</a></li>
          </ul>
        </div>
        <div class="tab-content" id="profileTabContent">
          <!-- Personal Info Tab -->
          <div class="tab-pane fade show active" id="tab-personal-info" role="tabpanel" aria-labelledby="personal-info-tab">
            <form action="{{ route('profile.update') }}" method="POST">
              @csrf
              <div class="row gx-3 gy-4 mb-5">
                <div class="col-12 col-lg-6">
                  <label class="form-label text-body-highlight fs-8 ps-0 text-capitalize lh-sm" for="name">Full name</label>
                  <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" placeholder="Full name" value="{{ old('name', $user->name) }}" />
                  @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-12 col-lg-6">
                  <label class="form-label text-body-highlight fs-8 ps-0 text-capitalize lh-sm" for="email">Email</label>
                  <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" placeholder="Email" value="{{ old('email', $user->email) }}" />
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-12 col-lg-6">
                  <label class="form-label text-body-highlight fw-bold fs-8 ps-0 text-capitalize lh-sm" for="phone">Phone</label>
                  <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" type="text" placeholder="+1234567890" value="{{ old('phone', $user->phone) }}" />
                  @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-primary px-7">Save changes</button>
              </div>
            </form>
          </div>

          <!-- Password Tab -->
          <div class="tab-pane fade" id="tab-password" role="tabpanel" aria-labelledby="password-tab">
            <form action="{{ route('profile.update-password') }}" method="POST">
              @csrf
              <div class="row gx-3 gy-4 mb-5">
                <div class="col-12 col-lg-6">
                  <label class="form-label text-body-highlight fs-8 ps-0 text-capitalize lh-sm" for="current_password">Current Password</label>
                  <input class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" type="password" />
                  @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-12 col-lg-6"></div>
                <div class="col-12 col-lg-6">
                  <label class="form-label text-body-highlight fs-8 ps-0 text-capitalize lh-sm" for="password">New Password</label>
                  <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" />
                  @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-12 col-lg-6">
                  <label class="form-label text-body-highlight fs-8 ps-0 text-capitalize lh-sm" for="password_confirmation">Confirm New Password</label>
                  <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" />
                </div>
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-primary px-7">Update Password</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- end of .container-->
@endsection
