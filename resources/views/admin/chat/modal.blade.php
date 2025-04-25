@if ($room)
    <form id="form-validate" method="POST" action="{{ route('admin.chat.postEditUser', ['id' => $room->id]) }}">
        <div class="row ">
            <div class="col-12 order-1">
                <div class="cr-card mb-2">
                    <div class="cr-card-header">
                        <h5 class="cr-card-tile mb-0">Thông tin của {{ $room->user_id }}</h5>
                        <div class="basic-ratings jq-ry-container">

                        </div>
                    </div>
                    <div class="cr-card-content">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-6">
                                    <label class="form-label" for="ecommerce-product-name">Tên</label>
                                    <input type="text" class="form-control" placeholder="Tên" name="name"
                                        aria-label="Tên" value="{{ old('name', $room->name) }}" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-6">
                                    <label class="form-label" for="ecommerce-product-name">Số điện thoại</label>
                                    <input type="text" class="form-control" placeholder="Số điện thoại"
                                        name="Số điện thoại" aria-label="Số điện thoại"
                                        value="{{ old('phone', $room->phone) }}" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-6">
                                    <label class="form-label" for="ecommerce-product-name">Email</label>
                                    <input type="text" class="form-control" placeholder="Email" name="email"
                                        aria-label="Email" value="{{ old('email', $room->email) }}" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-6">
                                    <label class="form-label">Trạng thái hội thoại</label>
                                    <select id="status" class="select2 form-select"
                                        data-placeholder="Chọn Trạng thái hội thoại" name="status">
                                        <option value="" disabled>Chọn trạng thái hội thoại</option>
                                        <option value="0" {{ $room->status == 0 ? 'selected' : '' }}>Mới</option>
                                        <option value="1" {{ $room->status == 1 ? 'selected' : '' }}>Đang xử lý
                                        </option>
                                        <option value="2" {{ $room->status == 2 ? 'selected' : '' }}>Đợi phản hồi
                                        </option>
                                        <option value="3" {{ $room->status == 3 ? 'selected' : '' }}>Đang xử lý
                                            thêm</option>
                                        <option value="4" {{ $room->status == 4 ? 'selected' : '' }}>Đã giải quyết
                                        </option>
                                        <option value="5" {{ $room->status == 5 ? 'selected' : '' }}>Tạm dừng
                                        </option>
                                        <option value="6" {{ $room->status == 6 ? 'selected' : '' }}>Chưa giải
                                            quyết</option>
                                        <option value="7" {{ $room->status == 7 ? 'selected' : '' }}>Đã đóng
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="mb-6">
                                    <label class="form-label" for="ecommerce-product-name">Ghi chú</label>
                                    <textarea class="form-control" rows="3" name="note">{{ old('note', $room->note) }}
                                    </textarea>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex align-content-center flex-wrap gap-4">
                            @csrf
                            @method('PUT')
                            <button type="submit" id="save" class="btn btn-info">Lưu</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
@else
    <p class="text-center">Phòng chat không tồn tại</p>
@endif
