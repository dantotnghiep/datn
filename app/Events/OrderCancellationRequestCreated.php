<?php

namespace App\Events;

use App\Models\OrderCancellation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCancellationRequestCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cancellation;

    /**
     * Create a new event instance.
     */
    public function __construct(OrderCancellation $cancellation)
    {
        $this->cancellation = $cancellation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('my-channel'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'my-event';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $order = $this->cancellation->order;
        $orderNumber = $order ? $order->order_number : 'Unknown';
        $customerName = $order ? $order->user_name : 'Unknown';
        $customerPhone = $order ? $order->user_phone : 'Unknown';
        $statusId = $order ? $order->getRawOriginal('status_id') : 0;
        $statusName = 'Unknown';
        
        switch ($statusId) {
            case 1: $statusName = 'Pending'; break;
            case 2: $statusName = 'Completed'; break;
            case 3: $statusName = 'Shipping'; break;
            case 4: $statusName = 'Cancelled'; break;
            case 5: $statusName = 'Refunded'; break;
        }

        return [
            'id' => $this->cancellation->id,
            'order_id' => $this->cancellation->order_id,
            'order_number' => $orderNumber,
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'reason' => $this->cancellation->reason,
            'status_id' => $statusId,
            'status_name' => $statusName,
            'notes' => $this->cancellation->notes,
            'created_at' => $this->cancellation->created_at->format('Y-m-d H:i:s'),
            'type' => 'cancellation_request'
        ];
    }
} 