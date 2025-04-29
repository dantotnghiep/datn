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
                <input type="text" class="form-control" name="province" value="{{ auth()->user()->province }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Quận/Huyện</label>
                <input type="text" class="form-control" name="district" value="{{ auth()->user()->district }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Phường/Xã</label>
                <input type="text" class="form-control" name="ward" value="{{ auth()->user()->ward }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Địa chỉ chi tiết</label>
                <input type="text" class="form-control" name="address" value="{{ auth()->user()->address }}" required>
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
              @endif
              <div class="d-flex justify-content-between mb-2">
                <h5 class="text-body fw-semibold">Phí vận chuyển: </h5>
                <h5 class="text-body fw-semibold">{{ number_format($shippingFee) }}đ</h5>
              </div>
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
@endsection