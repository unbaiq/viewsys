<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // reset cache
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | CREATE ROLES
        |--------------------------------------------------------------------------
        */
        $roles = ['superadmin', 'admin', 'manager'];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN
        |--------------------------------------------------------------------------
        */
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@thelocads.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('12345678'),
                'is_active' => true,
            ]
        );

        $superAdmin->syncRoles(['superadmin']);

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */
        $admin = User::updateOrCreate(
            ['email' => 'admin@system.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
                'is_active' => true,
            ]
        );

        $admin->syncRoles(['admin']);

        /*
        |--------------------------------------------------------------------------
        | MANAGER
        |--------------------------------------------------------------------------
        */
        $manager = User::updateOrCreate(
            ['email' => 'manager@system.com'],
            [
                'name' => 'Manager',
                'password' => Hash::make('12345678'),
                'is_active' => true,
            ]
        );

        $manager->syncRoles(['manager']);
    }
}