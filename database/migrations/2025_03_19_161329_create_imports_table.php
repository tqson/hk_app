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
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->string('import_code')->unique()->comment('Mã phiếu nhập');
            $table->foreignId('supplier_id')->constrained();
            $table->date('expected_date')->comment('Ngày nhận hàng dự kiến');
            $table->bigInteger('total_amount')->comment('Tổng tiền hàng');
            $table->integer('vat')->comment('Thuế VAT');
//            $table->integer('vat_percent')->comment('Phần trăm VAT');
            $table->integer('discount_percent')->comment('Phần trăm giảm giá');
            $table->integer('final_amount')->comment('Tổng cần trả');
            $table->integer('paid_amount')->comment('Đã thanh toán');
            $table->integer('debt_amount')->comment('Còn nợ');
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
