<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageFromClient implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $roomId;
    public $message;

    public $type;
    public $user_id;
    public function __construct($roomId = null, $message = null, $type = 'message', $user_id = null)
    {
        $this->roomId = $roomId;
        $this->message = $message;
        $this->type = $type;
        $this->user_id = $user_id;
    }

    public function broadcastOn()
    {
        return $this->type === 'message'
            ? new Channel('GetMessageRoom.' . $this->roomId)
            : new Channel('GetPerson');
    }

    public function broadcastAs()
    {
        return $this->type;
    }
    public function broadcastWith()
    {
        $data = [
            'roomId' => $this->roomId,
            'message' => $this->message,
            'type'    => $this->type,
            'user_id' => $this->user_id,
        ];
        return $data;
    }
}
