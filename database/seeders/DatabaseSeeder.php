<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
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

        // Akun Pelanggan (Customer)
        User::factory()->create([
            'name' => 'Pelanggan Setia',
            'email' => 'customer@laundry.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // Daftar Layanan (Services)
        $services = [
            [
                'name' => 'Deep Cleaning',
                'description' => 'Pembersihan menyeluruh untuk semua bagian sepatu, luar dan dalam.',
                'price' => 50000,
            ],
            [
                'name' => 'Fast Cleaning',
                'description' => 'Pembersihan instan untuk bagian luar sepatu (upper & midsole).',
                'price' => 30000,
            ],
            [
                'name' => 'Unyellowing',
                'description' => 'Mengembalikan warna sole sepatu yang menguning menjadi putih kembali.',
                'price' => 60000,
            ],
            [
                'name' => 'Leather Care',
                'description' => 'Perawatan khusus sepatu berbahan kulit asli atau sintetis.',
                'price' => 70000,
            ],
            [
                'name' => 'Repaint',
                'description' => 'Pewarnaan ulang sepatu yang sudah pudar atau ganti warna.',
                'price' => 120000,
            ]
        ];

        foreach ($services as $service) {
            \App\Models\Service::create($service);
        }
    }
}
