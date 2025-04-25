<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RefreshPersonFromClient implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $roomId;
    public $message,$lastMessage;
    public $type;

    public function __construct($roomId = null, $message = null,$lastMessage= null, $type = 'refresh')
    {
        $this->roomId = $roomId;
        $this->message = $message;
        $this->lastMessage = $lastMessage;
        $this->type = $type;
    }

    public function broadcastOn()
    {
        return new Channel('RefreshPerson');
    }

    public function broadcastAs()
    {
        return 'refresh';
    }
}
