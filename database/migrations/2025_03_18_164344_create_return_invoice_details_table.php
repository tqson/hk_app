<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnInvoiceDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('return_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
        });
    }

    public function down()
    {
        Schema::dropIfExists('return_invoice_details');
    }
}
