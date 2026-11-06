<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'role_id' => 1,
            'kitchen_id' => null,
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@restaurant.test',
            'password' => Hash::make('admin123'),
            'is_active' => true,
        ]);

        User::create([
            'role_id' => 2,
            'kitchen_id' => null,
            'name' => 'Kasir',
            'username' => 'kasir',
            'email' => 'kasir@restaurant.test',
            'password' => Hash::make('kasir123'),
            'is_active' => true,
        ]);

        User::create([
            'role_id' => 3,
            'kitchen_id' => 1,
            'name' => 'Dapur Makanan Ringan',
            'username' => 'dapur_ringan',
            'email' => 'dapur.ringan@restaurant.test',
            'password' => Hash::make('dapur123'),
            'is_active' => true,
        ]);

        \App\Models\User::create([
            'role_id' => 3,
            'kitchen_id' => 2,
            'name' => 'Dapur Makanan Berat',
            'username' => 'dapur_berat',
            'email' => 'dapur.berat@restaurant.test',
            'password' => Hash::make('dapur123'),
            'is_active' => true,
        ]); 
        
        \App\Models\User::create([
            'role_id' => 3,
            'kitchen_id' => 3,
            'name' => 'Dapur Minuman',
            'username' => 'dapur_minuman',
            'email' => 'dapur.minuman@restaurant.test',
            'password' => Hash::make('dapur123'),
            'is_active' => true,
        ]);
    }
}