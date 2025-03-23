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
            $table->string('sku')->nullable()->unique()->comment('Mã sản phẩm');
            $table->string('barcode')->nullable()->unique()->comment('Mã vạch');
            $table->decimal('price', 12, 2)->default(0)->comment('Giá bán');
            $table->decimal('import_price', 12, 2)->default(0)->comment('Giá nhập');
            $table->integer('stock')->default(0)->comment('Số lượng tồn kho không theo lô');
            $table->text('description')->nullable()->comment('Mô tả sản phẩm');
            $table->string('image')->nullable()->comment('Hình ảnh sản phẩm');
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
