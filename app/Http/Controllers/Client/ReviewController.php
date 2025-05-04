<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a review for an order
     */
    public function store(Request $request, $order)
    {
        $order = Order::where('order_number', $order)->firstOrFail();
        
        
        // Check if order has been completed
        if ($order->status_id != 2) { // Assuming 2 is the ID for completed status
            return redirect()->route('client.order.detail', $order->order_number)
                ->with('error', 'Bạn chỉ có thể đánh giá đơn hàng đã hoàn thành.');
        }
        
        // Check if order has already been reviewed
        if ($order->reviews()->where('user_id', Auth::id())->exists()) {
            return redirect()->route('client.order.detail', $order->order_number)
                ->with('error', 'Bạn đã đánh giá đơn hàng này rồi.');
        }
        
        // Validate request
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);
        
        // Create review
        $review = new Review([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'rating' => $request->rating,
        ]);
        
        $review->save();
        
        return redirect()->route('client.order.detail', $order->order_number)
            ->with('review_success', 'Cảm ơn bạn đã đánh giá đơn hàng!');
    }
}
