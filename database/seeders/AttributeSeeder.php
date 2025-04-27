<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tạo Size attribute
        $sizeId = DB::table('attributes')->insertGetId([
            'name' => 'Size',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Tạo Size values
        $sizeValues = ['S', 'M', 'L'];
        foreach ($sizeValues as $value) {
            DB::table('attribute_values')->insert([
                'attribute_id' => $sizeId,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Tạo Color attribute
        $colorId = DB::table('attributes')->insertGetId([
            'name' => 'Color',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Tạo Color values
        $colorValues = ['Red', 'Blue', 'Black'];
        foreach ($colorValues as $value) {
            DB::table('attribute_values')->insert([
                'attribute_id' => $colorId,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Tạo Material attribute
        $materialId = DB::table('attributes')->insertGetId([
            'name' => 'Material',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Tạo Material values
        $materialValues = ['Cotton', 'Polyester', 'Wool'];
        foreach ($materialValues as $value) {
            DB::table('attribute_values')->insert([
                'attribute_id' => $materialId,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
} 