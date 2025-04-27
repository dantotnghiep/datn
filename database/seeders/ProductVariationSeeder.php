<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all products
        $products = DB::table('products')->get();
        
        // Get all attribute values for size and color
        $sizeAttribute = DB::table('attributes')->where('name', 'Size')->first();
        $sizeValues = DB::table('attribute_values')->where('attribute_id', $sizeAttribute->id)->get();
        
        $colorAttribute = DB::table('attributes')->where('name', 'Color')->first();
        $colorValues = DB::table('attribute_values')->where('attribute_id', $colorAttribute->id)->get();
        
        foreach ($products as $product) {
            // Create 3-5 variations per product with different size/color combinations
            $variationCount = rand(3, 5);
            
            for ($i = 1; $i <= $variationCount; $i++) {
                // Select random size and color
                $sizeValue = $sizeValues->random();
                $colorValue = $colorValues->random();
                
                $sku = $product->sku . '-' . substr($sizeValue->value, 0, 1) . substr($colorValue->value, 0, 1);
                $basePrice = rand(10000, 50000) * 100; // 1,000,000 - 5,000,000 VND
                $salePrice = rand(8000, $basePrice/100) * 100; // 800,000 - basePrice VND
                
                // Insert product variation
                $variationId = DB::table('product_variations')->insertGetId([
                    'product_id' => $product->id,
                    'sku' => $sku . $i, // Make sure SKU is unique by adding index
                    'name' => "{$product->name} - {$colorValue->value} / {$sizeValue->value}",
                    'price' => $basePrice,
                    'sale_price' => $salePrice,
                    'stock' => rand(10, 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Connect variation with attribute values
                DB::table('attribute_value_variations')->insert([
                    'product_variation_id' => $variationId,
                    'attribute_value_id' => $sizeValue->id,
                ]);
                
                DB::table('attribute_value_variations')->insert([
                    'product_variation_id' => $variationId,
                    'attribute_value_id' => $colorValue->id,
                ]);
            }
        }
    }
} 