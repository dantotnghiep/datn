<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    
    /**
     * Chỉ định rằng sự kiện này nên được broadcast ngay lập tức
     */
    public $broadcastQueue = 'high';
    
    /**
     * Chỉ định rằng sự kiện này nên được broadcast ngay lập tức
     */
    public $afterCommit = true;

    public function __construct($order)
    {
        $this->order = $order;
        
        // Đảm bảo các relationship cần thiết đã được load
        if (!$order->relationLoaded('status')) {
            $this->order->load('status');
        }
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('orders.admin'),
            new PrivateChannel('orders.' . $this->order->user_id)
        ];
    }

    public function broadcastAs()
    {
        return 'OrderStatusUpdated';
    }

    public function broadcastWith()
    {
        return [
            'order' => [
                'id' => $this->order->id,
                'order_code' => $this->order->order_code,
                'status_id' => $this->order->status_id,
                'status' => [
                    'id' => $this->order->status->id,
                    'status_name' => $this->order->status->status_name
                ]
            ]
        ];
    }
}