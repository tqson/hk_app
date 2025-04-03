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
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('batch_number')->comment('Số lô');
            $table->date('manufacturing_date')->comment('Ngày sản xuất');
            $table->date('expiry_date')->comment('Hạn sử dụng');
            $table->integer('quantity')->default(0)->comment('Số lượng tồn kho theo lô');
            $table->integer('import_price')->default(0)->comment('Giá nhập của lô');
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->timestamps();

            // Mỗi sản phẩm không thể có 2 lô trùng số
            $table->unique(['product_id', 'batch_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_batches');
    }
};
