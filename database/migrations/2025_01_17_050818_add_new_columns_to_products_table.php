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
        Schema::table('products', function (Blueprint $table) {
            $table->string('name',255);
            $table->string('slug',255)->unique();
            $table->text('description')->nullable();
            $table->decimal('price',10,2)->default(0);
            $table->decimal('sale_price',10,2)->nullable();
            $table->dateTime('sale_start')->nullable();
            $table->dateTime('sale_end')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']); 
            $table->dropColumn([
                'name',
                'slug',
                'description',
                'price',
                'sale_price',
                'sale_start',
                'sale_end',
                'category_id',
                'status',
            ]);
        });
    }
};
