<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;

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
            
            $order = $this->model::findOrFail($id);
            $oldStatusId = $order->getRawOriginal('status_id');
            $newStatusId = $request->status_id;
            $order->status_id = $newStatusId;
            $order->save();
            // If new status is Completed (2), update payment status
            if ($newStatusId == 2) {
                $order->payment_status = 'completed';
                $order->paid_at = now();
                $order->save();
            }
            return redirect()->route($this->route . '.index')
                ->with('success', 'Order status updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating order status: ' . $e->getMessage());
        }
    }
}