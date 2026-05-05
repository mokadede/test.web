<?php

namespace Database\Seeders;

use App\Models\AddOn;
use Illuminate\Database\Seeder;

class AddOnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addOns = [
            ['name' => 'Change Colour Repaint', 'price' => 35000],
            ['name' => 'Suede Care', 'price' => 0],
            ['name' => 'For White', 'price' => 5000],
            ['name' => 'Warna Cerah', 'price' => 5000],
            ['name' => 'Extra Hard', 'price' => 10000],
            ['name' => 'For Boots', 'price' => 10000],
            ['name' => 'Waxing', 'price' => 5000],
            ['name' => 'Basah, Berbau, Jamur, Kotoran Hewan', 'price' => 0],
        ];

        foreach ($addOns as $addOn) {
            AddOn::updateOrCreate(['name' => $addOn['name']], $addOn);
        }
    }
}
