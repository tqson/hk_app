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
        Schema::create('import_payment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2)->comment('Số tiền thanh toán');
            $table->decimal('remaining_debt', 12, 2)->comment('Công nợ còn lại');
            $table->string('payment_method')->nullable()->comment('Phương thức thanh toán');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_payment_histories');
    }
};
