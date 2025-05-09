@extends('admin.layouts.master')

@section('content')
<div class="cr-main-content">
    <div class="container-fluid">
        <div class="cr-page-title cr-page-title-2">
            <div class="cr-breadcrumb">
                <h5>Thêm mã giảm giá</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Carrot</a></li>
                    <li><a href="{{ route('admin.discounts.index') }}">Danh sách mã giảm giá</a></li>
                    <li>Thêm mới</li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="cr-card card-default">
                    <div class="cr-card-content">
                        <form action="{{ route('admin.discounts.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="code">Mã giảm giá</label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                               id="code" name="code" value="{{ old('code') }}" required>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="sale">Giá trị giảm (%)</label>
                                        <input type="number" class="form-control @error('sale') is-invalid @enderror"
                                               id="sale" name="sale" value="{{ old('sale') }}" required>
                                        @error('sale')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="startDate">Ngày bắt đầu</label>
                                        <input type="datetime-local" class="form-control @error('startDate') is-invalid @enderror"
                                               id="startDate" name="startDate" value="{{ old('startDate') }}" required>
                                        @error('startDate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="endDate">Ngày kết thúc</label>
                                        <input type="datetime-local" class="form-control @error('endDate') is-invalid @enderror"
                                               id="endDate" name="endDate" value="{{ old('endDate') }}" required>
                                        @error('endDate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="maxUsage">Giới hạn sử dụng</label>
                                        <input type="number" class="form-control @error('maxUsage') is-invalid @enderror"
                                               id="maxUsage" name="maxUsage" value="{{ old('maxUsage') }}">
                                        @error('maxUsage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="minOrderValue">Giá trị đơn hàng tối thiểu (VNĐ)</label>
                                        <input type="number" class="form-control @error('minOrderValue') is-invalid @enderror"
                                               id="minOrderValue" name="minOrderValue" value="{{ old('minOrderValue') }}">
                                        @error('minOrderValue')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="maxDiscount">Giảm giá tối đa (VNĐ)</label>
                                        <input type="number" class="form-control @error('maxDiscount') is-invalid @enderror"
                                               id="maxDiscount" name="maxDiscount" value="{{ old('maxDiscount') }}">
                                        @error('maxDiscount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group text-end">
                                <button type="submit" class="btn btn-primary">Tạo mã giảm giá</button>
                                <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection