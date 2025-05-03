<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderRefund;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderRefundController extends BaseController
{
    public function __construct()
    {
        $this->model = OrderRefund::class;
        $this->viewPath = 'admin.order-refunds';
        $this->route = 'admin.order-refunds';
        parent::__construct();
    }

    /**
     * Update the refund status
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $refund = OrderRefund::findOrFail($id);

        // Validate the request
        $request->validate([
            'refund_status' => 'required|in:pending,approved,rejected',
        ]);

        // Only allow updating active refunds
        if (!$refund->is_active) {
            return redirect()->back()->with('error', 'This refund request is no longer active.');
        }

        // Update the refund status
        $refund->refund_status = $request->refund_status;

        // If approved, update the order status to refunded (5)
        if ($request->refund_status === 'approved') {
            $order = Order::findOrFail($refund->order_id);
            $order->status_id = 5; // Refunded status
            $order->save();
        }

        $refund->save();

        return redirect()->back()->with('success', 'Refund status updated successfully.');
    }
}
