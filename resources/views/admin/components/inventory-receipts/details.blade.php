@extends('admin.master')
@section('title', $title ?? 'Chi tiết phiếu nhập kho')

@section('content')
    <div class="content">
        <nav class="mb-3" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route($route . '.index') }}">Phiếu nhập kho</a></li>
                <li class="breadcrumb-item active">{{ $title ?? 'Chi tiết phiếu nhập kho' }}</li>
            </ol>
        </nav>
        <div class="mb-9">
            <div class="d-sm-flex flex-between-center mb-3">
                <p class="text-body-secondary lh-sm mb-0 mt-2 mt-sm-0">Nhà cung cấp: <a class="fw-bold"
                        href="#!">{{ $receipt->supplier_name }}</a></p>
                <div class="d-flex">
                    <button class="btn btn-link pe-3 ps-0 text-body" onclick="window.print();"><span class="fas fa-print me-2"></span>In phiếu</button>
                    @if ($receipt->status != 'completed' && $receipt->status != 'cancelled')
                        <form action="{{ route($route . '.update-status', $receipt->id) }}" method="POST"
                            class="d-inline ms-1">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-link px-3 text-body"
                                onclick="return confirm('Bạn có chắc chắn muốn xác nhận phiếu nhập này không?')">
                                <span class="fas fa-check me-2"></span>Xác nhận phiếu
                            </button>
                        </form>
                    @endif
                    @if ($receipt->status != 'cancelled' && $receipt->status != 'completed')
                        <form action="{{ route($route . '.update-status', $receipt->id) }}" method="POST"
                            class="d-inline ms-1">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn btn-link px-3 text-danger"
                                onclick="return confirm('Bạn có chắc chắn muốn hủy phiếu nhập này không?')">
                                <span class="fas fa-times me-2"></span>Hủy phiếu
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="row g-5 gy-7">
                <div class="col-12 col-xl-8 col-xxl-9">
                    <div id="receiptTable"
                        data-list='{"valueNames":["products","quantity","unitcost","total"],"page":10}'>
                        <div class="table-responsive scrollbar">
                            <table class="table fs-9 mb-0 border-top border-translucent">
                                <thead>
                                    <tr>
                                        <th class="sort white-space-nowrap align-middle fs-10" scope="col"></th>
                                        <th class="sort white-space-nowrap align-middle" scope="col"
                                            style="min-width:400px;" data-sort="products">Sản phẩm</th>
                                        <th class="sort align-middle text-end ps-4" scope="col" data-sort="unitcost"
                                            style="width:150px;">Đơn giá</th>
                                        <th class="sort align-middle text-end ps-4" scope="col" data-sort="quantity"
                                            style="width:150px;">Số lượng</th>
                                        <th class="sort align-middle text-end ps-4" scope="col" data-sort="total"
                                            style="width:200px;">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody class="list" id="receipt-table-body">
                                    @foreach ($receipt->items as $item)
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
                                                class="products align-middle white-space-nowrap text-body-tertiary fw-semibold py-0 ps-4">
                                                @if ($item->productVariation && $item->productVariation->product)
                                                    <a href="{{ route('admin.products.edit', $item->productVariation->product->slug) }}" class="text-decoration-none">
                                                        {{ $item->productVariation->product->name }} - {{ $item->productVariation->name ?? 'N/A' }}
                                                    </a>
                                                @else
                                                    {{ $item->productVariation->name ?? 'N/A' }}
                                                @endif
                                            </td>
                                            <td class="unitcost align-middle text-body fw-semibold text-end py-0 ps-4">
                                                {{ number_format($item->unit_cost, 0, ',', '.') }} VND
                                            </td>
                                            <td class="quantity align-middle text-end py-0 ps-4 text-body-tertiary">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="total align-middle fw-bold text-body-highlight text-end py-0 ps-4">
                                                {{ number_format($item->subtotal, 0, ',', '.') }} VND
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex flex-between-center py-3 border-bottom border-translucent mb-6">
                        <p class="text-body-emphasis fw-semibold lh-sm mb-0">Tổng số sản phẩm:</p>
                        <p class="text-body-emphasis fw-bold lh-sm mb-0">{{ number_format($receipt->total_amount, 0, ',', '.') }} VND</p>
                    </div>
                    <div class="row gx-4 gy-6 g-xl-7 justify-content-sm-center justify-content-xl-start">
                        <div class="col-12 col-sm-auto">
                            <h4 class="mb-5">Thông tin nhà cung cấp</h4>
                            <div class="row g-4 flex-sm-column">
                                <div class="col-6 col-sm-12">
                                    <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="user"
                                            style="stroke-width:2.5;"></span>
                                        <h6 class="mb-0">Tên nhà cung cấp</h6>
                                    </div><a class="d-block fs-9 ms-4" href="#!">{{ $receipt->supplier_name }}</a>
                                </div>
                                <div class="col-6 col-sm-12">
                                    <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="phone"
                                            style="stroke-width:2.5;"> </span>
                                        <h6 class="mb-0">Liên hệ</h6>
                                    </div>
                                    @if($receipt->supplier_contact)
                                    <a class="d-block fs-9 ms-4"
                                        href="tel:{{ $receipt->supplier_contact }}">{{ $receipt->supplier_contact }}</a>
                                    @else
                                    <p class="mb-0 text-body-secondary fs-9 ms-4">Không có thông tin liên hệ</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-auto">
                            <h4 class="mb-5">Thông tin nhân viên</h4>
                            <div class="row g-4 flex-sm-column">
                                <div class="col-6 col-sm-12">
                                    <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="user"
                                            style="stroke-width:2.5;"> </span>
                                        <h6 class="mb-0">Nhân viên tạo phiếu</h6>
                                    </div>
                                    <p class="mb-0 text-body-secondary fs-9 ms-4">
                                        {{ $receipt->user ? $receipt->user->name : 'N/A' }}
                                    </p>
                                </div>
                                <div class="col-6 col-sm-12">
                                    <div class="d-flex align-items-center mb-1"><span class="me-2"
                                            data-feather="calendar" style="stroke-width:2.5;"></span>
                                        <h6 class="mb-0">Ngày tạo</h6>
                                    </div>
                                    <p class="mb-0 text-body-secondary fs-9 ms-4">
                                        {{ $receipt->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-auto">
                            <h4 class="mb-5">Thông tin bổ sung</h4>
                            <div class="row g-4 flex-sm-column">
                                <div class="col-6 col-sm-12">
                                    <div class="d-flex align-items-center mb-1"><span class="me-2"
                                            data-feather="hash" style="stroke-width:2.5;"></span>
                                        <h6 class="mb-0">Mã phiếu nhập</h6>
                                    </div>
                                    <p class="mb-0 text-body-secondary fs-9 ms-4">
                                        {{ $receipt->receipt_number }}
                                    </p>
                                </div>
                                <div class="col-6 col-sm-12">
                                    <div class="d-flex align-items-center mb-1"><span class="me-2"
                                            data-feather="file-text" style="stroke-width:2.5;"> </span>
                                        <h6 class="mb-0">Ghi chú</h6>
                                    </div>
                                    <p class="mb-0 text-body-secondary fs-9 ms-4">
                                        {{ $receipt->notes ?? 'Không có ghi chú' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-4 col-xxl-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h3 class="card-title mb-4">Tổng kết</h3>
                                    <div>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body fw-semibold">Tổng giá trị nhập:</p>
                                            <p class="text-body-emphasis fw-semibold">
                                                {{ number_format($receipt->total_amount, 0, ',', '.') }} VND</p>
                                        </div>
                                    </div>
                                    <div
                                        class="d-flex justify-content-between border-top border-translucent border-dashed pt-4">
                                        <h4 class="mb-0">Tổng cộng:</h4>
                                        <h4 class="mb-0">{{ number_format($receipt->total_amount, 0, ',', '.') }} VND</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title mb-4">Trạng thái phiếu nhập</h3>
                                    <h6 class="mb-2">Trạng thái hiện tại</h6>
                                    @php
                                        $statusText = '';
                                        $statusClass = '';
                                        switch ($receipt->status) {
                                            case 'pending':
                                                $statusText = 'Chờ xác nhận';
                                                $statusClass = 'warning';
                                                break;
                                            case 'completed':
                                                $statusText = 'Đã hoàn thành';
                                                $statusClass = 'success';
                                                break;
                                            case 'cancelled':
                                                $statusText = 'Đã hủy';
                                                $statusClass = 'danger';
                                                break;
                                        }
                                    @endphp
                                    <p class="mb-3"><span
                                            class="badge bg-{{ $statusClass }}">{{ $statusText }}</span></p>

                                    @if($receipt->status == 'pending')
                                    <div class="mt-4">
                                        <div class="d-grid gap-2">
                                            <form action="{{ route($route . '.update-status', $receipt->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="btn btn-success w-100"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xác nhận phiếu nhập này không?')">
                                                    <span class="fas fa-check me-2"></span>Xác nhận phiếu
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route($route . '.update-status', $receipt->id) }}" method="POST" class="mt-2">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-danger w-100"
                                                    onclick="return confirm('Bạn có chắc chắn muốn hủy phiếu nhập này không?')">
                                                    <span class="fas fa-times me-2"></span>Hủy phiếu
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 