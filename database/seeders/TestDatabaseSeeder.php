<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'client']);

        // Assign permissions to roles (if you have specific permissions)
        // You can create and assign specific permissions here if needed
        // Example: $permission = Permission::create(['name' => 'manage orders']);
        // $adminRole->givePermissionTo($permission);
        // $managerRole->givePermissionTo($permission);
    }
}
