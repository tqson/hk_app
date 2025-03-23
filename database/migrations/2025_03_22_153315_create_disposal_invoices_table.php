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
        Schema::create('disposal_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_code')->unique()->comment('Mã phiếu xuất hủy');
            $table->foreignId('user_id')->nullable()->constrained()->comment('Người tạo phiếu');
            $table->decimal('total_amount', 12, 2)->default(0)->comment('Tổng giá trị hủy');
            $table->text('note')->nullable()->comment('Ghi chú phiếu hủy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposal_invoices');
    }
};
