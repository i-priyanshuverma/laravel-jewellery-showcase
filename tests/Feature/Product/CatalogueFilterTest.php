<?php

namespace Tests\Feature\Product;

use App\Models\Category;
use App\Models\Metal;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StoneType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogueFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalogue_filters_by_metal_and_stone_type(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create(['name' => 'Rings']);

        $metalGold = Metal::create(['name' => 'Gold', 'status' => 'active']);
        $metalGold->purities()->create(['name' => '18K', 'value' => '18K', 'status' => 'active']);
        $metalPlatinum = Metal::create(['name' => 'Platinum', 'status' => 'active']);
        $metalPlatinum->purities()->create(['name' => '950', 'value' => '950', 'status' => 'active']);

        $diamond = StoneType::create(['name' => 'Diamond', 'status' => 'active']);
        $ruby = StoneType::create(['name' => 'Ruby', 'status' => 'active']);

        // Product 1: Gold + Diamond
        $product1 = Product::factory()->active()->create([
            'user_id' => $vendor->id,
            'category_id' => $category->id,
            'name' => 'Gold Solitaire Diamond Ring',
        ]);
        $variant1 = ProductVariant::factory()->active()->create([
            'product_id' => $product1->id,
            'metal' => 'Gold',
            'purity' => '18K',
            'stock' => 5,
        ]);
        $variant1->stones()->create(['stone_type_id' => $diamond->id, 'carat_weight' => 0.5]);

        // Product 2: Platinum + Ruby
        $product2 = Product::factory()->active()->create([
            'user_id' => $vendor->id,
            'category_id' => $category->id,
            'name' => 'Platinum Royal Ruby Ring',
        ]);
        $variant2 = ProductVariant::factory()->active()->create([
            'product_id' => $product2->id,
            'metal' => 'Platinum',
            'purity' => '950',
            'stock' => 3,
        ]);
        $variant2->stones()->create(['stone_type_id' => $ruby->id, 'carat_weight' => 1.2]);

        // Query for Gold only
        $response = $this->get(route('products.index', ['metal' => 'Gold']));
        $response->assertStatus(200);
        $response->assertSee('Gold Solitaire Diamond Ring');
        $response->assertDontSee('Platinum Royal Ruby Ring');

        // Query for Diamond stone only
        $response = $this->get(route('products.index', ['stone_type' => 'Diamond']));
        $response->assertStatus(200);
        $response->assertSee('Gold Solitaire Diamond Ring');
        $response->assertDontSee('Platinum Royal Ruby Ring');

        // Query for Ruby stone only
        $response = $this->get(route('products.index', ['stone_type' => 'Ruby']));
        $response->assertStatus(200);
        $response->assertSee('Platinum Royal Ruby Ring');
        $response->assertDontSee('Gold Solitaire Diamond Ring');
    }
}
