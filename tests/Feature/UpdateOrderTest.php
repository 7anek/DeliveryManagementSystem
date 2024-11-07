<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class UpdateOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_update_order()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $order = Order::factory()->create([
            'client_id' => $user->id,
            'pickup_address' => '123 Pickup St',
            'delivery_address' => '456 Delivery St',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($user)->putJson('/api/orders/' . $order->id, [
            'status' => 'in_progress'
        ]);

        $response->assertStatus(403);
        // $this->assertDatabaseHas('orders', [
        //     'id' => $order->id,
        //     'status' => 'in_progress'
        // ]);
    }
}
