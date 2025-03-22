<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('return_invoices', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->decimal('total_amount', 15, 2);
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('return_invoices', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('return_invoices', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'total_amount', 'notes']);
        });
    }
};
