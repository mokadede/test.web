<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Increases order_number from 10 to 12 chars to support YY+5char format (e.g., 25ABCDE).
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique('orders_order_number_unique');
            $table->string('order_number', 12)->nullable()->change();
            $table->unique('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique('orders_order_number_unique');
            $table->string('order_number', 10)->nullable()->change();
            $table->unique('order_number');
        });
    }
};
