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
    <div class="row justify-content-between">
      <div class="col-lg-7 col-xl-7">
        <form>
          <div class="d-flex align-items-end">
            <h3 class="mb-0 me-3">Shipping Information</h3><button class="btn btn-link p-0" type="button">Edit</button>
          </div>
          <table class="table table-borderless mt-4">
            <tbody>
              <tr>
                <td class="py-2 ps-0">
                  <div class="d-flex"><span class="fs-3 me-2" data-feather="user" style="height:16px; width:16px;"> </span>
                    <h5 class="lh-sm me-4">Full Name</h5>
                  </div>
                </td>
                <td class="py-2 fw-bold lh-sm">:</td>
                <td class="py-2 px-3">
                  <h5 class="lh-sm fw-normal text-body-secondary">{{ auth()->user()->name }}</h5>
                </td>
              </tr>
              <tr>
                <td class="py-2 ps-0">
                  <div class="d-flex"><span class="fs-3 me-2" data-feather="home" style="height:16px; width:16px;"> </span>
                    <h5 class="lh-sm me-4">Address</h5>
                  </div>
                </td>
                <td class="py-2 fw-bold lh-sm">:</td>
                <td class="py-2 px-3">
                  <h5 class="lh-lg fw-normal text-body-secondary">{{ auth()->user()->address ?? 'Not updated' }}</h5>
                </td>
              </tr>
              <tr>
                <td class="py-2 ps-0">
                  <div class="d-flex"><span class="fs-3 me-2" data-feather="phone" style="height:16px; width:16px;"> </span>
                    <h5 class="lh-sm me-4">Phone Number</h5>
                  </div>
                </td>
                <td class="py-2 fw-bold lh-sm">: </td>
                <td class="py-2 px-3">
                  <h5 class="lh-sm fw-normal text-body-secondary">{{ auth()->user()->phone ?? 'Not updated' }}</h5>
                </td>
              </tr>
            </tbody>
          </table>
          <hr class="my-6">
          <h3>Payment Method</h3>
          <div class="row g-4 mb-7">
            <div class="col-12">
              <div class="row gx-lg-11">
                <div class="col-md-auto">
                  <div class="form-check">
                    <input class="form-check-input" id="creditCard" type="radio" name="paymentMethod" checked="checked" />
                    <label class="form-check-label fs-8 text-body text-nowrap d-flex gap-2" for="creditCard">
                      Credit Card
                      <img class="h-100" src="{{ asset('assets/img/logos/visa.png') }}" alt="" />
                      <img class="h-100" src="{{ asset('assets/img/logos/mastercard.png') }}" alt="" />
                    </label>
                  </div>
                </div>
                <div class="col-12 col-md-auto">
                  <div class="form-check">
                    <input class="form-check-input" id="cod" type="radio" name="paymentMethod" />
                    <label class="form-check-label fs-8 text-body" for="cod">Cash on Delivery (COD)</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row g-2 mb-5 mb-lg-0">
            <div class="col-md-8 col-lg-9 d-grid">
              <button class="btn btn-primary" type="submit">Pay {{ number_format($total) }}đ</button>
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
                <h5 class="text-body fw-semibold">Subtotal: </h5>
                <h5 class="text-body fw-semibold">{{ number_format($subtotal) }}đ</h5>
              </div>
              @if(isset($discount) && $discount > 0)
              <div class="d-flex justify-content-between mb-2">
                <h5 class="text-body fw-semibold">Discount: </h5>
                <h5 class="text-danger fw-semibold">-{{ number_format($discount) }}đ</h5>
              </div>
              @endif
              <div class="d-flex justify-content-between mb-2">
                <h5 class="text-body fw-semibold">Shipping Fee: </h5>
                <h5 class="text-body fw-semibold">{{ number_format($shippingFee) }}đ</h5>
              </div>
            </div>
            <div class="d-flex justify-content-between border-dashed-y pt-3">
              <h4 class="mb-0">Total:</h4>
              <h4 class="mb-0">{{ number_format($total) }}đ</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection