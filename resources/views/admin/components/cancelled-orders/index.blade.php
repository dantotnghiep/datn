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
                            <tbody class="list" id="cancellation-requests-body">
                                @foreach ($items as $item)
                                    <tr id="cancellation-row-{{ $item->id }}">
                                        @foreach ($fields as $field => $options)
                                            <td class="align-middle name">
                                                @if (isset($options['formatter']) && is_callable($options['formatter']))
                                                    {!! $options['formatter']($item->$field ?? null, $item) !!}
                                                @elseif ($field == 'order_id')
                                                    @if($item->order)
                                                        <a href="{{ route('admin.orders.details', $item->order->id) }}" class="fw-semibold text-body">
                                                            {{ $item->order->order_number }}
                                                        </a>
                                                    @else
                                                        {{ $item->order_id ?? '-' }}
                                                    @endif
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
                                                            <span class="badge bg-secondary">Processed</span>
                                                        @endif
                                                    </div>
                                                @elseif ($field == 'customer_info')
                                                    @if($item->order)
                                                        {{ $item->order->user_name ?? '-' }} 
                                                        @if($item->order->user_phone)
                                                            ({{ $item->order->user_phone }})
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                @elseif ($field == 'order_status')
                                                    @if($item->order)
                                                        @php
                                                            $statusId = $item->order->getRawOriginal('status_id') ?? 0;
                                                            $statusName = '';
                                                            $statusClass = '';
                                                            
                                                            switch ($statusId) {
                                                                case 1:
                                                                    $statusName = 'Pending';
                                                                    $statusClass = 'warning';
                                                                    break;
                                                                case 2:
                                                                    $statusName = 'Completed';
                                                                    $statusClass = 'success';
                                                                    break;
                                                                case 3:
                                                                    $statusName = 'Shipping';
                                                                    $statusClass = 'info';
                                                                    break;
                                                                case 4:
                                                                    $statusName = 'Cancelled';
                                                                    $statusClass = 'danger';
                                                                    break;
                                                                case 5:
                                                                    $statusName = 'Refunded';
                                                                    $statusClass = 'danger';
                                                                    break;
                                                                default:
                                                                    $statusName = 'Unknown';
                                                                    $statusClass = 'secondary';
                                                            }
                                                        @endphp
                                                        <span class="badge bg-{{ $statusClass }}">{{ $statusName }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                @elseif ($field == 'created_at')
                                                    {{ $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '-' }}
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tắt Pusher logging
        Pusher.logToConsole = false;
        console.log('Initializing Pusher connection for cancellation requests...');

        // Get Pusher key and cluster from configuration
        const pusherKey = '{{ config("broadcasting.connections.pusher.key") }}';
        const pusherCluster = '{{ config("broadcasting.connections.pusher.options.cluster") }}';

        // Make sure we have necessary configuration
        if (!pusherKey) {
            console.error('Pusher key not configured');
            return;
        }

        try {
            // Initialize Pusher
            const pusher = new Pusher(pusherKey, {
                cluster: pusherCluster || 'ap1',
                forceTLS: true
            });

            // Subscribe to the channel
            const channel = pusher.subscribe('my-channel');
            
            // Listen for cancellation request events
            channel.bind('my-event', function(data) {
                console.log('Received event:', data);
                
                // Handle new cancellation requests
                if (data.type === 'cancellation_request') {
                    handleNewCancellationRequest(data);
                }
                
                // Handle processed cancellation requests
                if (data.type === 'cancellation_processed') {
                    handleProcessedCancellationRequest(data);
                }
                
                // Handle order status changes (for updating order status column)
                if (data.type === undefined && data.order_number) {
                    updateOrderStatus(data);
                }
            });
            
            // Function to handle new cancellation requests
            function handleNewCancellationRequest(data) {
                // Check if the request is already in the table
                if (document.getElementById(`cancellation-row-${data.id}`)) {
                    console.log('Cancellation request already exists in table');
                    return;
                }
                
                console.log('Adding new cancellation request to table');
                
                // Create a new row
                const tbody = document.getElementById('cancellation-requests-body');
                if (!tbody) {
                    console.error('Cancellation requests body element not found');
                    return;
                }
                
                // Create row HTML
                let statusClass = 'secondary';
                let statusName = 'Unknown';
                
                switch (data.status_id) {
                    case 1: statusClass = 'warning'; statusName = 'Pending'; break;
                    case 2: statusClass = 'success'; statusName = 'Completed'; break;
                    case 3: statusClass = 'info'; statusName = 'Shipping'; break;
                    case 4: statusClass = 'danger'; statusName = 'Cancelled'; break;
                    case 5: statusClass = 'danger'; statusName = 'Refunded'; break;
                }
                
                const newRow = document.createElement('tr');
                newRow.id = `cancellation-row-${data.id}`;
                newRow.className = 'bg-light-warning';
                newRow.innerHTML = `
                    <td class="align-middle name">
                        <a href="{{ route('admin.orders.details', '') }}/${data.order_id}" class="fw-semibold text-body">
                            ${data.order_number}
                        </a>
                    </td>
                    <td class="align-middle name">
                        ${data.customer_name} (${data.customer_phone})
                    </td>
                    <td class="align-middle name">
                        <span class="badge bg-${statusClass}">${statusName}</span>
                    </td>
                    <td class="align-middle name">
                        ${data.reason}
                    </td>
                    <td class="align-middle name">
                        ${data.notes || '-'}
                    </td>
                    <td class="align-middle name">
                        ${data.created_at}
                    </td>
                    <td class="align-middle name">
                        <div class="d-flex">
                            ${data.status_id != 4 ? `
                                <form action="{{ route($route . '.cancellation.approve', '') }}/${data.id}" method="POST" class="d-inline me-1">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" 
                                        onclick="return confirm('Are you sure you want to approve this cancellation request?')">
                                        <span class="fas fa-check me-1"></span>Approve
                                    </button>
                                </form>
                            ` : `
                                <span class="badge bg-secondary">Processed</span>
                            `}
                        </div>
                    </td>
                `;
                
                // Add to the beginning of the table
                if (tbody.firstChild) {
                    tbody.insertBefore(newRow, tbody.firstChild);
                } else {
                    tbody.appendChild(newRow);
                }
                
                // Show notification
                if (typeof toast !== 'undefined') {
                    toast.info(`New cancellation request for order #${data.order_number}`);
                }
                
                // Remove highlight after 3 seconds
                setTimeout(() => {
                    newRow.classList.remove('bg-light-warning');
                }, 3000);
            }
            
            // Function to handle processed cancellation requests
            function handleProcessedCancellationRequest(data) {
                const row = document.getElementById(`cancellation-row-${data.id}`);
                if (!row) {
                    console.log('Cancellation row not found in table');
                    return;
                }
                
                console.log('Updating processed cancellation request');
                
                if (data.processed_status === 'approved') {
                    // Update the action cell
                    const actionCells = row.querySelectorAll('td:last-child');
                    if (actionCells.length > 0) {
                        actionCells[0].innerHTML = `
                            <div class="d-flex">
                                <span class="badge bg-secondary">Processed</span>
                            </div>
                        `;
                    }
                    
                    // Update the order status cell
                    const statusCells = row.querySelectorAll('td:nth-child(3)');
                    if (statusCells.length > 0) {
                        statusCells[0].innerHTML = `
                            <span class="badge bg-danger">Cancelled</span>
                        `;
                    }
                    
                    // Highlight the row
                    row.classList.add('bg-light-warning');
                    setTimeout(() => {
                        row.classList.remove('bg-light-warning');
                    }, 3000);
                    
                    // Show notification
                    if (typeof toast !== 'undefined') {
                        toast.success(`Cancellation request for order #${data.order_number} approved`);
                    }
                } else if (data.processed_status === 'rejected') {
                    // Remove the row with animation
                    row.classList.add('bg-light-danger');
                    setTimeout(() => {
                        row.style.transition = 'all 0.5s';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                        }, 500);
                    }, 1000);
                    
                    // Show notification
                    if (typeof toast !== 'undefined') {
                        toast.warning(`Cancellation request for order #${data.order_number} rejected`);
                    }
                }
            }
            
            // Function to update order status when it changes
            function updateOrderStatus(data) {
                // Find all rows that match this order
                const cells = document.querySelectorAll(`td a[href*="/${data.id}"]`);
                if (cells.length === 0) return;
                
                // For each cell, find the parent row and update the status
                cells.forEach(cell => {
                    const row = cell.closest('tr');
                    if (!row) return;
                    
                    const statusCell = row.querySelector('td:nth-child(3)');
                    if (!statusCell) return;
                    
                    let statusClass = 'secondary';
                    let statusName = 'Unknown';
                    
                    switch (data.status_id) {
                        case 1: statusClass = 'warning'; statusName = 'Pending'; break;
                        case 2: statusClass = 'success'; statusName = 'Completed'; break;
                        case 3: statusClass = 'info'; statusName = 'Shipping'; break;
                        case 4: statusClass = 'danger'; statusName = 'Cancelled'; break;
                        case 5: statusClass = 'danger'; statusName = 'Refunded'; break;
                    }
                    
                    statusCell.innerHTML = `<span class="badge bg-${statusClass}">${statusName}</span>`;
                    
                    // If order is now cancelled, update the action cell
                    if (data.status_id === 4) {
                        const actionCell = row.querySelector('td:last-child');
                        if (actionCell) {
                            actionCell.innerHTML = `
                                <div class="d-flex">
                                    <span class="badge bg-secondary">Processed</span>
                                </div>
                            `;
                        }
                    }
                    
                    // Highlight the row
                    row.classList.add('bg-light-warning');
                    setTimeout(() => {
                        row.classList.remove('bg-light-warning');
                    }, 3000);
                });
            }
        } catch (error) {
            console.error('Error initializing Pusher:', error);
        }
    });
</script>
@endpush
