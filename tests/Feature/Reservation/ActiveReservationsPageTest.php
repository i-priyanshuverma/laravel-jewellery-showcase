<?php

namespace Tests\Feature\Reservation;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockReservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActiveReservationsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_can_view_active_reservations_page(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create(['name' => 'Rings']);
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 5]);

        $this->withSession(['reservation_session_id' => 'test-session-123']);

        StockReservation::create([
            'product_variant_id' => $variant->id,
            'session_id' => 'test-session-123',
            'idempotency_key' => 'idemp-1',
            'quantity' => 2,
            'expires_at' => now()->addMinutes(15),
            'status' => 'active',
        ]);

        $response = $this->get(route('reservations.index'));

        $response->assertStatus(200);
        $response->assertSee('My Active Reservations');
        $response->assertSee($product->name);
        $response->assertSee('2 unit(s)');
    }

    public function test_user_can_release_all_active_reservations(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();
        $category = Category::factory()->create(['name' => 'Rings']);
        $product = Product::factory()->active()->create(['user_id' => $vendor->id, 'category_id' => $category->id]);
        $variant = ProductVariant::factory()->active()->create(['product_id' => $product->id, 'stock' => 5]);

        $this->withSession(['reservation_session_id' => 'test-session-456']);

        StockReservation::create([
            'product_variant_id' => $variant->id,
            'session_id' => 'test-session-456',
            'idempotency_key' => 'idemp-2',
            'quantity' => 2,
            'expires_at' => now()->addMinutes(15),
            'status' => 'active',
        ]);

        $response = $this->delete(route('reservations.destroyAll'));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('stock_reservations', [
            'session_id' => 'test-session-456',
            'status' => 'released',
        ]);
    }
}
