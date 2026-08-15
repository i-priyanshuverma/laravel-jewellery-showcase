<?php

namespace Tests\Feature\Vendor;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_approved_vendor_can_create_product(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($vendor)->post(route('vendor.products.store'), [
            'name' => 'Gold Diamond Necklace',
            'category_id' => $category->id,
            'description' => 'A stunning 18K necklace',
            'status' => 'active',
        ]);

        $product = Product::where('name', 'Gold Diamond Necklace')->first();
        $this->assertNotNull($product);
        $this->assertEquals($vendor->id, $product->user_id);
        $response->assertRedirect(route('vendor.products.show', $product));
    }

    public function test_pending_vendor_cannot_access_product_creation(): void
    {
        $pendingVendor = User::factory()->vendor()->pending()->create();

        $response = $this->actingAs($pendingVendor)->get(route('vendor.products.create'));

        $response->assertRedirect(route('vendor.dashboard'));
    }

    public function test_vendor_cannot_manage_another_vendors_product(): void
    {
        $vendor1 = User::factory()->vendor()->approved()->create();
        $vendor2 = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();

        $product = Product::factory()->create(['user_id' => $vendor1->id, 'category_id' => $category->id]);

        $response = $this->actingAs($vendor2)->get(route('vendor.products.show', $product));

        $response->assertStatus(403);
    }
}
