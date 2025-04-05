@extends('admin.layouts.master')

@section('content')


<div class="cr-main-content">
    <div class="container-fluid">

        <div class="container mt-5">
            <h1>Danh sách khách hàng</h1>
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Tổng đơn hoàn thành</th>
                        <th>Chi tiêu đã thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                    <tr>
                        <td>
                            <a href="{{ route('admin.users.clients.detail', $customer->id) }}">{{ $customer->name }}</a>
                        </td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->completed_orders }}</td>
                        <td>{{ number_format($customer->total_spent, 2) }} VNĐ</td>
                        <td>{{ $customer->status === 'active' ? 'Hoạt động' : 'Bị khóa' }}</td>
                        <td>
                            @if ($customer->status === 'active')
                            <form action="{{ route('admin.users.clients.lock', $customer->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn khóa tài khoản này?')">Khóa</button>
                            </form>
                            @else
                            <form action="{{ route('admin.users.clients.unlock', $customer->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Bạn có chắc muốn mở khóa tài khoản này?')">Mở khóa</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection