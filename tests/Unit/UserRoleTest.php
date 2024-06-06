<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_assigned_role()
    {
        $user = User::factory()->create();
        $adminRole = Role::create(['name' => 'admin']);

        $user->assignRole('admin');

        $this->assertTrue($user->hasRole('admin'));
    }
}
