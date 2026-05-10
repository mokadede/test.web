<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom yang diperlukan untuk fitur voucher lengkap:
     * - value (rename discount_amount), type (rename discount_type)
     * - min_order, max_uses, used_count, valid_from, valid_until
     */
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->decimal('min_order', 12, 0)->default(0)->after('discount_type');
            $table->integer('max_uses')->default(100)->after('min_order');
            $table->integer('used_count')->default(0)->after('max_uses');
            $table->date('valid_from')->nullable()->after('used_count');
            $table->date('valid_until')->nullable()->after('valid_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['min_order', 'max_uses', 'used_count', 'valid_from', 'valid_until']);
        });
    }
};
