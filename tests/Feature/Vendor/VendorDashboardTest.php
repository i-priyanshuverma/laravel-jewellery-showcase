<?php

namespace Tests\Feature\Vendor;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_approved_vendor_can_view_dashboard_metrics(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 10]);

        $response = $this->actingAs($vendor)->get(route('vendor.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Total Products');
        $response->assertSee('Account Active');
    }

    public function test_pending_vendor_sees_pending_banner(): void
    {
        $vendor = User::factory()->vendor()->pending()->create();

        $response = $this->actingAs($vendor)->get(route('vendor.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Account Approval Pending');
    }
}
