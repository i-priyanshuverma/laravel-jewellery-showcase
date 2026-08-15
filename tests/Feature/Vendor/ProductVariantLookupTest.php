<?php

namespace Tests\Feature\Vendor;

use App\Models\Category;
use App\Models\Colour;
use App\Models\JewellerySize;
use App\Models\Metal;
use App\Models\Product;
use App\Models\StoneType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariantLookupTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_can_create_variant_with_lookups_and_stones(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create(['name' => 'Rings']);
        $product = Product::factory()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);

        $metal = Metal::create(['name' => 'Gold', 'status' => 'active']);
        $metal->purities()->create(['name' => '18K (750)', 'value' => '18K', 'status' => 'active']);
        Colour::create(['name' => 'Yellow', 'status' => 'active']);
        JewellerySize::create(['category_id' => $category->id, 'name' => 'Size 7', 'value' => '7', 'status' => 'active']);
        $diamond = StoneType::create(['name' => 'Diamond', 'status' => 'active']);

        $response = $this->actingAs($vendor)->post(route('vendor.products.variants.store', $product), [
            'sku' => 'RING-18K-YG-7-DIA',
            'price' => 75000.00,
            'stock' => 5,
            'metal' => 'Gold',
            'purity' => '18K',
            'colour' => 'Yellow',
            'size' => '7',
            'weight' => 4.250,
            'status' => 'active',
            'stones' => [
                [
                    'stone_type_id' => $diamond->id,
                    'carat_weight' => 0.500,
                    'clarity' => 'VS1',
                    'setting_type' => 'Prong',
                ],
            ],
        ]);

        $response->assertRedirect(route('vendor.products.show', $product));

        $this->assertDatabaseHas('product_variants', [
            'sku' => 'RING-18K-YG-7-DIA',
            'metal' => 'Gold',
            'purity' => '18K',
            'colour' => 'Yellow',
            'size' => '7',
        ]);

        $this->assertDatabaseHas('variant_stones', [
            'stone_type_id' => $diamond->id,
            'clarity' => 'VS1',
            'setting_type' => 'Prong',
        ]);
    }
}
