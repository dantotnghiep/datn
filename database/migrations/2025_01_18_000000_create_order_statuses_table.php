<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status_name')->unique();
            $table->timestamps();
        });

        // Thêm các trạng thái mặc định
        DB::table('order_statuses')->insert([
            [
                'id' => 1,
                'status_name' => 'Chờ xử lý',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'status_name' => 'Đang vận chuyển',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'status_name' => 'Thành công',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'status_name' => 'Đã hủy',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('order_statuses');
    }
};