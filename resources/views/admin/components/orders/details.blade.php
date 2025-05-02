@extends('admin.master')
@section('title', $title ?? 'Order Details')

@section('content')
    <div class="content">
        <nav class="mb-3" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route($route . '.index') }}">Orders</a></li>
                <li class="breadcrumb-item active">{{ $title ?? 'Order Details' }}</li>
            </ol>
        </nav>
        <div class="mb-9">
            <div class="d-sm-flex flex-between-center mb-3">
                <p class="text-body-secondary lh-sm mb-0 mt-2 mt-sm-0">Customer: <a class="fw-bold"
                        href="#!">{{ $order->user_id }}</a></p>
                <div class="d-flex">
                    <button class="btn btn-link pe-3 ps-0 text-body"><span class="fas fa-print me-2"></span>Print</button>
                    @php
                        $orderStatus = $order->getRawOriginal('status_id') ?? 0;
                    @endphp
                    @if ($orderStatus != 4 && $orderStatus != 5)
                        <form action="{{ route($route . '.update-status', $order->id) }}" method="POST"
                            class="d-inline ms-1">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status_id" value="5">
                            <button type="submit" class="btn btn-link px-3 text-body"
                                onclick="return confirm('Are you sure you want to refund this order?')">
                                <span class="fas fa-undo me-2"></span>Refund
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="row g-5 gy-7">
                <div class="col-12 col-xl-8 col-xxl-9">
                    <div id="orderTable"
                        data-list='{"valueNames":["products","color","size","price","quantity","total"],"page":10}'>
                        <div class="table-responsive scrollbar">
                            <table class="table fs-9 mb-0 border-top border-translucent">
                                <thead>
                                    <tr>
                                        <th class="sort white-space-nowrap align-middle fs-10" scope="col"></th>
                                        <th class="sort white-space-nowrap align-middle" scope="col"
                                            style="min-width:400px;" data-sort="products">PRODUCTS</th>
                                        <th class="sort align-middle text-end ps-4" scope="col" data-sort="price"
                                            style="width:150px;">PRICE</th>
                                        <th class="sort align-middle text-end ps-4" scope="col" data-sort="quantity"
                                            style="width:200px;">QUANTITY</th>
                                        <th class="sort align-middle text-end ps-4" scope="col" data-sort="total"
                                            style="width:250px;">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody class="list" id="order-table-body">
                                    @foreach ($order->items as $item)
                                        <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                                            <td class="align-middle white-space-nowrap py-2">
                                                @if ($item->productVariation && $item->productVariation->product)
                                                    <a class="d-block border border-translucent rounded-2" href="#">
                                                        <img src="{{ $item->productVariation->product->first_image ?? asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/products/1.png') }}"
                                                            alt="{{ $item->productVariation->product->name }}"
                                                            width="53" />
                                                    </a>
                                                @else
                                                    <span
                                                        class="d-block border border-translucent rounded-2 text-center p-2">No
                                                        image</span>
                                                @endif
                                            </td>
                                            <td
                                                class="size align-middle white-space-nowrap text-body-tertiary fw-semibold py-0 ps-4">
                                                {{ $item->productVariation->name ?? 'N/A' }}
                                            </td>
                                            <td class="price align-middle text-body fw-semibold text-end py-0 ps-4">
                                                {{ number_format($item->price, 2) }}
                                            </td>
                                            <td class="quantity align-middle text-end py-0 ps-4 text-body-tertiary">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="total align-middle fw-bold text-body-highlight text-end py-0 ps-4">
                                                {{ number_format($item->total, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex flex-between-center py-3 border-bottom border-translucent mb-6">
                        <p class="text-body-emphasis fw-semibold lh-sm mb-0">Items subtotal :</p>
                        <p class="text-body-emphasis fw-bold lh-sm mb-0">{{ number_format($order->total, 2) }}</p>
                    </div>
                    <div class="row gx-4 gy-6 g-xl-7 justify-content-sm-center justify-content-xl-start">
                        <div class="col-12 col-sm-auto">
                            <h4 class="mb-5">Billing details</h4>
                            <div class="row g-4 flex-sm-column">
                                <div class="col-6 col-sm-12">
                                    <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="user"
                                            style="stroke-width:2.5;"></span>
                                        <h6 class="mb-0">Customer</h6>
                                    </div><a class="d-block fs-9 ms-4" href="#!">{{ $order->user_name }}</a>
                                </div>
                                <div class="col-6 col-sm-12 order-sm-1">
                                    <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="home"
                                            style="stroke-width:2.5;"></span>
                                        <h6 class="mb-0">Address</h6>
                                    </div>
                                    <div class="ms-4">
                                        <p class="text-body-secondary mb-0 fs-9">{{ $order->user_name }}</p>
                                        <p class="text-body-secondary mb-0 fs-9">{{ $order->address }}<br
                                                class="d-none d-sm-block" />{{ $order->ward }}, {{ $order->district }},
                                            {{ $order->province }}</p>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-12">
                                    <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="phone"
                                            style="stroke-width:2.5;"> </span>
                                        <h6 class="mb-0">Phone</h6>
                                    </div><a class="d-block fs-9 ms-4"
                                        href="tel:{{ $order->user_phone }}">{{ $order->user_phone }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-auto">
                            <h4 class="mb-5">Shipping details</h4>
                            <div class="row g-4 flex-sm-column">
                                <div class="col-6 col-sm-12">
                                    <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="phone"
                                            style="stroke-width:2.5;"> </span>
                                        <h6 class="mb-0">Phone</h6>
                                    </div><a class="d-block fs-9 ms-4"
                                        href="tel:{{ $order->user_phone }}">{{ $order->user_phone }}</a>
                                </div>
                                <div class="col-6 col-sm-12 order-sm-1">
                                    <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="home"
                                            style="stroke-width:2.5;"> </span>
                                        <h6 class="mb-0">Address</h6>
                                    </div>
                                    <div class="ms-4">
                                        <p class="text-body-secondary mb-0 fs-9">{{ $order->user_name }}</p>
                                        <p class="text-body-secondary mb-0 fs-9">{{ $order->address }}<br
                                                class="d-none d-sm-block" />{{ $order->ward }}, {{ $order->district }},
                                            {{ $order->province }}</p>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-12">
                                    <div class="d-flex align-items-center mb-1"><span class="me-2"
                                            data-feather="calendar" style="stroke-width:2.5;"></span>
                                        <h6 class="mb-0">Order Date</h6>
                                    </div>
                                    <p class="mb-0 text-body-secondary fs-9 ms-4">
                                        {{ $order->created_at->format('d M, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-auto">
                            <h4 class="mb-5">Other details</h4>
                            <div class="row g-4 flex-sm-column">
                                <div class="col-6 col-sm-12">
                                    <div class="d-flex align-items-center mb-1"><span class="me-2"
                                            data-feather="shopping-bag" style="stroke-width:2.5;"></span>
                                        <h6 class="mb-0">Payment Method</h6>
                                    </div>
                                    <p class="mb-0 text-body-secondary fs-9 ms-4">
                                        {{ $order->payment_method }}
                                    </p>
                                </div>
                                <div class="col-6 col-sm-12">
                                    <div class="d-flex align-items-center mb-1"><span class="me-2"
                                            data-feather="package" style="stroke-width:2.5;"> </span>
                                        <h6 class="mb-0">Payment Status</h6>
                                    </div>
                                    <p class="mb-0 text-body-secondary fs-9 ms-4">
                                        @if ($order->payment_status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($order->payment_status == 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($order->payment_status == 'failed')
                                            <span class="badge bg-danger">Failed</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-6 col-sm-12">
                                    <div class="d-flex align-items-center mb-1"><span class="me-2"
                                            data-feather="file-text" style="stroke-width:2.5;"> </span>
                                        <h6 class="mb-0">Order Notes</h6>
                                    </div>
                                    <p class="mb-0 text-body-secondary fs-9 ms-4">
                                        {{ $order->notes ?? 'No notes provided' }}</p>
                                </div>
                                @if ($order->paid_at)
                                    <div class="col-6 col-sm-12">
                                        <div class="d-flex align-items-center mb-1"><span class="me-2"
                                                data-feather="calendar" style="stroke-width:2.5;"> </span>
                                            <h6 class="mb-0">Payment Date</h6>
                                        </div>
                                        <p class="mb-0 text-body-secondary fs-9 ms-4">
                                            {{ $order->paid_at->format('d M, Y') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-4 col-xxl-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h3 class="card-title mb-4">Summary</h3>
                                    <div>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body fw-semibold">Items subtotal :</p>
                                            <p class="text-body-emphasis fw-semibold">
                                                {{ number_format($order->total, 2) }}</p>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body fw-semibold">Discount :</p>
                                            <p class="text-danger fw-semibold">-{{ number_format($order->discount, 2) }}
                                            </p>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body fw-semibold">Subtotal :</p>
                                            <p class="text-body-emphasis fw-semibold">
                                                {{ number_format($order->total_with_discount, 2) }}</p>
                                        </div>
                                    </div>
                                    <div
                                        class="d-flex justify-content-between border-top border-translucent border-dashed pt-4">
                                        <h4 class="mb-0">Total :</h4>
                                        <h4 class="mb-0">{{ number_format($order->total_with_discount, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title mb-4">Order Status</h3>
                                    <h6 class="mb-2">Current Status</h6>
                                    @php
                                        $currentStatus = $order->getRawOriginal('status_id');
                                        $statusText = '';
                                        $statusClass = '';
                                        switch ($currentStatus) {
                                            case 1:
                                                $statusText = 'Pending';
                                                $statusClass = 'warning';
                                                break;
                                            case 2:
                                                $statusText = 'Completed';
                                                $statusClass = 'success';
                                                break;
                                            case 3:
                                                $statusText = 'Shipping';
                                                $statusClass = 'info';
                                                break;
                                            case 4:
                                                $statusText = 'Cancelled';
                                                $statusClass = 'danger';
                                                break;
                                            case 5:
                                                $statusText = 'Refunded';
                                                $statusClass = 'secondary';
                                                break;
                                        }
                                    @endphp
                                    <p class="mb-3"><span
                                            class="badge bg-{{ $statusClass }}">{{ $statusText }}</span></p>

                                    <h6 class="mb-2">Payment Status</h6>
                                    <p class="mb-0">
                                        <span
                                            class="badge bg-{{ $order->payment_status == 'completed' ? 'success' : ($order->payment_status == 'failed' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer position-absolute">
            <div class="row g-0 justify-content-between align-items-center h-100">
                <div class="col-12 col-sm-auto text-center">
                    <p class="mb-0 mt-2 mt-sm-0 text-body">Thank you for creating with Phoenix<span
                            class="d-none d-sm-inline-block"></span><span
                            class="d-none d-sm-inline-block mx-1">|</span><br class="d-sm-none" />2025 &copy;<a
                            class="mx-1" href="https://themewagon.com/">Themewagon</a></p>
                </div>
                <div class="col-12 col-sm-auto text-center">
                    <p class="mb-0 text-body-tertiary text-opacity-85">v1.22.0</p>
                </div>
            </div>
        </footer>
    </div>
@endsection
