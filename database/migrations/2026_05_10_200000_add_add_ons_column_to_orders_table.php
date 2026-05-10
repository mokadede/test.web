<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom add_ons ke tabel orders untuk menyimpan detail
     * biaya tambahan (add-ons) dalam format JSON.
     * Contoh value: [{"name":"Basah","price":15000},{"name":"Jamur","price":20000}]
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambahkan kolom add_ons setelah additional_fees
            $table->text('add_ons')->nullable()->after('service_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('add_ons');
        });
    }
};
