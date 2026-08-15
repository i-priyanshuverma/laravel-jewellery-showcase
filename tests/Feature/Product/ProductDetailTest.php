<?php

namespace Tests\Feature\Product;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_can_view_product_detail_page(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create(['name' => 'Rings']);
        $product = Product::factory()->active()->create([
            'user_id' => $vendor->id,
            'category_id' => $category->id,
            'name' => 'Solitaire Diamond Ring',
        ]);
        $variant = ProductVariant::factory()->active()->create([
            'product_id' => $product->id,
            'sku' => 'RING-SOL-01',
            'metal' => 'Gold',
            'purity' => '18K',
            'price' => 75000,
            'stock' => 5,
        ]);

        $response = $this->get(route('products.show', $product));

        $response->assertStatus(200);
        $response->assertSee('Solitaire Diamond Ring');
        $response->assertSee('RING-SOL-01');
    }

    public function test_cannot_view_inactive_product(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->inactive()->create([
            'user_id' => $vendor->id,
            'category_id' => $category->id,
            'name' => 'Hidden Draft Ring',
        ]);

        $response = $this->get(route('products.show', $product));

        $response->assertStatus(404);
    }
}
