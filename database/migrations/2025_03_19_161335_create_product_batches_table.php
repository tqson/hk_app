<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->string('batch_number');
            $table->date('manufacturing_date');
            $table->date('expiry_date');
            $table->timestamps();

            $table->unique(['product_id', 'batch_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_batches');
    }
};
