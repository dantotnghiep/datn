@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Order Details</h1>
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Order #{{ $order->order_code }}</h6>
            <div>
                <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST" class="d-flex align-items-center">
                    @csrf
                    <div class="form-group mr-2 mb-0">
                        <select name="status_id" class="form-control">
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ $order->status_id == $status->id ? 'selected' : '' }}>
                                    {{ $status->status_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Customer Information</h5>
                    <p><strong>Name:</strong> {{ $order->user_name }}</p>
                    <p><strong>Email:</strong> {{ $order->user_email }}</p>
                    <p><strong>Phone:</strong> {{ $order->user_phone }}</p>
                    <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Order Information</h5>
                    <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $order->status_id == 1 ? 'warning' : ($order->status_id == 2 ? 'info' : ($order->status_id == 3 ? 'danger' : 'success')) }}">
                            {{ $order->status->status_name }}
                        </span>
                    </p>
                    <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
                    @if($order->discount_code)
                    <p><strong>Discount Code:</strong> {{ $order->discount_code }} ({{ number_format($order->discount_amount, 2) }})</p>
                    @endif
                </div>
            </div>
            
            <h5>Order Items</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Variation</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->variation->product->name }}</td>
                            <td>
                                {{ $item->variation->name }}
                            </td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Subtotal</strong></td>
                            <td>${{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</td>
                        </tr>
                        @if($order->discount_amount > 0)
                        <tr>
                            <td colspan="4" class="text-right"><strong>Discount</strong></td>
                            <td>-${{ number_format($order->discount_amount, 2) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="4" class="text-right"><strong>Total</strong></td>
                            <td>${{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 