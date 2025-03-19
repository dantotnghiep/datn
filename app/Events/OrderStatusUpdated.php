<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        
        // Add more detailed logging
        \Log::info('OrderStatusUpdated event constructed', [
            'order_id' => $order->id,
            'status_id' => $order->status_id,
            'user_id' => $order->user_id,
            'channel' => 'private-orders.admin',
            'event' => 'OrderStatusUpdated'
        ]);
    }

    public function broadcastOn(): array
    {
        Log::info('Broadcasting to channels', [
            'admin_channel' => 'private-orders.admin',
            'user_channel' => 'private-orders.' . $this->order->user_id
        ]);
        
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