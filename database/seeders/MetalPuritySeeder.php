<?php

namespace Database\Seeders;

use App\Models\Metal;
use App\Models\Purity;
use Illuminate\Database\Seeder;

class MetalPuritySeeder extends Seeder
{
    public function run(): void
    {
        $metals = [
            'Gold' => [
                ['name' => '24K (999)', 'value' => '24K', 'sort_order' => 1],
                ['name' => '22K (916)', 'value' => '22K', 'sort_order' => 2],
                ['name' => '18K (750)', 'value' => '18K', 'sort_order' => 3],
                ['name' => '14K (585)', 'value' => '14K', 'sort_order' => 4],
            ],
            'Silver' => [
                ['name' => '999 Fine Silver', 'value' => '999', 'sort_order' => 1],
                ['name' => '925 Sterling Silver', 'value' => '925', 'sort_order' => 2],
            ],
            'Platinum' => [
                ['name' => '950 Platinum', 'value' => '950', 'sort_order' => 1],
                ['name' => '900 Platinum', 'value' => '900', 'sort_order' => 2],
            ],
            'Rose Gold' => [
                ['name' => '18K Rose Gold', 'value' => '18K', 'sort_order' => 1],
                ['name' => '14K Rose Gold', 'value' => '14K', 'sort_order' => 2],
            ],
            'White Gold' => [
                ['name' => '18K White Gold', 'value' => '18K', 'sort_order' => 1],
                ['name' => '14K White Gold', 'value' => '14K', 'sort_order' => 2],
            ],
        ];

        $sortOrder = 1;
        foreach ($metals as $metalName => $purities) {
            $metal = Metal::firstOrCreate(
                ['name' => $metalName],
                ['sort_order' => $sortOrder++, 'status' => 'active']
            );

            foreach ($purities as $purity) {
                Purity::firstOrCreate(
                    ['metal_id' => $metal->id, 'value' => $purity['value']],
                    ['name' => $purity['name'], 'sort_order' => $purity['sort_order'], 'status' => 'active']
                );
            }
        }
    }
}
