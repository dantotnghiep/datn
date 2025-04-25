<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendAdminMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $message;
    public $type;

    /**
     * Create a new event instance.
     *
     * @param  string|null  $userId
     * @param  string|null  $message
     * @param  string       $type
     */
    public function __construct($userId = null, $message = null, $type = 'message')
    {
        $this->userId = $userId;
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * Determine the broadcast channel based on the event type.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return $this->type === 'message'
            ? new Channel('user.' . $this->userId)
            : new Channel('startSale');
    }

    /**
     * Define the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return $this->type;
    }
}
