<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderCancellationProcessed;
use App\Events\OrderStatusChanged;
use App\Models\OrderCancellation;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderCancellationController extends BaseController
{
    public function __construct()
    {
        $this->model = OrderCancellation::class;
        $this->viewPath = 'admin.components.cancelled-orders';
        $this->route = 'admin.orders';
        parent::__construct();
    }
    
    /**
     * Hiển thị danh sách yêu cầu hủy đơn hàng
     */
    public function index(Request $request)
    {
        $fields = [
            'order_id' => [
                'label' => 'Order Number',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true,
                'formatter' => function($value, $item) {
                    if (!$item->order) return $value;
                    return '<a href="' . route('admin.orders.details', $item->order->id) . '" class="fw-semibold text-body">' . 
                        $item->order->order_number . '</a>';
                }
            ],
            'customer_info' => [
                'label' => 'Customer',
                'type' => 'text',
                'searchable' => false,
                'sortable' => false
            ],
            'order_status' => [
                'label' => 'Order Status',
                'type' => 'text',
                'searchable' => false,
                'sortable' => false
            ],
            'reason' => [
                'label' => 'Cancellation Reason',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'notes' => [
                'label' => 'Notes',
                'type' => 'textarea',
                'searchable' => true,
                'sortable' => false
            ],
            'created_at' => [
                'label' => 'Requested Date',
                'type' => 'datetime',
                'sortable' => true
            ],
            'action' => [
                'label' => 'Actions',
                'type' => 'text',
                'searchable' => false,
                'sortable' => false,
                'formatter' => function($value, $item) {
                    if (!$item->order || $item->order->getRawOriginal('status_id') == 4) {
                        return '<span class="badge bg-secondary">Processed</span>';
                    }
                    
                    $approveForm = '<form action="' . route($this->route . '.cancellation.approve', $item->id) . '" method="POST" class="d-inline me-1">
                        ' . csrf_field() . '
                        <button type="submit" class="btn btn-sm btn-success" title="Approve Cancellation">
                            <span class="fas fa-check me-1"></span>Approve
                        </button>
                    </form>';
                    
                    return $approveForm;
                }
            ],
        ];
        
        // Query từ bảng OrderCancellation với order và thông tin liên quan
        $query = $this->model::with(['order.user']);
        
        // Xử lý tìm kiếm
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $searchable = collect($fields)->filter(function ($options) {
                return isset($options['searchable']) && $options['searchable'];
            })->keys()->toArray();
            
            $query->where(function ($q) use ($searchable, $search) {
                foreach ($searchable as $field) {
                    if ($field === 'order_id') {
                        $q->orWhereHas('order', function($subQuery) use ($search) {
                            $subQuery->where('order_number', 'like', "%{$search}%");
                        });
                    } else {
                        $q->orWhere($field, 'like', "%{$search}%");
                    }
                }
            });
        }
        
        // Xử lý sắp xếp
        if ($request->has('sort') && !empty($request->sort)) {
            $sort = explode('_', $request->sort);
            if (count($sort) == 2) {
                $query->orderBy($sort[0], $sort[1]);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $items = $query->paginate(15);
        
        return view($this->viewPath . '.index', [
            'items' => $items,
            'fields' => $fields,
            'route' => $this->route,
            'title' => 'Cancellation Requests'
        ]);
    }
    
    /**
     * Xử lý yêu cầu hủy đơn hàng - chấp nhận
     */
    public function approve($id)
    {
        try {
            // Ghi log để theo dõi
            Log::info('OrderCancellationController@approve - Start', [
                'cancellation_id' => $id
            ]);
            
            $cancellation = $this->model::with('order')->findOrFail($id);
            $order = $cancellation->order;
            
            if (!$order) {
                Log::warning('OrderCancellationController@approve - Order not found', [
                    'cancellation_id' => $id
                ]);
                
                return redirect()->route($this->route . '.cancelled')
                    ->with('error', 'Order not found!');
            }
            
            Log::info('OrderCancellationController@approve - Before status update', [
                'cancellation_id' => $id,
                'order_id' => $order->id,
                'old_status' => $order->status_id
            ]);
            
            // Cập nhật trạng thái đơn hàng thành "Cancelled"
            $order->status_id = 4; // ID của trạng thái "Cancelled"
            $order->save();
            
            Log::info('OrderCancellationController@approve - After status update', [
                'cancellation_id' => $id,
                'order_id' => $order->id,
                'new_status' => $order->status_id
            ]);
            
            // Phát sóng sự kiện OrderStatusChanged để cập nhật trạng thái đơn hàng ở client
            try {
                event(new OrderStatusChanged($order));
                Log::info('OrderCancellationController@approve - OrderStatusChanged event dispatched');
            } catch (\Exception $eventError) {
                Log::error('OrderCancellationController@approve - Error dispatching OrderStatusChanged event', [
                    'error' => $eventError->getMessage()
                ]);
            }
            
            // Phát sóng sự kiện OrderCancellationProcessed để cập nhật danh sách yêu cầu hủy
            try {
                event(new OrderCancellationProcessed($cancellation, 'approved'));
                Log::info('OrderCancellationController@approve - OrderCancellationProcessed event dispatched');
            } catch (\Exception $eventError) {
                Log::error('OrderCancellationController@approve - Error dispatching OrderCancellationProcessed event', [
                    'error' => $eventError->getMessage()
                ]);
            }
            
            return redirect()->route($this->route . '.cancelled')
                ->with('success', 'Order cancellation approved successfully!');
                
        } catch (\Exception $e) {
            Log::error('OrderCancellationController@approve - Error', [
                'cancellation_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error approving cancellation: ' . $e->getMessage());
        }
    }
    
    /**
     * Từ chối yêu cầu hủy đơn hàng
     */
    public function reject($id)
    {
        try {
            // Ghi log để theo dõi
            Log::info('OrderCancellationController@reject - Start', [
                'cancellation_id' => $id
            ]);
            
            $cancellation = $this->model::with('order')->findOrFail($id);
            
            // Phát sóng sự kiện OrderCancellationProcessed để cập nhật danh sách yêu cầu hủy
            try {
                event(new OrderCancellationProcessed($cancellation, 'rejected'));
                Log::info('OrderCancellationController@reject - OrderCancellationProcessed event dispatched');
            } catch (\Exception $eventError) {
                Log::error('OrderCancellationController@reject - Error dispatching OrderCancellationProcessed event', [
                    'error' => $eventError->getMessage()
                ]);
            }
            
            // Xóa yêu cầu hủy
            $cancellation->delete();
            
            Log::info('OrderCancellationController@reject - Cancellation deleted', [
                'cancellation_id' => $id
            ]);
            
            return redirect()->route($this->route . '.cancelled')
                ->with('success', 'Order cancellation request rejected!');
                
        } catch (\Exception $e) {
            Log::error('OrderCancellationController@reject - Error', [
                'cancellation_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error rejecting cancellation: ' . $e->getMessage());
        }
    }
} 