<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_order()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

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
            'client_id' => $user->id, 
        ];

        $response = $this->actingAs($user)->postJson('/api/orders', $sampleOrderData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            'pickup_address' => '123 Pickup St',
            'delivery_address' => '456 Delivery St'
        ]);
    }
}