<?php

namespace Tests\Unit;

use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_roles_are_created()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $managerRole = Role::create(['name' => 'manager']);
        $clientRole = Role::create(['name' => 'client']);

        $this->assertDatabaseHas('roles', ['name' => 'admin']);
        $this->assertDatabaseHas('roles', ['name' => 'manager']);
        $this->assertDatabaseHas('roles', ['name' => 'client']);
    }
}
