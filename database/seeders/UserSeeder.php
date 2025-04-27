<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create user roles first
        $roles = [
            [
                'name' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Khách hàng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhân viên',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('user_roles')->insert($roles);
        
        // Get role IDs
        $adminRoleId = DB::table('user_roles')->where('name', 'Admin')->first()->id;
        $customerRoleId = DB::table('user_roles')->where('name', 'Khách hàng')->first()->id;
        $staffRoleId = DB::table('user_roles')->where('name', 'Nhân viên')->first()->id;
        
        // Create admin user
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '0901234567',
            'role_id' => $adminRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Create staff users
        DB::table('users')->insert([
            'name' => 'Nhân viên 1',
            'email' => 'staff1@example.com',
            'password' => Hash::make('password'),
            'phone' => '0912345678',
            'role_id' => $staffRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('users')->insert([
            'name' => 'Nhân viên 2',
            'email' => 'staff2@example.com',
            'password' => Hash::make('password'),
            'phone' => '0912345679',
            'role_id' => $staffRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Create sample customers
        $customers = [
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'nguyenvana@example.com',
                'password' => Hash::make('password'),
                'phone' => '0923456789',
                'role_id' => $customerRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Trần Thị B',
                'email' => 'tranthib@example.com',
                'password' => Hash::make('password'),
                'phone' => '0934567890',
                'role_id' => $customerRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lê Văn C',
                'email' => 'levanc@example.com',
                'password' => Hash::make('password'),
                'phone' => '0945678901',
                'role_id' => $customerRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Phạm Thị D',
                'email' => 'phamthid@example.com',
                'password' => Hash::make('password'),
                'phone' => '0956789012',
                'role_id' => $customerRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hoàng Văn E',
                'email' => 'hoangvane@example.com',
                'password' => Hash::make('password'),
                'phone' => '0967890123',
                'role_id' => $customerRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ngô Thị F',
                'email' => 'ngothif@example.com',
                'password' => Hash::make('password'),
                'phone' => '0978901234',
                'role_id' => $customerRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vũ Văn G',
                'email' => 'vuvang@example.com',
                'password' => Hash::make('password'),
                'phone' => '0989012345',
                'role_id' => $customerRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('users')->insert($customers);
    }
} 