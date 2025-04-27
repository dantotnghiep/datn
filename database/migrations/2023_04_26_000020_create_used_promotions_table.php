<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('used_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('order_id')->constrained();
            $table->decimal('discount_amount', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('used_promotions');
    }
};
