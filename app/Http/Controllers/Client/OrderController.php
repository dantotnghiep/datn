<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_cancellation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Events\OrderStatusUpdated;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::query()->where('user_id', Auth::id())->get();
        return view('client.orders.index', compact('orders'));
    }

    public function cancle($id)
    {
        DB::transaction(function () use ($id) {
            $order = Order::query()->where('user_id', Auth::id())->where('id', $id)->first();
            if ($order) {
                if ($order->status_id == '1' || $order->status_id == '2') {
                    $order->update(['status_id' => 3]);

                    Order_cancellation::create([
                        'order_id' => $order->id,
                        'reason' => 'abcabc'
                    ]);
                }
                else {
                    return back()->with('error', 'Không thể hủy đơn hàng này vì trạng thái không hợp lệ');
                }
            }
            return back()->with('success', 'Hủy đơn hàng thành công');
        });
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status_id' => 'required|exists:order_statuses,id'
        ]);
        
        $order->status_id = $request->status_id;
        $order->save();
        
        event(new OrderStatusUpdated($order));
        
        return redirect()->back()->with('success', 'Order status updated successfully');
    }
}
