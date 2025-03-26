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
        Schema::create('disposal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disposal_invoice_id')->constrained('disposal_invoices')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_batch_id')->constrained('product_batches');
            $table->bigInteger('quantity');
            $table->bigInteger('price');
            $table->bigInteger('total_price');
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposal_items');
    }
};
