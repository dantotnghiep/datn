<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckCancelledOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        $cancelledOrders = $this->user->orders()
            ->where('status_id', 6) // Cancelled
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        if ($cancelledOrders >= 5 && $this->user->status === 'active') {
            $this->user->update([
                'status' => 'inactive',
                'locked_at' => now(),
            ]);

            // Xóa các bản ghi cảnh báo liên quan đến user này
            UserActivity::where('user_id', $this->user->id)
                ->where('activity_type', 'warning')
                ->delete();

            UserActivity::create([
                'user_id' => $this->user->id,
                'activity_type' => 'locked',
                'reason' => "Hủy $cancelledOrders đơn trong 7 ngày",
            ]);
        } elseif ($cancelledOrders === 4 && $this->user->status === 'active') {
            // Kiểm tra xem đã có cảnh báo tương tự trong 7 ngày qua chưa
            $existingWarning = UserActivity::where('user_id', $this->user->id)
                ->where('activity_type', 'warning')
                ->where('reason', 'Đã hủy 4 đơn trong 7 ngày')
                ->where('created_at', '>=', now()->subDays(7))
                ->exists();

            if (!$existingWarning) {
                UserActivity::create([
                    'user_id' => $this->user->id,
                    'activity_type' => 'warning',
                    'reason' => 'Đã hủy 4 đơn trong 7 ngày',
                ]);
            }
        }
    }
}
