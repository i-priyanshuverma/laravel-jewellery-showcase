<?php

namespace Tests\Feature\Reservation;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockReservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class StockReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_can_reserve_variant_stock(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 5]);

        $idempotencyKey = Str::uuid()->toString();

        $response = $this->post(route('reservations.store'), [
            'product_variant_id' => $variant->id,
            'quantity' => 2,
            'idempotency_key' => $idempotencyKey,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals(3, $variant->fresh()->stock);

        $reservation = StockReservation::where('idempotency_key', $idempotencyKey)->first();
        $this->assertNotNull($reservation);
        $this->assertEquals(2, $reservation->quantity);
    }

    public function test_cannot_reserve_when_stock_is_insufficient(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 1]);

        $response = $this->post(route('reservations.store'), [
            'product_variant_id' => $variant->id,
            'quantity' => 5,
            'idempotency_key' => Str::uuid()->toString(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals(1, $variant->fresh()->stock);
    }
}
