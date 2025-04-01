<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('status_id')->constrained('order_statuses')->onDelete('cascade');
            $table->string('order_code')->unique();
            $table->text('user_name');
            $table->text('user_phone');
            $table->text('user_email');
            $table->decimal('total_amount', 10, 2);
            $table->text('shipping_address');
            $table->string('payment_method');
            $table->string('discount_code')->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('payment_status')->default('pending');
            $table->string('vnpay_transaction_no')->nullable();
            $table->datetime('vnpay_payment_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
