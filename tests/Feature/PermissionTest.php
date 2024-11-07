<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Order;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_orders()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'sanctum');

        $sampleOrderData = [
            'pickup_address' => '123 Pickup St',
            'pickup_latitude' => 40.712776,
            'pickup_longitude' => -74.005974,
            'current_address' => '789 Current St',
            'current_latitude' => 40.713776,
            'current_longitude' => -74.006974,
            'delivery_address' => '456 Delivery St',
            'delivery_latitude' => 40.713776,
            'delivery_longitude' => -74.007974,
            'pickup_at' => now()->addHours(2), 
            'delivered_at' => null, 
            'status' => 'pending', 
            'client_id' => $admin->id, 
        ];

        // Test creating an order
        $response = $this->postJson('/api/orders', $sampleOrderData);

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

    public function test_manager_can_manage_own_orders()
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $this->actingAs($manager, 'sanctum');

        // Test creating an order for the manager
        $order = Order::factory()->make(['manager_id' => $manager->id, 'status' => 'pending']);

        // Manager can update their own order
        $response = $this->putJson("/api/orders/{$order->id}", [
            'status' => 'in_progress'
        ]);
        $response->assertStatus(200);
    }

    public function test_manager_cannot_modify_others_orders()
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $this->actingAs($manager, 'sanctum');

        // Test creating an order for another user
        $otherUser = User::factory()->create();
        $order = Order::factory()->make(['manager_id' => $otherUser->id, 'status' => 'pending']);

        // Manager cannot update another user's order
        $response = $this->putJson("/api/orders/{$order->id}", [
            'status' => 'in_progress'
        ]);
        $response->assertStatus(403); // Forbidden
    }

    public function test_client_cannt_manage_own_orders()
{
    $client = User::factory()->create();
    $client->assignRole('client');

    $this->actingAs($client, 'sanctum');

    $sampleOrderData = [
        'pickup_address' => '123 Pickup St',
        'pickup_latitude' => 40.712776,
        'pickup_longitude' => -74.005974,
        'current_address' => '789 Current St',
        'current_latitude' => 40.713776,
        'current_longitude' => -74.006974,
        'delivery_address' => '456 Delivery St',
        'delivery_latitude' => 40.713776,
        'delivery_longitude' => -74.007974,
        'pickup_at' => now()->addHours(2), 
        'delivered_at' => null, 
        'status' => 'pending', 
        'client_id' => $client->id, 
    ];

    // Test creating an order
    $response = $this->postJson('/api/orders', $sampleOrderData);

    $response->assertStatus(403);

    // $orderId = $response->json('id');

    // // Test viewing own order
    // $response = $this->getJson("/api/orders/{$orderId}");
    // $response->assertStatus(200);

    // // Sprawdź, czy klient nie może zaktualizować zamówienia
    // $response = $this->putJson("/api/orders/{$orderId}", [
    //     'status' => 'completed'
    // ]);
    
    // // Upewnij się, że otrzymujesz błąd 403 lub inny odpowiedni błąd
    // $response->assertStatus(403);

    // // Test deleting own order
    // $response = $this->deleteJson("/api/orders/{$orderId}");
    // $response->assertStatus(403); // lub inny odpowiedni błąd, jeśli klient nie ma uprawnień
}

    public function test_client_cannot_manage_others_orders()
    {
        $client = User::factory()->create();
        $client->assignRole('client');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'sanctum');

        $sampleOrderData = [
            'pickup_address' => '123 Pickup St',
            'pickup_latitude' => 40.712776,
            'pickup_longitude' => -74.005974,
            'current_address' => '789 Current St',
            'current_latitude' => 40.713776,
            'current_longitude' => -74.006974,
            'delivery_address' => '456 Delivery St',
            'delivery_latitude' => 40.713776,
            'delivery_longitude' => -74.007974,
            'pickup_at' => now()->addHours(2), 
            'delivered_at' => null, 
            'status' => 'pending', 
            'client_id' => $client->id, 
        ];

        // Admin creates an order
        $response = $this->postJson('/api/orders', $sampleOrderData);

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
