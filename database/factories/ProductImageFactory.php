<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'path' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=600&auto=format&fit=crop',
            'sort_order' => 0,
        ];
    }
}
