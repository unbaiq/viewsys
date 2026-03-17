<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@system.com',
            'role' => 'superadmin',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@system.com',
            'role' => 'admin',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Manager',
            'email' => 'manager@system.com',
            'role' => 'manager',
            'password' => Hash::make('12345678'),
        ]);
    }
}