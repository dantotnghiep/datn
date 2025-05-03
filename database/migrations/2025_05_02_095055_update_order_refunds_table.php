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
        Schema::table('order_refunds', function (Blueprint $table) {
            // Check if status column doesn't exist
            if (!Schema::hasColumn('order_refunds', 'status')) {
                $table->string('status')->default('pending')->comment('Request status (pending/approved/rejected)')->after('amount');
            }

            // Make sure refund_status column exists
            if (!Schema::hasColumn('order_refunds', 'refund_status')) {
                $table->string('refund_status')->default('pending')->comment('Money refund status (pending/approved/rejected)')->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to drop these columns in case of rollback
        // as they're part of the base structure
    }
};
