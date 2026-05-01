<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'Deep Clean', 'description' => 'Pembersihan mendalam untuk seluruh bagian sepatu dari luar dan dalam.', 'price' => 50000],
            ['name' => 'Fast Clean', 'description' => 'Pembersihan cepat untuk bagian luar sepatu saja.', 'price' => 35000],
            ['name' => 'Unyellowing', 'description' => 'Menghilangkan noda kuning pada midsole sepatu yang menguning akibat oksidasi.', 'price' => 60000],
            ['name' => 'Repaint', 'description' => 'Pengecatan ulang warna sepatu untuk mengembalikan warnanya seperti baru.', 'price' => 120000],
        ];

        foreach ($services as $service) {
            \App\Models\Service::create($service);
        }
    }
}
