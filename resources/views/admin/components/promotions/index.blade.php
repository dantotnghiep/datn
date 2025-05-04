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

                        <!-- Filters -->
                        <div class="scrollbar overflow-hidden-y">
                            <div class="btn-group position-static" role="group">
                                @foreach ($fields as $field => $options)
                                    @if (isset($options['filterable']) && $options['filterable'])
                                        <div class="btn-group position-static text-nowrap">
                                            <button class="btn btn-phoenix-secondary px-4 flex-shrink-0" type="button"
                                                data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true"
                                                aria-expanded="false">
                                                {{ $options['label'] ?? ucfirst($field) }}
                                                <span class="fas fa-angle-down ms-2"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item"
                                                        href="{{ route($route . '.index', ['trashed' => request()->get('trashed', 0)]) }}">Tất
                                                        cả</a>
                                                </li>
                                                @foreach ($options['filter_options'] ?? [] as $value => $label)
                                                    <li>
                                                        <a class="dropdown-item {{ request()->input("filter.$field") == $value ? 'active' : '' }}"
                                                            href="{{ route($route . '.index', array_merge(request()->except('page'), ['filter' => [$field => $value], 'trashed' => request()->get('trashed', 0)])) }}">
                                                            {{ $label }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endforeach

                                <!-- Sort Dropdown -->
                                <div class="btn-group position-static text-nowrap">
                                    <button class="btn btn-phoenix-secondary px-4 flex-shrink-0" type="button"
                                        data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true"
                                        aria-expanded="false">
                                        Sắp xếp theo
                                        <span class="fas fa-angle-down ms-2"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item"
                                                href="{{ route($route . '.index', ['trashed' => request()->get('trashed', 0)]) }}">Mặc
                                                định</a>
                                        </li>
                                        @foreach ($fields as $field => $options)
                                            @if (!isset($options['sortable']) || $options['sortable'])
                                                <li>
                                                    <a class="dropdown-item {{ request()->get('sort') == $field . '_asc' ? 'active' : '' }}"
                                                        href="{{ route($route . '.index', array_merge(request()->except('page'), ['sort' => $field . '_asc', 'trashed' => request()->get('trashed', 0)])) }}">
                                                        {{ $options['label'] ?? ucfirst($field) }} (A-Z)
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item {{ request()->get('sort') == $field . '_desc' ? 'active' : '' }}"
                                                        href="{{ route($route . '.index', array_merge(request()->except('page'), ['sort' => $field . '_desc', 'trashed' => request()->get('trashed', 0)])) }}">
                                                        {{ $options['label'] ?? ucfirst($field) }} (Z-A)
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
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
                                    {{ request()->get('trashed') ? 'Xem mã khuyến mãi' : 'Xem thùng rác' }}
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
                                    @foreach ($fields as $field => $options)
                                        <th class="sort white-space-nowrap align-middle fs-9" scope="col">
                                            {{ $options['label'] ?? ucfirst($field) }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach ($items as $item)
                                    <tr>
                                        @foreach ($fields as $field => $options)
                                            <td class="align-middle name">
                                                @if ($field == 'image')
                                                    <img src="{{ $item->first_image }}" alt="{{ $item->name }}"
                                                        width="40" height="40" class="rounded object-fit-cover">
                                                @elseif (isset($options['formatter']) && is_callable($options['formatter']))
                                                    {!! $options['formatter']($item->$field, $item) !!}
                                                @elseif ($field == 'is_active')
                                                    @if ($item->is_active)
                                                        <span class="badge bg-success">Có</span>
                                                    @else
                                                        <span class="badge bg-danger">Không</span>
                                                    @endif
                                                @elseif ($field == 'discount_type')
                                                    @if ($item->discount_type == 'percentage')
                                                        <span class="badge bg-success">Phần trăm</span>
                                                    @else
                                                        <span class="badge bg-danger">Giá trị</span>
                                                    @endif
                                                @elseif ($field == 'status')
                                                    @if ($item->status == 'pending')
                                                        <form action="{{ route($route . '.update-status', $item->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit" class="btn btn-sm btn-success me-1"
                                                                title="Mark as Completed">
                                                                <span class="fas fa-check me-1"></span>Complete
                                                            </button>
                                                        </form>
                                                        <form action="{{ route($route . '.update-status', $item->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="cancelled">
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                title="Mark as Cancelled">
                                                                <span class="fas fa-times me-1"></span>Cancel
                                                            </button>
                                                        </form>
                                                    @elseif ($item->status == 'completed')
                                                        <span class="badge bg-success">Completed</span>
                                                        <form action="{{ route($route . '.update-status', $item->id) }}"
                                                            method="POST" class="d-inline ms-1">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="pending">
                                                            <button type="submit" class="btn btn-sm btn-warning"
                                                                title="Revert to Pending">
                                                                <span class="fas fa-undo me-1"></span>Revert
                                                            </button>
                                                        </form>
                                                    @elseif ($item->status == 'cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                        <form action="{{ route($route . '.update-status', $item->id) }}"
                                                            method="POST" class="d-inline ms-1">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="pending">
                                                            <button type="submit" class="btn btn-sm btn-warning"
                                                                title="Revert to Pending">
                                                                <span class="fas fa-undo me-1"></span>Revert
                                                            </button>
                                                        </form>
                                                    @endif
                                                @else
                                                    {{ $item->$field }}
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="align-middle text-center">
                                            @if (method_exists($item, 'trashed') && $item->trashed())
                                                <form action="{{ route($route . '.restore', $item->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm p-0 text-success me-2"
                                                        title="Restore">
                                                        <span class="fas fa-trash-restore"></span>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route($route . '.destroy', $item->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm p-0 text-danger"
                                                        title="Move to Trash"
                                                        onclick="return confirm('Are you sure you want to move this item to trash?')">
                                                        <span class="fas fa-trash-alt"></span>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex flex-between-center pt-3 mb-3">
                        <div class="pagination d-none"></div>
                        <div class="mt-3">
                            <div class="pagination">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 me-3">
                                        Hiển thị {{ $items->firstItem() ?? 0 }} đến {{ $items->lastItem() ?? 0 }} của
                                        {{ $items->total() }} kết quả
                                    </p>

                                    <nav aria-label="Page navigation">
                                        <ul class="pagination mb-0">
                                            @if ($items->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link">Trang trước</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $items->previousPageUrl() }}"
                                                        aria-label="Previous">
                                                        Trang trước
                                                    </a>
                                                </li>
                                            @endif

                                            @if ($items->hasPages())
                                                @php
                                                    $currentPage = $items->currentPage();
                                                    $lastPage = $items->lastPage();
                                                    $window = 1; // Show 1 page before and after current
                                                @endphp

                                                {{-- First Page --}}
                                                @if ($currentPage > $window + 2)
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $items->url(1) }}">1</a>
                                                    </li>
                                                    @if ($currentPage > $window + 3)
                                                        <li class="page-item disabled">
                                                            <span class="page-link">...</span>
                                                        </li>
                                                    @endif
                                                @endif

                                                {{-- Page Window --}}
                                                @for ($i = max(1, $currentPage - $window); $i <= min($lastPage, $currentPage + $window); $i++)
                                                    <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                                        <a class="page-link"
                                                            href="{{ $items->url($i) }}">{{ $i }}</a>
                                                    </li>
                                                @endfor

                                                {{-- Last Page --}}
                                                @if ($currentPage < $lastPage - $window - 1)
                                                    @if ($currentPage < $lastPage - $window - 2)
                                                        <li class="page-item disabled">
                                                            <span class="page-link">...</span>
                                                        </li>
                                                    @endif
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                            href="{{ $items->url($lastPage) }}">{{ $lastPage }}</a>
                                                    </li>
                                                @endif
                                            @endif

                                            @if ($items->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $items->nextPageUrl() }}"
                                                        aria-label="Next">
                                                        Trang tiếp
                                                    </a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <span class="page-link">Trang tiếp</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
