<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Variation;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::factory(10)->create()->each(function ($product) {
            // Mỗi sản phẩm có 3 biến thể
            Variation::factory(3)->create([
                'product_id' => $product->id
            ]);
        });
    }
}

