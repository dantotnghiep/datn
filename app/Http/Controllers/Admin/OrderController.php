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
            return redirect()->route($this->route . '.index')
                ->with('success', 'Order status updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating order status: ' . $e->getMessage());
        }
    }
} 