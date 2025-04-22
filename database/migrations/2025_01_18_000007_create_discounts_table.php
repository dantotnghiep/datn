<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateDiscountsTable extends Migration
{
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Mã voucher');
            $table->enum('type', ['percentage', 'fixed'])->comment('Loại giảm giá: theo % hoặc số tiền cố định');
            $table->decimal('sale', 8, 2)->comment('Giá trị giảm giá');
            $table->dateTime('startDate')->comment('Ngày bắt đầu');
            $table->dateTime('endDate')->comment('Ngày kết thúc');
            $table->integer('usageCount')->default(0)->comment('Số lần đã sử dụng');
            $table->integer('maxUsage')->nullable()->comment('Số lần tối đa được sử dụng');
            $table->integer('user_limit')->nullable()->comment('Số lần tối đa mỗi user có thể sử dụng');
            $table->boolean('is_public')->default(true)->comment('Voucher công khai hay private');
            $table->decimal('minOrderValue', 12, 2)->nullable()->comment('Giá trị đơn hàng tối thiểu');
            $table->decimal('maxDiscount', 12, 2)->nullable()->comment('Giá trị giảm tối đa');
            $table->json('applicable_products')->nullable()->comment('Danh sách ID sản phẩm được áp dụng');
            $table->json('applicable_categories')->nullable()->comment('Danh sách ID danh mục được áp dụng');
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active')->comment('Trạng thái voucher');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discounts');
    }
} 