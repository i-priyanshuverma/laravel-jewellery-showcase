<?php

namespace Tests\Feature\Reservation;

use App\Enums\ReservationStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\StockReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CascadingReservationTest extends TestCase
{
    use RefreshDatabase;

    protected StockReservationService $reservationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reservationService = new StockReservationService;
    }

    public function test_suspending_vendor_releases_active_reservations_immediately(): void
    {
        $admin = User::factory()->admin()->create();
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 10]);

        // Reserve 3 items
        $reservation = $this->reservationService->reserve($variant, 3, 'sess-1', Str::uuid()->toString());
        $this->assertEquals(7, $variant->fresh()->stock);

        // Admin suspends vendor
        $this->actingAs($admin)->patch(route('admin.vendors.suspend', $vendor));

        // Reservation should be released & stock restored to 10
        $this->assertEquals(ReservationStatus::Released, $reservation->fresh()->status);
        $this->assertEquals(10, $variant->fresh()->stock);
    }

    public function test_deleting_product_releases_active_reservations_immediately(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 10]);

        $reservation = $this->reservationService->reserve($variant, 4, 'sess-2', Str::uuid()->toString());
        $this->assertEquals(6, $variant->fresh()->stock);

        // Vendor deletes product
        $this->actingAs($vendor)->delete(route('vendor.products.destroy', $product));

        $this->assertEquals(ReservationStatus::Released, $reservation->fresh()->status);
        $this->assertEquals(10, $variant->fresh()->stock);
        $this->assertTrue($product->fresh()->trashed());
    }

    public function test_deactivating_variant_releases_active_reservations_immediately(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 5]);

        $reservation = $this->reservationService->reserve($variant, 2, 'sess-3', Str::uuid()->toString());
        $this->assertEquals(3, $variant->fresh()->stock);

        // Vendor updates variant status to inactive
        $this->actingAs($vendor)->put(route('vendor.products.variants.update', [$product, $variant]), [
            'sku' => $variant->sku,
            'price' => $variant->price,
            'stock' => 3,
            'status' => 'inactive',
        ]);

        $this->assertEquals(ReservationStatus::Released, $reservation->fresh()->status);
        $this->assertEquals(5, $variant->fresh()->stock); // 3 + 2 restored
    }
}
