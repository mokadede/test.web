<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Deep Cleaning',
                'price' => 50000,
            ],
            [
                'name' => 'Standard Cleaning',
                'price' => 35000,
            ],
            [
                'name' => 'Unyellowing',
                'price' => 60000,
            ],
            [
                'name' => 'Repaint',
                'price' => 100000,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
