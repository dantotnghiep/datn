<?php

namespace Database\Factories;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'url' => $this->faker->imageUrl(640, 480, 'products'),
            'is_main' => $this->faker->boolean(30),
        ];
    }
}
