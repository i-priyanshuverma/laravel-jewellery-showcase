<?php

namespace Database\Seeders;

use App\Models\Colour;
use Illuminate\Database\Seeder;

class ColourSeeder extends Seeder
{
    public function run(): void
    {
        $colours = [
            ['name' => 'Yellow', 'sort_order' => 1],
            ['name' => 'Rose', 'sort_order' => 2],
            ['name' => 'White', 'sort_order' => 3],
            ['name' => 'Two-Tone', 'sort_order' => 4],
            ['name' => 'Oxidized', 'sort_order' => 5],
            ['name' => 'Green', 'sort_order' => 6],
            ['name' => 'Black (Rhodium)', 'sort_order' => 7],
        ];

        foreach ($colours as $colour) {
            Colour::firstOrCreate(
                ['name' => $colour['name']],
                ['sort_order' => $colour['sort_order'], 'status' => 'active']
            );
        }
    }
}
