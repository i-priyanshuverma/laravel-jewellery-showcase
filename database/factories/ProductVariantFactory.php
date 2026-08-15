<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'sku' => 'SKU-'.strtoupper($this->faker->unique()->bothify('??###??')),
            'price' => $this->faker->randomFloat(2, 5000, 250000),
            'stock' => $this->faker->numberBetween(1, 25),
            'metal' => $this->faker->randomElement(['Gold', 'Rose Gold', 'White Gold', 'Platinum', 'Silver']),
            'purity' => $this->faker->randomElement(['18K', '22K', '24K', '925']),
            'colour' => $this->faker->randomElement(['Yellow', 'Rose', 'White']),
            'size' => (string) $this->faker->numberBetween(5, 12),
            'weight' => $this->faker->randomFloat(3, 2, 25),
            'status' => 'active',
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }
}
