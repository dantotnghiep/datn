@extends('client.master')
@section('content')
<div class="container-small">
    <nav class="mb-3" aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cart') }}">Giỏ hàng</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
      </ol>
    </nav>
    <h2 class="mb-5">Checkout</h2>
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row justify-content-between">
      <div class="col-lg-7 col-xl-7">
        <form action="{{ route('orders.store') }}" method="POST">
          @csrf
          <div class="d-flex align-items-end">
            <h3 class="mb-0 me-3">Thông tin giao hàng</h3>
            <button type="button" class="btn btn-outline-primary btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#addressModal">
              Thông tin địa chỉ
            </button>
          </div>
          <div class="card mt-4">
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label">Họ và tên người nhận</label>
                <input type="text" class="form-control" name="user_name" value="{{ auth()->user()->name }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" name="user_phone" value="{{ auth()->user()->phone }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Tỉnh/Thành phố</label>
                <input type="text" class="form-control" name="province" value="{{ $defaultLocation->province ?? auth()->user()->province }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Quận/Huyện</label>
                <input type="text" class="form-control" name="district" value="{{ $defaultLocation->district ?? auth()->user()->district }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Phường/Xã</label>
                <input type="text" class="form-control" name="ward" value="{{ $defaultLocation->ward ?? auth()->user()->ward }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Địa chỉ chi tiết</label>
                <input type="text" class="form-control" name="address" value="{{ $defaultLocation->address ?? auth()->user()->address }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Ghi chú</label>
                <textarea class="form-control" name="notes" rows="3"></textarea>
              </div>
            </div>
          </div>

          <hr class="my-6">
          <h3>Phương thức thanh toán</h3>
          <div class="card">
            <div class="card-body">
              <div class="row g-4">
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" id="bank" type="radio" name="payment_method" value="bank" checked>
                    <label class="form-check-label" for="bank">
                      Chuyển khoản ngân hàng
                    </label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" id="cod" type="radio" name="payment_method" value="cod">
                    <label class="form-check-label" for="cod">
                      Thanh toán khi nhận hàng (COD)
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row g-2 mt-4">
            <div class="col-md-8 col-lg-9 d-grid">
              <button class="btn btn-primary" type="submit">Đặt hàng - {{ number_format($total) }}đ</button>
            </div>
            <div class="col-md-4 col-lg-3 d-grid">
              <a href="{{ route('cart') }}" class="btn btn-phoenix-secondary text-nowrap">Quay lại giỏ hàng</a>
            </div>
          </div>
        </form>
      </div>
      <div class="col-lg-5 col-xl-4">
        <div class="card mt-3 mt-lg-0">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
              <h3 class="mb-0">Tóm tắt đơn hàng</h3>
            </div>
            <div class="border-dashed border-bottom border-translucent mt-4">
              <div class="ms-n2">
                @foreach($selectedItems as $item)
                <div class="row align-items-center mb-2 g-3">
                  <div class="col-8 col-md-7 col-lg-8">
                    <div class="d-flex align-items-center">
                      <img class="me-2 ms-1" src="{{ asset(optional($item->productVariation->product->images->first())->image_path ?? 'assets/img/products/default.png') }}" width="40" alt="" />
                      <h6 class="fw-semibold text-body-highlight lh-base">{{ $item->productVariation->product->name }}</h6>
                    </div>
                    <div class="text-muted small">
                      {{ $item->productVariation->attributeValues->map(function($attrVal) {
                          return ($attrVal->attribute ? $attrVal->attribute->name . ': ' : '') . $attrVal->value;
                      })->implode(' / ') }}
                    </div>
                  </div>
                  <div class="col-2 col-md-3 col-lg-2">
                    <h6 class="fs-10 mb-0">x{{ $item->quantity }}</h6>
                  </div>
                  <div class="col-2 ps-0">
                    <h5 class="mb-0 fw-semibold text-end">{{ number_format($item->total) }}đ</h5>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            <div class="border-dashed border-bottom border-translucent mt-4">
              <div class="d-flex justify-content-between mb-2">
                <h5 class="text-body fw-semibold">Tạm tính: </h5>
                <h5 class="text-body fw-semibold">{{ number_format($subtotal) }}đ</h5>
              </div>
              @if(isset($discount) && $discount > 0)
              <div class="d-flex justify-content-between mb-2">
                <h5 class="text-body fw-semibold">Giảm giá: </h5>
                <h5 class="text-danger fw-semibold">-{{ number_format($discount) }}đ</h5>
              </div>
              @if(session('applied_voucher'))
              <div class="d-flex justify-content-between mb-2">
                <h5 class="text-body">
                  <small>Discount: <span class="fw-semibold">{{ session('applied_voucher') }}</span></small>
                </h5>
              </div>
              @endif
              @endif
            </div>
            <div class="d-flex justify-content-between border-dashed-y pt-3">
              <h4 class="mb-0">Tổng cộng:</h4>
              <h4 class="mb-0">{{ number_format($total) }}đ</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Địa chỉ -->
  <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addressModalLabel">Địa chỉ giao hàng</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @if(auth()->user()->locations->count() > 0)
            <div class="row">
              @foreach(auth()->user()->locations as $location)
                <div class="col-md-6 mb-3">
                  <div class="card h-100 {{ $location->is_default ? 'border-primary' : '' }}">
                    <div class="card-body">
                      <h5 class="card-title">{{ auth()->user()->name }}</h5>
                      <p class="card-text mb-1">{{ $location->address }}</p>
                      <p class="card-text mb-1">{{ $location->ward }}, {{ $location->district }}</p>
                      <p class="card-text">{{ $location->province }}, {{ $location->country }}</p>
                      @if($location->is_default)
                        <span class="badge bg-primary">Mặc định</span>
                      @endif
                      <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-primary select-address"
                                data-address="{{ $location->address }}"
                                data-province="{{ $location->province }}"
                                data-district="{{ $location->district }}"
                                data-ward="{{ $location->ward }}">
                          Sử dụng địa chỉ này
                        </button>
                        @if(!$location->is_default)
                          <a href="{{ route('locations.set-default', $location->id) }}" class="btn btn-sm btn-outline-secondary">
                            Đặt làm mặc định
                          </a>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="alert alert-info">
              Bạn chưa có địa chỉ nào được lưu. Vui lòng nhập thông tin giao hàng để tự động lưu địa chỉ mới.
            </div>
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Xử lý khi chọn địa chỉ từ modal
      const selectButtons = document.querySelectorAll('.select-address');
      selectButtons.forEach(button => {
        button.addEventListener('click', function() {
          const address = this.getAttribute('data-address');
          const province = this.getAttribute('data-province');
          const district = this.getAttribute('data-district');
          const ward = this.getAttribute('data-ward');
          
          document.querySelector('input[name="address"]').value = address;
          document.querySelector('input[name="province"]').value = province;
          document.querySelector('input[name="district"]').value = district;
          document.querySelector('input[name="ward"]').value = ward;
          
          // Đóng modal
          const modal = bootstrap.Modal.getInstance(document.getElementById('addressModal'));
          modal.hide();
        });
      });

      // Thêm chức năng lấy thông tin địa chỉ chi tiết
      const locationDetailButtons = document.querySelectorAll('.get-location-detail');
      locationDetailButtons.forEach(button => {
        button.addEventListener('click', function() {
          const locationId = this.getAttribute('data-location-id');
          
          // Gọi API lấy thông tin địa chỉ
          fetch(`/locations/${locationId}`)
            .then(response => response.json())
            .then(data => {
              document.querySelector('input[name="address"]').value = data.address;
              document.querySelector('input[name="province"]').value = data.province;
              document.querySelector('input[name="district"]').value = data.district;
              document.querySelector('input[name="ward"]').value = data.ward;
            })
            .catch(error => console.error('Error fetching location:', error));
        });
      });
    });
  </script>
  @endpush
@endsection