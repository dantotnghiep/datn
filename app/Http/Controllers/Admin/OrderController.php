<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderStatusChanged;
use App\Models\Order;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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