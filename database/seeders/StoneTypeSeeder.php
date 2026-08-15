<?php

namespace Database\Seeders;

use App\Models\StoneType;
use Illuminate\Database\Seeder;

class StoneTypeSeeder extends Seeder
{
    public function run(): void
    {
        $stoneTypes = [
            ['name' => 'Diamond', 'sort_order' => 1],
            ['name' => 'Ruby', 'sort_order' => 2],
            ['name' => 'Emerald', 'sort_order' => 3],
            ['name' => 'Sapphire', 'sort_order' => 4],
            ['name' => 'Pearl', 'sort_order' => 5],
            ['name' => 'Topaz', 'sort_order' => 6],
            ['name' => 'Amethyst', 'sort_order' => 7],
            ['name' => 'CZ (Cubic Zirconia)', 'sort_order' => 8],
            ['name' => 'Polki', 'sort_order' => 9],
            ['name' => 'Kundan', 'sort_order' => 10],
        ];

        foreach ($stoneTypes as $stone) {
            StoneType::firstOrCreate(
                ['name' => $stone['name']],
                ['sort_order' => $stone['sort_order'], 'status' => 'active']
            );
        }
    }
}
