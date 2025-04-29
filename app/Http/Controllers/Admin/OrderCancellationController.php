<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderCancellation;
use App\Models\Order;
use Illuminate\Http\Request;

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
        $query = $this->model::with(['order']);
        
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
        
        // Tạo mảng mới để lưu các item đã được xử lý
        $processedItems = [];
        
        foreach ($items as $index => $item) {
            // Tạo một bản sao dữ liệu của item
            $processedItem = clone $item;
            
            if ($processedItem->order) {
                // Thiết lập thông tin khách hàng
                $processedItem->customer_info = $processedItem->order->user_name . ' (' . $processedItem->order->user_phone . ')';
                
                // Thiết lập trạng thái đơn hàng
                $statusId = $processedItem->order->getRawOriginal('status_id') ?? 0;
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
                
                $processedItem->order_status = '<span class="badge bg-' . $statusClass . '">' . $statusName . '</span>';
            } else {
                $processedItem->customer_info = '-';
                $processedItem->order_status = '-';
            }
            
            $processedItems[] = $processedItem;
        }
        
        // Tạo collection từ mảng đã xử lý và duy trì thông tin phân trang
        $processedCollection = new \Illuminate\Pagination\LengthAwarePaginator(
            $processedItems,
            $items->total(),
            $items->perPage(),
            $items->currentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
        
        return view($this->viewPath . '.index', [
            'items' => $processedCollection,
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
            $cancellation = $this->model::with('order')->findOrFail($id);
            $order = $cancellation->order;
            
            if (!$order) {
                return redirect()->route($this->route . '.cancelled')
                    ->with('error', 'Order not found!');
            }
            
            // Cập nhật trạng thái đơn hàng thành "Cancelled"
            $order->status_id = 4; // ID của trạng thái "Cancelled"
            $order->save();
            
            return redirect()->route($this->route . '.cancelled')
                ->with('success', 'Order cancellation approved successfully!');
                
        } catch (\Exception $e) {
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
            $cancellation = $this->model::findOrFail($id);
            
            // Xóa yêu cầu hủy
            $cancellation->delete();
            
            return redirect()->route($this->route . '.cancelled')
                ->with('success', 'Order cancellation request rejected!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error rejecting cancellation: ' . $e->getMessage());
        }
    }
} 