<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UnlockUserAfterThreeDays implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $users = User::where('status', 'inactive')
        ->whereNotNull('locked_at')
        ->where('locked_at', '<=', now()->subMinutes(2)) // 2 phút để test
        ->get();

    foreach ($users as $user) {
        $user->update([
            'status' => 'active',
            'locked_at' => null,
        ]);

        UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'unlocked',
            'reason' => 'Tự động mở khóa sau 2 phút',
        ]);
    }
    }
}
