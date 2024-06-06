<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_orders_route()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user, 'sanctum')->get('/api/orders');

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_orders_route()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->get('/api/orders');

        $response->assertStatus(403);
    }
}
