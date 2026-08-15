<?php

namespace Tests\Unit\Services;

use App\Enums\ReservationStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockReservation;
use App\Models\User;
use App\Services\StockReservationService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class StockReservationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected StockReservationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new StockReservationService;
    }

    public function test_successful_stock_reservation(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 10]);

        $idempotencyKey = Str::uuid()->toString();

        $reservation = $this->service->reserve($variant, 2, 'session-123', $idempotencyKey);

        $this->assertInstanceOf(StockReservation::class, $reservation);
        $this->assertEquals(2, $reservation->quantity);
        $this->assertEquals(ReservationStatus::Active, $reservation->status);

        // Check stock was decremented
        $variant->refresh();
        $this->assertEquals(8, $variant->stock);
    }

    public function test_reservation_idempotency_prevents_duplicate_reservations(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 10]);

        $idempotencyKey = Str::uuid()->toString();

        $res1 = $this->service->reserve($variant, 2, 'session-123', $idempotencyKey);
        $res2 = $this->service->reserve($variant, 2, 'session-123', $idempotencyKey);

        $this->assertEquals($res1->id, $res2->id);

        // Stock should only be decremented once
        $variant->refresh();
        $this->assertEquals(8, $variant->stock);
    }

    public function test_prevents_duplicate_active_reservation_on_same_variant_for_same_session(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant1 = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 10]);
        $variant2 = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 10]);

        // First reservation for Variant 1 -> Should succeed
        $res1 = $this->service->reserve($variant1, 1, 'session-123', Str::uuid()->toString());
        $this->assertEquals(ReservationStatus::Active, $res1->status);

        // Second reservation for Variant 2 (different variant) -> Should SUCCEED
        $res2 = $this->service->reserve($variant2, 1, 'session-123', Str::uuid()->toString());
        $this->assertEquals(ReservationStatus::Active, $res2->status);

        // Attempting another reservation for Variant 1 (same variant) while active -> Should FAIL
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Active hold exists for this variant');

        $this->service->reserve($variant1, 1, 'session-123', Str::uuid()->toString());
    }

    public function test_cannot_reserve_more_than_available_stock(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 1]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Insufficient stock available');

        $this->service->reserve($variant, 2, 'session-123', Str::uuid()->toString());
    }

    public function test_releasing_expired_reservations_restores_stock(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 5]);

        // Create expired reservation manually
        $reservation = StockReservation::factory()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 3,
            'expires_at' => now()->subMinute(),
            'status' => 'active',
        ]);

        $releasedCount = $this->service->releaseExpired();

        $this->assertEquals(1, $releasedCount);

        $reservation->refresh();
        $this->assertEquals(ReservationStatus::Expired, $reservation->status);

        $variant->refresh();
        $this->assertEquals(8, $variant->stock); // 5 + 3 restored
    }
}
