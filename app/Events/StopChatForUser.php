<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StopChatForUser  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $roomId;
    public $message;

    public function __construct($roomId, $message)
    {
        $this->roomId = $roomId;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('GetMessageRoom.' . $this->roomId); // Gửi sự kiện qua room của người dùng
    }

    public function broadcastAs()
    {
        return 'stop-chat';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message
        ];
    }
}
