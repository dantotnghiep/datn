<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_statuses')->insert([
            ['id' => 1, 'status_name' => 'pending'],
            ['id' => 2, 'status_name' => 'confirmed'],
            ['id' => 3, 'status_name' => 'shipped'],
            ['id' => 4, 'status_name' => 'completed'],
        ]);
    }
}
