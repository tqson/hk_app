<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->string('import_code')->unique();
            $table->foreignId('supplier_id')->constrained();
            $table->date('expected_date');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('vat', 15, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('final_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('debt_amount', 15, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('imports');
    }
};
