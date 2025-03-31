@extends('client.layouts.master')
@section('content')
<div class="checkout-area ml-110 mt-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="payment-success-wrap">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 48px;"></i>
                    <h2 class="mt-4">Thanh toán thành công!</h2>
                    <p class="mt-3">Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được xác nhận.</p>

                    @if(session('order_id'))
                    <p class="mt-2">Mã đơn hàng: #{{ session('order_id') }}</p>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('orders.index') }}" class="btn btn-primary">Xem đơn hàng</a>
                        <a href="{{ route('client.index') }}" class="btn btn-outline-primary ml-3">Tiếp tục mua sắm</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-success-wrap {
    padding: 40px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}

.btn {
    padding: 10px 25px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #4CAF50;
    border-color: #4CAF50;
}

.btn-primary:hover {
    background-color: #45a049;
    border-color: #45a049;
}

.btn-outline-primary {
    color: #4CAF50;
    border-color: #4CAF50;
}

.btn-outline-primary:hover {
    background-color: #4CAF50;
    border-color: #4CAF50;
    color: white;
}

.ml-3 {
    margin-left: 1rem;
}

.text-success {
    color: #4CAF50 !important;
}

@media (max-width: 768px) {
    .checkout-area {
        margin-left: 0;
        margin-top: 50px;
    }

    .btn {
        display: block;
        width: 100%;
        margin: 10px 0;
    }

    .ml-3 {
        margin-left: 0;
    }
}
</style>
@endsection