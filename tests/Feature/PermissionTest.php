<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(\Database\Seeders\TestDatabaseSeeder::class);
    }

    public function test_admin_can_manage_orders()
    {
        $admin = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->first();

        $this->actingAs($admin, 'sanctum');

        // Test creating an order
        $response = $this->postJson('/api/orders', [
            'pickup_address' => '123 Pickup St',
            'delivery_address' => '456 Delivery St'
        ]);

        $response->assertStatus(201);

        $orderId = $response->json('id');

        // Test updating the order
        $response = $this->putJson("/api/orders/{$orderId}", [
            'status' => 'in_progress'
        ]);

        $response->assertStatus(200);

        // Test viewing the order
        $response = $this->getJson("/api/orders/{$orderId}");
        $response->assertStatus(200);

        // Test deleting the order
        $response = $this->deleteJson("/api/orders/{$orderId}");
        $response->assertStatus(204);
    }

    public function test_client_can_manage_own_orders()
    {
        $client = User::whereHas('roles', function ($query) {
            $query->where('name', 'client');
        })->first();

        $this->actingAs($client, 'sanctum');

        // Test creating an order
        $response = $this->postJson('/api/orders', [
            'pickup_address' => '789 Pickup St',
            'delivery_address' => '101 Delivery St'
        ]);

        $response->assertStatus(201);

        $orderId = $response->json('id');

        // Test viewing own order
        $response = $this->getJson("/api/orders/{$orderId}");
        $response->assertStatus(200);

        // Test updating own order
        $response = $this->putJson("/api/orders/{$orderId}", [
            'status' => 'completed'
        ]);

        $response->assertStatus(200);

        // Test deleting own order - should fail
        $response = $this->deleteJson("/api/orders/{$orderId}");
        $response->assertStatus(403);
    }

    public function test_client_cannot_manage_others_orders()
    {
        $client = User::whereHas('roles', function ($query) {
            $query->where('name', 'client');
        })->first();

        $admin = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->first();

        $this->actingAs($admin, 'sanctum');

        // Admin creates an order
        $response = $this->postJson('/api/orders', [
            'pickup_address' => '123 Pickup St',
            'delivery_address' => '456 Delivery St'
        ]);

        $response->assertStatus(201);

        $orderId = $response->json('id');

        // Client tries to access the admin's order
        $this->actingAs($client, 'sanctum');

        // Test viewing the admin's order - should fail
        $response = $this->getJson("/api/orders/{$orderId}");
        $response->assertStatus(403);

        // Test updating the admin's order - should fail
        $response = $this->putJson("/api/orders/{$orderId}", [
            'status' => 'completed'
        ]);
        $response->assertStatus(403);

        // Test deleting the admin's order - should fail
        $response = $this->deleteJson("/api/orders/{$orderId}");
        $response->assertStatus(403);
    }
}
