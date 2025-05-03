@extends('admin.master')

@section('content')
<div class="content">
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom border-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Thống kê đơn hàng theo người dùng</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Người dùng</th>
                                    <th class="text-center">Tổng đơn hàng</th>
                                    <th class="text-center">Đơn thành công</th>
                                    <th class="text-center">Đơn hủy</th>
                                    <th class="text-end">Tổng chi tiêu</th>
                                    <th class="text-center">Đơn gần nhất</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userOrderStats as $user)
                                <tr>
                                    <td class="fw-bold">{{ $user->name }}</td>
                                    <td class="text-center">{{ $user->total_orders }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $user->completed_orders }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $user->cancelled_orders }}</span>
                                    </td>
                                    <td class="text-end">{{ number_format($user->total_spent, 0, ',', '.') }}đ</td>
                                    <td class="text-center">{{ date('d/m/Y', strtotime($user->last_order_date)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $userOrderStats->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
