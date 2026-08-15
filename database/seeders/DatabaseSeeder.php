<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            MetalPuritySeeder::class,
            ColourSeeder::class,
            JewellerySizeSeeder::class,
            StoneTypeSeeder::class,
            AdminSeeder::class,
        ]);

        if (! app()->isProduction()) {
            $this->call([
                VendorSeeder::class,
                ProductSeeder::class,
            ]);
        }
    }
}
