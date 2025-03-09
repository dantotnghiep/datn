<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Variation;
use Illuminate\Database\Eloquent\Factories\Factory;

class VariationFactory extends Factory
{
    protected $model = Variation::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id ?? Product::factory(),
            'sku' => strtoupper($this->faker->unique()->bothify('??##??##')),
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'sale_price' => $this->faker->optional()->randomFloat(2, 50, 900),
            'sale_start' => $this->faker->optional()->dateTimeBetween('-1 months', '+1 months'),
            'sale_end' => $this->faker->optional()->dateTimeBetween('+1 months', '+3 months'),
            'stock' => $this->faker->numberBetween(0, 100),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
