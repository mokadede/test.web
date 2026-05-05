<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Restructure orders table to match the new flat schema:
     * order_id, customer_name, phone_number, shoe_brand, shoe_size,
     * shoe_condition, service_category, service_name, additional_fees,
     * total_price, estimated_days, payment_method, payment_status,
     * status, created_at, created_by
     */
    public function up(): void
    {
        // Drop old related tables first
        Schema::dropIfExists('order_items');

        // Drop the old orders table and recreate with new structure
        Schema::dropIfExists('orders');

        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->string('tracking_code', 5)->unique()->nullable();
            $table->string('customer_name');
            $table->string('phone_number');
            $table->string('shoe_brand')->nullable();
            $table->string('shoe_size')->nullable();
            $table->string('shoe_condition')->nullable(); // Kotor Ringan, Kotor Sedang, Kotor Berat, dll.
            $table->string('service_category')->nullable(); // Kategori layanan: Cuci, Repaint, dll.
            $table->string('service_name'); // Nama layanan spesifik: Deep Cleaning, Fast Cleaning, dll.
            $table->integer('additional_fees')->default(0); // Biaya tambahan
            $table->integer('total_price')->default(0);
            $table->string('estimated_days')->nullable(); // Estimasi hari pengerjaan (dari layanan)
            $table->string('payment_method')->nullable(); // QRIS, Transfer Bank, Cash
            $table->string('payment_status')->default('unpaid'); // unpaid, paid
            $table->string('status')->default('pending'); // pending, processing, completed, cancelled
            $table->text('notes')->nullable();
            $table->string('created_by')->nullable(); // Nama karyawan/owner yang input
            $table->string('external_id')->nullable(); // For Xendit reference
            $table->string('voucher_code')->nullable();
            $table->integer('discount_amount')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');

        // Recreate original orders table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tracking_code', 5)->unique()->nullable();
            $table->string('status')->default('pending');
            $table->integer('total_price')->default(0);
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->string('external_id')->nullable();
            $table->string('voucher_code')->nullable();
            $table->integer('discount_amount')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Recreate original order_items table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('shoe_name');
            $table->string('shoe_brand')->nullable();
            $table->string('shoe_size')->nullable();
            $table->string('shoe_material')->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('price');
            $table->timestamps();
        });
    }
};
