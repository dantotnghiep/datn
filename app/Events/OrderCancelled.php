<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCancelled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
        
        // Ensure the status relationship is loaded
        if (!$order->relationLoaded('status')) {
            $this->order->load('status');
        }
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('admin-orders'),
            new PrivateChannel('orders.' . $this->order->user_id)
        ];
    }

    public function broadcastAs()
    {
        return 'OrderCancelled';
    }

    public function broadcastWith()
    {
        return [
            'order' => [
                'id' => $this->order->id,
                'order_code' => $this->order->order_code,
                'status_id' => $this->order->status_id,
                'status' => [
                    'status_name' => $this->order->status->status_name
                ],
                'user_id' => $this->order->user_id,
                'user_name' => $this->order->user_name ?? $this->order->user->name ?? 'Unknown'
            ]
        ];
    }
}