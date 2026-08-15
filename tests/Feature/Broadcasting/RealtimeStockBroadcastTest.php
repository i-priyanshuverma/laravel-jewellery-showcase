<?php

namespace Tests\Feature\Broadcasting;

use App\Events\ProductStockUpdated;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockReservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class RealtimeStockBroadcastTest extends TestCase
{
    use RefreshDatabase;

    public function test_reserving_stock_broadcasts_product_stock_updated_event(): void
    {
        Event::fake([ProductStockUpdated::class]);

        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 10]);

        $response = $this->post(route('reservations.store'), [
            'product_variant_id' => $variant->id,
            'quantity' => 3,
            'idempotency_key' => Str::uuid()->toString(),
        ]);

        $response->assertRedirect();

        Event::assertDispatched(ProductStockUpdated::class, function ($event) use ($product, $variant, $vendor) {
            return $event->productId === $product->id
                && $event->variantId === $variant->id
                && $event->vendorId === $vendor->id
                && $event->stock === 7
                && $event->activeHoldsCount === 3;
        });
    }

    public function test_releasing_stock_broadcasts_product_stock_updated_event(): void
    {
        Event::fake([ProductStockUpdated::class]);

        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 5]);

        $reservation = StockReservation::create([
            'product_variant_id' => $variant->id,
            'session_id' => 'test-session-id',
            'idempotency_key' => Str::uuid()->toString(),
            'quantity' => 2,
            'expires_at' => now()->addMinutes(15),
            'status' => 'active',
        ]);

        $response = $this->withSession(['reservation_session_id' => 'test-session-id'])
            ->delete(route('reservations.destroy', $reservation));

        $response->assertRedirect();

        Event::assertDispatched(ProductStockUpdated::class, function ($event) use ($product, $variant) {
            return $event->productId === $product->id
                && $event->variantId === $variant->id
                && $event->stock === 7;
        });
    }

    public function test_releasing_expired_reservations_broadcasts_stock_updates(): void
    {
        Event::fake([ProductStockUpdated::class]);

        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 2]);

        StockReservation::create([
            'product_variant_id' => $variant->id,
            'session_id' => 'session-expired',
            'idempotency_key' => Str::uuid()->toString(),
            'quantity' => 3,
            'expires_at' => now()->subMinutes(5),
            'status' => 'active',
        ]);

        $this->artisan('reservations:expire')->assertSuccessful();

        Event::assertDispatched(ProductStockUpdated::class, function ($event) use ($product, $variant) {
            return $event->productId === $product->id
                && $event->variantId === $variant->id
                && $event->stock === 5;
        });
    }

    public function test_event_channels_and_payload_structure(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 8]);

        $event = new ProductStockUpdated($variant);

        $channels = $event->broadcastOn();
        $this->assertCount(3, $channels);
        $this->assertEquals('products.'.$product->id, $channels[0]->name);
        $this->assertEquals('private-vendor.'.$vendor->id, $channels[1]->name);
        $this->assertEquals('private-admin.inventory', $channels[2]->name);

        $payload = $event->broadcastWith();
        $this->assertEquals($product->id, $payload['productId']);
        $this->assertEquals($vendor->id, $payload['vendorId']);
        $this->assertEquals($variant->id, $payload['variantId']);
        $this->assertEquals(8, $payload['stock']);
        $this->assertEquals('ProductStockUpdated', $event->broadcastAs());
    }
}
