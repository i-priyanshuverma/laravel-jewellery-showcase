<?php

namespace Tests\Feature\Vendor;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariantStatusSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_with_no_variants_is_marked_draft_on_creation(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($vendor)->post(route('vendor.products.store'), [
            'name' => 'Sapphire Pendant',
            'category_id' => $category->id,
            'description' => 'A royal sapphire pendant',
            'status' => 'active',
        ]);

        $product = Product::where('name', 'Sapphire Pendant')->first();
        $this->assertNotNull($product);
        $this->assertEquals(ProductStatus::Draft, $product->status);
        $this->assertEquals(0, $product->variants()->count());
    }

    public function test_adding_first_active_variant_activates_product(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->inactive()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);

        $response = $this->actingAs($vendor)->post(route('vendor.products.variants.store', $product), [
            'sku' => 'SP-001',
            'price' => 12000,
            'stock' => 5,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('vendor.products.show', $product));
        $this->assertEquals(ProductStatus::Active, $product->fresh()->status);
        $this->assertEquals(1, $product->fresh()->variants()->count());
    }

    public function test_deleting_last_variant_marks_product_inactive(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 5]);

        $response = $this->actingAs($vendor)->delete(route('vendor.products.variants.destroy', [$product, $variant]));

        $response->assertRedirect(route('vendor.products.show', $product));
        $this->assertEquals(ProductStatus::Inactive, $product->fresh()->status);
        $this->assertEquals(0, $product->fresh()->variants()->count());
    }

    public function test_out_of_stock_variant_does_not_change_product_status(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->outOfStock()->active()->create(['product_id' => $product->id]);

        $this->assertEquals(ProductStatus::Active, $product->fresh()->status);
        $this->assertEquals(0, $variant->fresh()->stock);
        $this->assertEquals(1, $product->fresh()->variants()->count());
    }
}
