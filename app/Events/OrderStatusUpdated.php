<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('orders.' . $this->order->user_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->order->id,
            'order_code' => $this->order->order_code,
            'status_id' => $this->order->status_id,
            'status_name' => $this->order->status->status_name ?? 'Processing',
            'updated_at' => $this->order->updated_at->format('M d, Y H:i:s')
        ];
    }
} 