<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductOversightTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_all_products_and_product_details(): void
    {
        $admin = User::factory()->admin()->create();
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create(['name' => 'Necklaces']);
        $product = Product::factory()->active()->create([
            'user_id' => $vendor->id,
            'category_id' => $category->id,
            'name' => 'Royal Emerald Choker',
        ]);
        ProductVariant::factory()->active()->create([
            'product_id' => $product->id,
            'sku' => 'CHOKER-01',
            'stock' => 3,
            'price' => 150000,
        ]);

        // Products List
        $response = $this->actingAs($admin)->get(route('admin.products.index'));
        $response->assertStatus(200);
        $response->assertSee('Royal Emerald Choker');

        // Product Detail
        $detailResponse = $this->actingAs($admin)->get(route('admin.products.show', $product));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('CHOKER-01');
    }
}
