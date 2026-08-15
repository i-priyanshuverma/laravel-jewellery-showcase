<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\JewellerySize;
use Illuminate\Database\Seeder;

class JewellerySizeSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');

        $sizeCatalog = [
            'Rings' => [
                ['name' => 'Ring Size 5', 'value' => '5', 'sort_order' => 1],
                ['name' => 'Ring Size 6', 'value' => '6', 'sort_order' => 2],
                ['name' => 'Ring Size 7', 'value' => '7', 'sort_order' => 3],
                ['name' => 'Ring Size 8', 'value' => '8', 'sort_order' => 4],
                ['name' => 'Ring Size 9', 'value' => '9', 'sort_order' => 5],
                ['name' => 'Ring Size 10', 'value' => '10', 'sort_order' => 6],
                ['name' => 'Ring Size 11', 'value' => '11', 'sort_order' => 7],
                ['name' => 'Adjustable / Free Size', 'value' => 'Adjustable', 'sort_order' => 8],
            ],
            'Necklaces' => [
                ['name' => '14 inch (Collar)', 'value' => '14 inch', 'sort_order' => 1],
                ['name' => '16 inch (Choker)', 'value' => '16 inch', 'sort_order' => 2],
                ['name' => '18 inch (Princess)', 'value' => '18 inch', 'sort_order' => 3],
                ['name' => '20 inch (Matinee)', 'value' => '20 inch', 'sort_order' => 4],
                ['name' => '24 inch (Opera)', 'value' => '24 inch', 'sort_order' => 5],
                ['name' => '30 inch (Rope)', 'value' => '30 inch', 'sort_order' => 6],
            ],
            'Chains' => [
                ['name' => '16 inch', 'value' => '16 inch', 'sort_order' => 1],
                ['name' => '18 inch', 'value' => '18 inch', 'sort_order' => 2],
                ['name' => '20 inch', 'value' => '20 inch', 'sort_order' => 3],
                ['name' => '22 inch', 'value' => '22 inch', 'sort_order' => 4],
                ['name' => '24 inch', 'value' => '24 inch', 'sort_order' => 5],
            ],
            'Bangles' => [
                ['name' => '2-2 (54mm)', 'value' => '2-2', 'sort_order' => 1],
                ['name' => '2-4 (57mm)', 'value' => '2-4', 'sort_order' => 2],
                ['name' => '2-6 (60mm)', 'value' => '2-6', 'sort_order' => 3],
                ['name' => '2-8 (63.5mm)', 'value' => '2-8', 'sort_order' => 4],
                ['name' => '2-10 (67mm)', 'value' => '2-10', 'sort_order' => 5],
            ],
            'Bracelets' => [
                ['name' => '6.0 inch (Small)', 'value' => '6.0 inch', 'sort_order' => 1],
                ['name' => '6.5 inch (Medium)', 'value' => '6.5 inch', 'sort_order' => 2],
                ['name' => '7.0 inch (Standard)', 'value' => '7.0 inch', 'sort_order' => 3],
                ['name' => '7.5 inch (Large)', 'value' => '7.5 inch', 'sort_order' => 4],
                ['name' => '8.0 inch (Extra Large)', 'value' => '8.0 inch', 'sort_order' => 5],
            ],
            'Anklets' => [
                ['name' => '9.0 inch', 'value' => '9.0 inch', 'sort_order' => 1],
                ['name' => '9.5 inch', 'value' => '9.5 inch', 'sort_order' => 2],
                ['name' => '10.0 inch', 'value' => '10.0 inch', 'sort_order' => 3],
                ['name' => '10.5 inch', 'value' => '10.5 inch', 'sort_order' => 4],
                ['name' => '11.0 inch', 'value' => '11.0 inch', 'sort_order' => 5],
            ],
            'Earrings' => [
                ['name' => 'Standard / One Size', 'value' => 'Standard', 'sort_order' => 1],
                ['name' => 'Small (10mm)', 'value' => 'Small', 'sort_order' => 2],
                ['name' => 'Medium (15mm)', 'value' => 'Medium', 'sort_order' => 3],
                ['name' => 'Large (20mm)', 'value' => 'Large', 'sort_order' => 4],
            ],
            'Pendants' => [
                ['name' => 'Standard / One Size', 'value' => 'Standard', 'sort_order' => 1],
                ['name' => 'Small (10mm)', 'value' => 'Small', 'sort_order' => 2],
                ['name' => 'Medium (15mm)', 'value' => 'Medium', 'sort_order' => 3],
                ['name' => 'Large (20mm)', 'value' => 'Large', 'sort_order' => 4],
            ],
        ];

        foreach ($sizeCatalog as $categoryName => $sizes) {
            $cat = $categories->get($categoryName);
            $catId = $cat?->id;

            foreach ($sizes as $size) {
                JewellerySize::firstOrCreate(
                    ['category_id' => $catId, 'value' => $size['value']],
                    ['name' => $size['name'], 'sort_order' => $size['sort_order'], 'status' => 'active']
                );
            }
        }
    }
}
