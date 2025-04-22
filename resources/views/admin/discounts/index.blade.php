@extends('admin.layouts.master')

@section('content')
<div class="cr-main-content">
    <div class="container-fluid">
        <div class="cr-page-title cr-page-title-2">
            <div class="cr-breadcrumb">
                <h5>Quản lý mã giảm giá</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Carrot</a></li>
                    <li>Danh sách mã giảm giá</li>
                </ul>
            </div>
        </div>

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

        <div class="row">
            <div class="col-md-12">
                <div class="cr-card card-default">
                    <div class="cr-card-content">
                        <div class="d-flex justify-content-end mb-3">
                            <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">Thêm mã giảm giá</a>
                        </div>
                        <div class="table-responsive">
                            <table id="discount_list" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Mã</th>
                                        <th class="text-center">Loại</th>
                                        <th class="text-center">Giá trị giảm</th>
                                        <th class="text-center">Ngày bắt đầu</th>
                                        <th class="text-center">Ngày kết thúc</th>
                                        <th class="text-center">Đã sử dụng</th>
                                        <th class="text-center">Giới hạn sử dụng</th>
                                        <th class="text-center">Giới hạn/user</th>
                                        <th class="text-center">Công khai</th>
                                        <th class="text-center">Giá trị tối thiểu</th>
                                        <th class="text-center">Giảm tối đa</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($discounts as $discount)
                                    <tr>
                                        <td class="text-center align-middle">{{ $discount->code }}</td>
                                        <td class="text-center align-middle">
                                            @if($discount->type == 'percentage')
                                                Phần trăm
                                            @else
                                                Cố định
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($discount->type == 'percentage')
                                                {{ number_format($discount->sale) }}%
                                            @else
                                                {{ number_format($discount->sale) }} VNĐ
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">{{ $discount->startDate->format('d/m/Y H:i') }}</td>
                                        <td class="text-center align-middle">{{ $discount->endDate->format('d/m/Y H:i') }}</td>
                                        <td class="text-center align-middle">{{ $discount->usageCount }}</td>
                                        <td class="text-center align-middle">{{ $discount->maxUsage ?? 'Không giới hạn' }}</td>
                                        <td class="text-center align-middle">{{ $discount->user_limit ?? 'Không giới hạn' }}</td>
                                        <td class="text-center align-middle">
                                            @if($discount->is_public)
                                                <span class="badge bg-success">Có</span>
                                            @else
                                                <span class="badge bg-secondary">Không</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">{{ $discount->minOrderValue ? number_format($discount->minOrderValue) . ' VNĐ' : 'Không' }}</td>
                                        <td class="text-center align-middle">{{ $discount->maxDiscount ? number_format($discount->maxDiscount) . ' VNĐ' : 'Không' }}</td>
                                        <td class="text-center align-middle">
                                            @switch($discount->status)
                                                @case('active')
                                                    <span class="badge bg-success">Hoạt động</span>
                                                    @break
                                                @case('inactive')
                                                    <span class="badge bg-warning">Tạm dừng</span>
                                                    @break
                                                @case('expired')
                                                    <span class="badge bg-danger">Hết hạn</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center">
                                                <button type="button" class="ri-settings-3-line"
                                                    style="border: none; padding: 0 15px; font-size: 20px; background: none;"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false" data-display="static">
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('admin.discounts.edit', $discount) }}">Sửa</a>
                                                    <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="dropdown-item" type="submit"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')">Xóa</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="13" class="text-center">Không có mã giảm giá nào</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $discounts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection