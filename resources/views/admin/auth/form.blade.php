@extends('admin.master')

@section('title', isset($item) ? 'Edit User' : 'Create User')

@section('content')
    <div class="content">
        <nav class="mb-3" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route($route . '.index') }}">Users</a></li>
                <li class="breadcrumb-item active">{{ isset($item) ? 'Edit' : 'Create' }}</li>
            </ol>
        </nav>

        <div class="mb-9">
            <div class="row g-3 mb-4">
                <div class="col-auto">
                    <h2 class="mb-0">{{ isset($item) ? 'Edit User' : 'Create User' }}</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ isset($item) ? route($route . '.update', $item->id) : route($route . '.store') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @if (isset($item))
                                    @method('PUT')
                                @endif

                                <div class="row">
                                    <!-- Name -->
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', isset($item) ? $item->name : '') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', isset($item) ? $item->email : '') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', isset($item) ? $item->phone : '') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Role -->
                                    <div class="col-md-6 mb-3">
                                        <label for="role_id" class="form-label">Role</label>
                                        <select class="form-select @error('role_id') is-invalid @enderror"
                                            id="role_id" name="role_id">
                                            <option value="">Select Role</option>
                                            @foreach (\App\Models\UserRole::all() as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ old('role_id', isset($item) ? $item->role_id : '') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">
                                            {{ isset($item) ? 'New Password (leave blank to keep current)' : 'Password' }}
                                        </label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control"
                                            id="password_confirmation" name="password_confirmation">
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                                {{ old('is_active', isset($item) ? $item->is_active : '1') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Active</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary me-2">
                                        {{ isset($item) ? 'Update' : 'Create' }}
                                    </button>
                                    <a href="{{ route($route . '.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
