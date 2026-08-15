<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Rings',
            'Necklaces',
            'Earrings',
            'Bracelets',
            'Bangles',
            'Pendants',
            'Chains',
            'Anklets',
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['name' => $cat],
                ['slug' => Str::slug($cat)]
            );
        }
    }
}
