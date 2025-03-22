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
            $table->decimal('total_amount', 12, 2)->default(0)->comment('Tổng tiền hàng');
            $table->decimal('vat', 12, 2)->default(0)->comment('Thuế VAT');
            $table->decimal('vat_percent', 5, 2)->default(0)->comment('Phần trăm VAT');
            $table->decimal('discount_percent', 5, 2)->default(0)->comment('Phần trăm giảm giá');
            $table->decimal('final_amount', 12, 2)->default(0)->comment('Tổng cần trả');
            $table->decimal('paid_amount', 12, 2)->default(0)->comment('Đã thanh toán');
            $table->decimal('debt_amount', 12, 2)->default(0)->comment('Còn nợ');
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
