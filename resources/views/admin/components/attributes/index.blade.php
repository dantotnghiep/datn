@extends('admin.master')

@section('title', $title ?? 'List')

@section('content')
    <div class="content">
        <nav class="mb-3" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $title ?? ucfirst($route) }}</li>
            </ol>
        </nav>

        <div class="mb-9">
            <div class="row g-3 mb-4">
                <div class="col-auto">
                    <h2 class="mb-0">{{ $title ?? ucfirst($route) }}</h2>
                </div>
            </div>

            <div id="table-list" data-list='{"valueNames":["name"],"page":15,"pagination":false}'>
                <div class="mb-4">
                    <div class="d-flex flex-wrap gap-3">
                        <div class="search-box">
                            <form action="{{ route($route . '.index') }}" method="GET" class="position-relative">
                                <input type="hidden" name="trashed" value="{{ request()->get('trashed', 0) }}">
                                <input class="form-control search-input search" type="search" name="search"
                                    value="{{ request()->get('search') }}" placeholder="Search..." aria-label="Search" />
                                <span class="fas fa-search search-box-icon"></span>
                            </form>
                        </div>

                        <div class="ms-auto">
                            @if (request()->has('search') || request()->has('filter') || request()->has('sort'))
                                <a href="{{ route($route . '.index', ['trashed' => request()->get('trashed', 0)]) }}"
                                    class="btn btn-phoenix-secondary me-1">
                                    <span class="fas fa-times me-2"></span> Xóa bộ lọc
                                </a>
                            @endif

                            @if ($items->count() > 0 && method_exists($items->first(), 'trashed'))
                                <a href="{{ route($route . '.index', ['trashed' => request()->get('trashed') ? 0 : 1]) }}"
                                    class="btn btn-phoenix-secondary me-1">
                                    {{ request()->get('trashed') ? 'Xem thuộc tính' : 'Xem thùng rác' }}
                                </a>
                            @endif

                            <a href="{{ route($route . '.create') }}" class="btn btn-primary">
                                <span class="fas fa-plus me-2"></span> Thêm mới
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis border-y border-translucent">
                    <div class="table-responsive scrollbar">
                        <table class="table fs-9 mb-0">
                            <thead>
                                <tr>
                                    <th class="sort white-space-nowrap align-middle fs-9" scope="col">ID</th>
                                    <th class="sort white-space-nowrap align-middle fs-9" scope="col">Tên loại biến thể</th>
                                    <th class="sort white-space-nowrap align-middle fs-9" scope="col">Giá trị biến thể</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach($items as $item)
                                    <tr>
                                        <td class="align-middle">{{ $item->id }}</td>
                                        <td class="align-middle name">{{ $item->name }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                                @foreach($item->values as $value)
                                                    <span class="badge bg-secondary">{{ $value->value }}</span>
                                                @endforeach
                                                <button type="button"
                                                    class="badge bg-success border-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#addValueModal"
                                                    data-attribute-id="{{ $item->id }}"
                                                    data-attribute-name="{{ $item->name }}"
                                                    style="cursor: pointer;">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex flex-between-center pt-3 mb-3">
                        <div class="pagination d-none"></div>
                        <div class="mt-3">
                            {{ $items->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal thêm giá trị -->
    <div class="modal fade" id="addValueModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm giá trị cho <span id="attributeName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addValueForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="value" class="form-label">Giá trị</label>
                            <input type="text" class="form-control" id="value" name="value" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
    .badge.bg-success:hover {
        background-color: #28a745 !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('addValueModal');
    const form = document.getElementById('addValueForm');
    const attributeNameSpan = document.getElementById('attributeName');

    // Xử lý khi nút thêm được click
    document.querySelectorAll('[data-bs-target="#addValueModal"]').forEach(button => {
        button.addEventListener('click', function() {
            const attributeId = this.dataset.attributeId;
            const attributeName = this.dataset.attributeName;

            // Cập nhật form action và tên thuộc tính
            form.action = `{{ route('admin.attributes.values.store') }}`;
            attributeNameSpan.textContent = attributeName;

            // Thêm attribute_id vào form
            if (!form.querySelector('input[name="attribute_id"]')) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'attribute_id';
                input.value = attributeId;
                form.appendChild(input);
            } else {
                form.querySelector('input[name="attribute_id"]').value = attributeId;
            }
        });
    });

    // Reset form khi modal đóng
    modal.addEventListener('hidden.bs.modal', function() {
        form.reset();
    });
});
</script>
@endpush
