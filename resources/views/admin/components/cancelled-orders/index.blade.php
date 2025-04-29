@extends('admin.master')

@section('title', $title ?? 'Cancellation Requests')

@section('content')
    <div class="content">
        <nav class="mb-3" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $title ?? 'Cancellation Requests' }}</li>
            </ol>
        </nav>

        <div class="mb-9">
            <div class="row g-3 mb-4">
                <div class="col-auto">
                    <h2 class="mb-0">{{ $title ?? 'Cancellation Requests' }}</h2>
                </div>
            </div>

            <div id="table-list" data-list='{"valueNames":["name"],"page":15,"pagination":false}'>
                <div class="mb-4">
                    <div class="d-flex flex-wrap gap-3">
                        <div class="search-box">
                            <form action="{{ route($route . '.cancelled') }}" method="GET" class="position-relative">
                                <input class="form-control search-input search" type="search" name="search"
                                    value="{{ request()->get('search') }}" placeholder="Search..." aria-label="Search" />
                                <span class="fas fa-search search-box-icon"></span>
                            </form>
                        </div>

                        <!-- Sort Dropdown -->
                        <div class="btn-group position-static text-nowrap">
                            <button class="btn btn-phoenix-secondary px-4 flex-shrink-0" type="button"
                                data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false">
                                Sort By
                                <span class="fas fa-angle-down ms-2"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route($route . '.cancelled') }}">Default</a>
                                </li>
                                @foreach ($fields as $field => $options)
                                    @if (!isset($options['sortable']) || $options['sortable'])
                                        <li>
                                            <a class="dropdown-item {{ request()->get('sort') == $field . '_asc' ? 'active' : '' }}"
                                                href="{{ route($route . '.cancelled', array_merge(request()->except('page'), ['sort' => $field . '_asc'])) }}">
                                                {{ $options['label'] ?? ucfirst($field) }} (A-Z)
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ request()->get('sort') == $field . '_desc' ? 'active' : '' }}"
                                                href="{{ route($route . '.cancelled', array_merge(request()->except('page'), ['sort' => $field . '_desc'])) }}">
                                                {{ $options['label'] ?? ucfirst($field) }} (Z-A)
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                        <div class="ms-auto">
                            @if (request()->has('search') || request()->has('sort'))
                                <a href="{{ route($route . '.cancelled') }}" class="btn btn-phoenix-secondary me-1">
                                    <span class="fas fa-times me-2"></span> Clear Filters
                                </a>
                            @endif
                            <a href="{{ route($route . '.index') }}" class="btn btn-phoenix-primary">
                                <span class="fas fa-list me-2"></span> All Orders
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
                                                @if (isset($options['formatter']) && is_callable($options['formatter']))
                                                    {!! $options['formatter']($item->$field ?? null, $item) !!}
                                                @elseif ($field == 'action')
                                                    <div class="d-flex">
                                                        @php
                                                            $orderStatus = 0;
                                                            if ($item->order) {
                                                                $orderStatus =
                                                                    $item->order->getRawOriginal('status_id') ?? 0;
                                                            }
                                                        @endphp

                                                        @if ($orderStatus != 4)
                                                            <form
                                                                action="{{ route($route . '.cancellation.approve', $item->id) }}"
                                                                method="POST" class="d-inline me-1">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-success"
                                                                    onclick="return confirm('Are you sure you want to approve this cancellation request?')">
                                                                    <span class="fas fa-check me-1"></span>Approve
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="badge bg-secondary">Đã xử lý</span>
                                                        @endif
                                                    </div>
                                                @elseif ($field == 'customer_info')
                                                    {!! $item->customer_info !!}
                                                @elseif ($field == 'order_status')
                                                    {!! $item->order_status !!}
                                                @else
                                                    {{ $item->$field ?? '-' }}
                                                @endif
                                            </td>
                                        @endforeach
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
                                        Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of
                                        {{ $items->total() }} results
                                    </p>

                                    <nav aria-label="Page navigation">
                                        <ul class="pagination mb-0">
                                            @if ($items->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link">« Previous</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $items->previousPageUrl() }}"
                                                        aria-label="Previous">
                                                        « Previous
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
                                                        Next »
                                                    </a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <span class="page-link">Next »</span>
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
