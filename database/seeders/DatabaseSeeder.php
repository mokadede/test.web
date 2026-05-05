<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Akun Owner
        User::factory()->create([
            'name' => 'Owner Laundry',
            'email' => 'owner@laundry.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        // Akun Karyawan
        User::factory()->create([
            'name' => 'Karyawan Laundry',
            'email' => 'karyawan@laundry.com',
            'password' => bcrypt('password'),
            'role' => 'karyawan',
        ]);

        // ========== SPECIAL TREATMENT ==========
        Service::create([
            'category' => 'Special Treatment',
            'name' => 'Whitening (For Upper)',
            'description' => 'Special treatment whitening untuk bagian upper sepatu.',
            'price' => 85000,
            'estimated_days' => '5-10 hari',
        ]);
        Service::create([
            'category' => 'Special Treatment',
            'name' => 'Unyellowing (For Midsole)',
            'description' => 'Menghilangkan noda kuning pada midsole.',
            'price' => 85000,
            'estimated_days' => '7-10 hari',
        ]);
        Service::create([
            'category' => 'Special Treatment',
            'name' => 'Brightening',
            'description' => 'Mencerahkan warna sepatu.',
            'price' => 55000,
            'estimated_days' => '5-7 hari',
        ]);
        Service::create([
            'category' => 'Special Treatment',
            'name' => 'All Special Treatment',
            'description' => 'Paket lengkap semua special treatment.',
            'price' => 120000,
            'estimated_days' => '17-21 hari',
        ]);

        // ========== CLEANING ==========
        Service::create([
            'category' => 'Cleaning',
            'name' => 'Deep Clean One Day',
            'description' => 'Deep cleaning selesai dalam 1 hari.',
            'price' => 55000,
            'estimated_days' => '1 hari',
        ]);
        Service::create([
            'category' => 'Cleaning',
            'name' => 'Deep Clean Two Days',
            'description' => 'Deep cleaning selesai dalam 2 hari.',
            'price' => 47500,
            'estimated_days' => '2 hari',
        ]);
        Service::create([
            'category' => 'Cleaning',
            'name' => 'Deep Clean Three Days',
            'description' => 'Deep cleaning selesai dalam 3 hari.',
            'price' => 42500,
            'estimated_days' => '3 hari',
        ]);
        Service::create([
            'category' => 'Cleaning',
            'name' => 'Deep Clean Four - Five Days',
            'description' => 'Deep cleaning selesai dalam 4-5 hari.',
            'price' => 38500,
            'estimated_days' => '4-5 hari',
        ]);
        Service::create([
            'category' => 'Cleaning',
            'name' => 'Express Clean (With Deep Clean)',
            'description' => 'Express clean dengan deep clean, selesai di hari yang sama.',
            'price' => 100000,
            'estimated_days' => 'Same Day',
        ]);
        Service::create([
            'category' => 'Cleaning',
            'name' => 'Bag / Hat Cleaning',
            'description' => 'Pencucian tas atau topi.',
            'price' => 35000,
            'estimated_days' => '3-5 hari',
        ]);
        Service::create([
            'category' => 'Cleaning',
            'name' => 'Special Condition',
            'description' => 'Untuk sepatu basah berbau jamur & kotoran hewan.',
            'price' => 55000,
            'estimated_days' => '5-14 hari',
        ]);
        Service::create([
            'category' => 'Cleaning',
            'name' => 'Reguler Clean',
            'description' => 'Untuk sepatu putih/warna cerah, upper dengan tingkat noda tertentu.',
            'price' => 25000,
            'estimated_days' => '4 hari',
        ]);

        // ========== REPAIR TREATMENT ==========
        Service::create([
            'category' => 'Repair Treatment',
            'name' => 'Custom Repair',
            'description' => 'Perbaikan custom sesuai kebutuhan.',
            'price' => 250000,
            'estimated_days' => '7-14 hari',
        ]);
        Service::create([
            'category' => 'Repair Treatment',
            'name' => 'Re-Glue + Press',
            'description' => 'Pengeleman ulang + press.',
            'price' => 150000,
            'estimated_days' => '14 hari',
        ]);
        Service::create([
            'category' => 'Repair Treatment',
            'name' => 'Re-Glue Manual',
            'description' => 'Pengeleman ulang manual.',
            'price' => 75000,
            'estimated_days' => '14 hari',
        ]);

        // ========== REPAINT TREATMENT ==========
        Service::create([
            'category' => 'Repaint Treatment',
            'name' => 'Repaint Canvas',
            'description' => 'Repaint untuk sepatu berbahan canvas.',
            'price' => 120000,
            'estimated_days' => '7-10 hari',
        ]);
        Service::create([
            'category' => 'Repaint Treatment',
            'name' => 'Repaint Leather',
            'description' => 'Repaint untuk sepatu berbahan kulit.',
            'price' => 130000,
            'estimated_days' => '7-10 hari',
        ]);
        Service::create([
            'category' => 'Repaint Treatment',
            'name' => 'Repaint Midsole',
            'description' => 'Repaint khusus bagian midsole.',
            'price' => 90000,
            'estimated_days' => '4-7 hari',
        ]);
        Service::create([
            'category' => 'Repaint Treatment',
            'name' => 'Change Colour Repaint',
            'description' => 'Biaya tambahan untuk ganti warna repaint.',
            'price' => 35000,
            'estimated_days' => '-',
        ]);

        // ========== SAMPLE ORDERS ==========
        Order::create([
            'customer_name' => 'Budi Santoso',
            'phone_number' => '081234567890',
            'shoe_brand' => 'Nike Air Max',
            'shoe_size' => '42',
            'shoe_condition' => 'Kotor Sedang',
            'service_category' => 'Cleaning',
            'service_name' => 'Deep Clean Three Days',
            'additional_fees' => 0,
            'total_price' => 42500,
            'estimated_days' => '3 hari',
            'payment_method' => 'Cash',
            'payment_status' => 'paid',
            'status' => 'processing',
            'created_by' => 'Karyawan Laundry',
        ]);

        Order::create([
            'customer_name' => 'Siti Aminah',
            'phone_number' => '089876543210',
            'shoe_brand' => 'Adidas Superstar',
            'shoe_size' => '38',
            'shoe_condition' => 'Kotor Berat',
            'service_category' => 'Cleaning',
            'service_name' => 'Deep Clean One Day',
            'additional_fees' => 5000,
            'total_price' => 60000,
            'estimated_days' => '1 hari',
            'payment_method' => 'QRIS',
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'notes' => 'Ada noda tinta di bagian toe box. Tambah biaya untuk warna putih.',
            'created_by' => 'Owner Laundry',
        ]);

        Order::create([
            'customer_name' => 'Andi Pratama',
            'phone_number' => '082112345678',
            'shoe_brand' => 'Vans Old Skool',
            'shoe_size' => '44',
            'shoe_condition' => 'Kotor Ringan',
            'service_category' => 'Repaint Treatment',
            'service_name' => 'Repaint Canvas',
            'additional_fees' => 0,
            'total_price' => 120000,
            'estimated_days' => '7-10 hari',
            'payment_method' => 'Transfer Bank',
            'payment_status' => 'paid',
            'status' => 'completed',
            'created_by' => 'Karyawan Laundry',
        ]);
    }
}
