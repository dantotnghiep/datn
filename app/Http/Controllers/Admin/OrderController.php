<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderStatusChanged;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderStatusHistory;
use App\Models\ProductVariation;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrderController extends BaseController
{
    public function __construct()
    {
        $this->model = Order::class;
        $this->viewPath = 'admin.components.orders';
        $this->route = 'admin.orders';
        parent::__construct();
    }
    
    /**
     * Override the index method to filter orders by the user who processed them
     */
    public function index(Request $request)
    {
        // Clear any cache that might interfere with sorting
        $this->clearCache();
        
        // Tạo query ban đầu mà không có order by mặc định
        $query = $this->model::query();
        
        // Get current authenticated user
        $user = Auth::user();
        
        // Find admin role IDs
        $adminRoleIds = UserRole::whereIn('name', ['admin', 'super-admin'])
            ->pluck('id')
            ->toArray();
        
        // If user doesn't have admin role, filter orders
        if ($user && !in_array($user->role_id, $adminRoleIds)) {
            // Get all non-pending order IDs
            $nonPendingOrderIds = Order::where('status_id', '!=', 1)->pluck('id')->toArray();
            
            // Tìm nhân viên đầu tiên xác nhận đơn hàng (người đầu tiên thay đổi trạng thái từ pending)
            // Sử dụng subquery để lấy bản ghi đầu tiên của mỗi đơn hàng trong history
            $firstConfirmations = DB::table('order_status_history as h1')
                ->join(DB::raw('(
                    SELECT order_id, MIN(id) as first_history_id
                    FROM order_status_history
                    WHERE status_id != 1
                    GROUP BY order_id
                ) as h2'), function($join) {
                    $join->on('h1.id', '=', 'h2.first_history_id');
                })
                ->select('h1.order_id', 'h1.user_id')
                ->whereIn('h1.order_id', $nonPendingOrderIds);
            
            // Lấy các đơn hàng mà user hiện tại là người đầu tiên xác nhận
            $assignedOrderIds = $firstConfirmations
                ->where('h1.user_id', $user->id)
                ->pluck('order_id')
                ->toArray();
            
            // Get pending orders
            $pendingOrderIds = Order::where('status_id', 1) // Pending status
                ->pluck('id')
                ->toArray();
            
            // Show both pending orders and orders assigned to this user
            $query->whereIn('id', array_merge($assignedOrderIds, $pendingOrderIds));
            
            // Add logging to debug
            Log::info('Order Filter Details', [
                'user_id' => $user->id,
                'pending_orders' => count($pendingOrderIds),
                'assigned_orders' => count($assignedOrderIds),
                'total_visible_orders' => count(array_merge($assignedOrderIds, $pendingOrderIds))
            ]);
        }
        
        // Handle Search
        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            $searchableFields = $this->model::getSearchableFields();

            $query->where(function($q) use ($searchTerm, $searchableFields) {
                foreach ($searchableFields as $field) {
                    $q->orWhere($field, 'LIKE', "%{$searchTerm}%");
                }
            });
        }

        // Handle Filters
        if ($request->has('filter')) {
            $filters = $request->get('filter');
            foreach ($filters as $field => $value) {
                if ($value !== null && $value !== '') {
                    $query->where($field, $value);
                }
            }
        }

        // Flag to track if sort has been applied
        $sortApplied = false;

        // Handle Sorting
        if ($request->has('sort')) {
            $sort = $request->get('sort');
            
            if (preg_match('/^(.+)_(asc|desc)$/', $sort, $matches)) {
                $field = $matches[1];
                $direction = $matches[2];
                $table = (new $this->model)->getTable();
                $query->orderBy($table . '.' . $field, $direction);
                $sortApplied = true;
                $query->distinct();
            }
        }
        
        if (!$sortApplied) {
            $query->orderBy('id', 'desc');
        }

        // Handle Trashed Items
        if ($request->get('trashed')) {
            // Kiểm tra xem model có sử dụng SoftDeletes không
            $uses_soft_deletes = in_array(
                \Illuminate\Database\Eloquent\SoftDeletes::class, 
                class_uses_recursive($this->model)
            );
            
            if ($uses_soft_deletes) {
                $query->onlyTrashed();
            }
        }

        $items = $query->paginate($this->itemsPerPage)->withQueryString();
        $fields = $this->model::getFields();
        $title = class_basename($this->model);

        return view($this->viewPath . '.index', [
            'items' => $items,
            'fields' => $fields,
            'title' => $title,
            'route' => $this->route
        ]);
    }
    
    /**
     * Display the details of an order
     */
    public function details($id)
    {
        try {
            $order = $this->model::with(['items.productVariation.product', 'status', 'user'])->findOrFail($id);
            
            return view($this->viewPath . '.details', [
                'order' => $order,
                'route' => $this->route,
                'title' => 'Order #' . $order->order_number
            ]);
        } catch (\Exception $e) {
            return redirect()->route($this->route . '.index')
                ->with('error', 'Error finding order: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the status of an order
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status_id' => 'required|in:1,2,3,4,5'
            ]);
            
            $order = $this->model::with(['status', 'items.productVariation'])->findOrFail($id);
            $oldStatusId = $order->getRawOriginal('status_id');
            $newStatusId = $request->status_id;
            
            // Bắt đầu transaction để đảm bảo toàn vẹn dữ liệu
            DB::beginTransaction();
            
            try {
                // Cập nhật trạng thái đơn hàng
                $order->status_id = $newStatusId;
                $order->save();
                
                // Tạo lịch sử trạng thái
                $notes = $request->notes ?? 'Cập nhật trạng thái đơn hàng';
                
                // Tạo bản ghi lịch sử trạng thái
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status_id' => $newStatusId,
                    'user_id' => Auth::id(),
                    'notes' => $notes
                ]);
                
                // Set session flag to prevent duplicate records from observer
                session(['status_history_tracked_' . $order->id => true]);
                
                // Nếu đơn hàng được chuyển sang trạng thái "Hoàn thành"
                if ($newStatusId == 2) {
                    $order->payment_status = 'completed';
                    $order->paid_at = now();
                    $order->save();
                }
                
                // Nếu đơn hàng được chuyển sang trạng thái "Đã hủy"
                if ($newStatusId == 4 && $oldStatusId != 4) {
                    Log::info('Restoring product quantities for cancelled order', [
                        'order_id' => $order->id,
                        'old_status' => $oldStatusId,
                        'new_status' => $newStatusId
                    ]);
                    
                    // Duyệt qua từng item trong đơn hàng và cập nhật lại số lượng vào kho
                    foreach ($order->items as $item) {
                        // Lấy biến thể sản phẩm
                        $variation = $item->productVariation;
                        if ($variation) {
                            // Cập nhật số lượng sản phẩm trong kho
                            $variation->stock += $item->quantity;
                            $variation->save();
                            
                            Log::info('Updated product stock after cancellation', [
                                'product_variation_id' => $variation->id,
                                'old_stock' => $variation->stock - $item->quantity,
                                'quantity_returned' => $item->quantity,
                                'new_stock' => $variation->stock
                            ]);
                        } else {
                            Log::warning('Product variation not found for order item', [
                                'order_item_id' => $item->id,
                                'product_variation_id' => $item->product_variation_id
                            ]);
                        }
                    }
                }
                
                // Commit transaction
                DB::commit();
                
                // Remove session flag
                session()->forget('status_history_tracked_' . $order->id);
                
                // Tải lại đơn hàng với quan hệ
                $order = $this->model::with('status')->findOrFail($id);
                
                // Kích hoạt sự kiện để cập nhật giao diện
                try {
                    event(new OrderStatusChanged($order));
                } catch (\Exception $eventError) {
                    Log::error('Error dispatching OrderStatusChanged event', [
                        'error' => $eventError->getMessage()
                    ]);
                }
                
                return redirect()->route($this->route . '.index')
                    ->with('success', 'Order status updated successfully!');
                    
            } catch (\Exception $e) {
                // Rollback transaction nếu có lỗi
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error('Error updating order status', [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error updating order status: ' . $e->getMessage());
        }
    }
}