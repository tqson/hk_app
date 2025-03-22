<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('import_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->foreignId('product_batch_id')->nullable()->constrained();
            $table->integer('quantity');
            $table->decimal('import_price', 15, 2);
            $table->decimal('total_price', 15, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('import_items');
    }
};
