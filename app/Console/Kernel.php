<?php

namespace App\Console;

use App\Jobs\CheckCancelledOrders;
use App\Jobs\UnlockUserAfterThreeDays;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        // Kiểm tra hủy đơn hàng mỗi giờ
        //$schedule->job(new CheckCancelledOrders(User::find(1)))->hourly();
        $schedule->job(new UnlockUserAfterThreeDays())->everyMinute(); // Chạy mỗi phút để test

        // Kiểm tra và mở khóa tài khoản mỗi ngày
        $schedule->job(new UnlockUserAfterThreeDays())->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
