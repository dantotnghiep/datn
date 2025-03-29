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

                            <div class="reg-input-group reg-submit-input d-flex align-items-center">
                                <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                            </div>
                        </form>
<!-- Nút mở modal thêm địa chỉ -->
<div class="mt-5 text-center">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                Add New Address
                            </button>
                        </div>

                        <!-- Modal thêm địa chỉ -->
                        <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addAddressModalLabel">Add New Address</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('addresses.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="reg-input-group">
                                                <label for="recipient_name">Recipient Name *</label>
                                                <input type="text" id="recipient_name" name="recipient_name" value="{{ old('recipient_name') }}" 
                                                       class="@error('recipient_name') is-invalid @enderror" required>
                                                @error('recipient_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="reg-input-group">
                                                <label for="phone">Phone Number *</label>
                                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" 
                                                       class="@error('phone') is-invalid @enderror" required>
                                                @error('phone')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="reg-input-group">
                                                <label for="province">Province/City *</label>
                                                <input type="text" id="province" name="province" value="{{ old('province') }}" 
                                                       class="@error('province') is-invalid @enderror" required>
                                                @error('province')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="reg-input-group">
                                                <label for="district">District *</label>
                                                <input type="text" id="district" name="district" value="{{ old('district') }}" 
                                                       class="@error('district') is-invalid @enderror" required>
                                                @error('district')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="reg-input-group">
                                                <label for="ward">Ward *</label>
                                                <input type="text" id="ward" name="ward" value="{{ old('ward') }}" 
                                                       class="@error('ward') is-invalid @enderror" required>
                                                @error('ward')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="reg-input-group">
                                                <label for="street">Street/Building/House Number *</label>
                                                <input type="text" id="street" name="street" value="{{ old('street') }}" 
                                                       class="@error('street') is-invalid @enderror" required>
                                                @error('street')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="reg-input-group form-check">
                                                <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1">
                                                <label class="form-check-label" for="is_default">Set as Default Address</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Add Address</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Danh sách địa chỉ -->
                        <h5 class="mt-5">Your Addresses</h5>
                        @if(auth()->user()->addresses->isEmpty())
                            <p class="text-center">You have no addresses yet.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Recipient</th>
                                            <th>Address</th>
                                            <th>Default</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(auth()->user()->addresses as $address)
                                            <tr>
                                                <td>{{ $address->recipient_name }}</td>
                                                <td>{{ $address->street }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}</td>
                                                <td>
                                                    @if($address->is_default)
                                                        <span class="badge bg-success">Default</span>
                                                    @else
                                                        <form action="{{ route('addresses.set-default', $address) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-success">Set as Default</button>
                                                        </form>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editAddressModal{{ $address->id }}">
                                                        Edit
                                                    </button>
                                                    <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this address?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <!-- Modal chỉnh sửa địa chỉ -->
                                            <div class="modal fade" id="editAddressModal{{ $address->id }}" tabindex="-1" aria-labelledby="editAddressModalLabel{{ $address->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editAddressModalLabel{{ $address->id }}">Edit Address</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('addresses.update', $address) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="reg-input-group">
                                                                    <label for="recipient_name_{{ $address->id }}">Recipient Name *</label>
                                                                    <input type="text" id="recipient_name_{{ $address->id }}" name="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}" 
                                                                           class="@error('recipient_name') is-invalid @enderror" required>
                                                                    @error('recipient_name')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                                <div class="reg-input-group">
                                                                    <label for="phone_{{ $address->id }}">Phone Number *</label>
                                                                    <input type="text" id="phone_{{ $address->id }}" name="phone" value="{{ old('phone', $address->phone) }}" 
                                                                           class="@error('phone') is-invalid @enderror" required>
                                                                    @error('phone')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                                <div class="reg-input-group">
                                                                    <label for="province_{{ $address->id }}">Province/City *</label>
                                                                    <input type="text" id="province_{{ $address->id }}" name="province" value="{{ old('province', $address->province) }}" 
                                                                           class="@error('province') is-invalid @enderror" required>
                                                                    @error('province')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                                <div class="reg-input-group">
                                                                    <label for="district_{{ $address->id }}">District *</label>
                                                                    <input type="text" id="district_{{ $address->id }}" name="district" value="{{ old('district', $address->district) }}" 
                                                                           class="@error('district') is-invalid @enderror" required>
                                                                    @error('district')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                                <div class="reg-input-group">
                                                                    <label for="ward_{{ $address->id }}">Ward *</label>
                                                                    <input type="text" id="ward_{{ $address->id }}" name="ward" value="{{ old('ward', $address->ward) }}" 
                                                                           class="@error('ward') is-invalid @enderror" required>
                                                                    @error('ward')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                                <div class="reg-input-group">
                                                                    <label for="street_{{ $address->id }}">Street/Building/House Number *</label>
                                                                    <input type="text" id="street_{{ $address->id }}" name="street" value="{{ old('street', $address->street) }}" 
                                                                           class="@error('street') is-invalid @enderror" required>
                                                                    @error('street')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                                <div class="reg-input-group form-check">
                                                                    <input type="checkbox" class="form-check-input" id="is_default_{{ $address->id }}" name="is_default" value="1" {{ $address->is_default ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="is_default_{{ $address->id }}">Set as Default Address</label>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Update Address</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <!-- Nút đăng xuất -->
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