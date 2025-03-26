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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained('product_categories')->onDelete('cascade');
            $table->string('unit')->comment('Đơn vị tính');
            $table->bigInteger('price')->comment('Giá bán');
//            $table->decimal('import_price', 12, 2)->default(0)->comment('Giá nhập');
            $table->text('description')->nullable()->comment('Mô tả sản phẩm');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
