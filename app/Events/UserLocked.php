<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLocked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public function __construct($user) // Thêm tham số $user
    {
        $this->user = $user; // Gán tham số vào thuộc tính
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('user.' . $this->user->id)];
    }
}