<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\StockReservation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StockReservationFactory extends Factory
{
    protected $model = StockReservation::class;

    public function definition(): array
    {
        return [
            'product_variant_id' => ProductVariant::factory(),
            'session_id' => Str::random(40),
            'idempotency_key' => Str::uuid()->toString(),
            'quantity' => $this->faker->numberBetween(1, 3),
            'expires_at' => now()->addMinutes(15),
            'status' => 'active',
        ];
    }
}
