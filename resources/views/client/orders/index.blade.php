@extends('client.layouts.master')
@section('content')
    <div class="px-5 ml-110 mt-100">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">My Orders</h2>

                @if ($orders->isEmpty())
                    <div class="alert alert-info">
                        You haven't placed any orders yet.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Payment Method</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_code }}</td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>${{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $order->status_id == 1 ? 'warning' : ($order->status_id == 2 ? 'info' : ($order->status_id == 3 ? 'danger' : 'success')) }}">
                                                {{ $order->status->status_name ?? 'Processing' }}
                                            </span>
                                        </td>
                                        <td>{{ $order->payment_method }}</td>
                                        <td class="d-flex justify-content-start align-content-center">
                                            <form action="{{ route('orders.cancle', $order->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Do you want cancle order??')">Cancle</button>
                                            </form>
                                            <a href="{{ route('orders.show', $order->id) }}"
                                                class="btn btn-sm btn-primary ms-2">View Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{-- {{ $orders->links() }} --}}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
