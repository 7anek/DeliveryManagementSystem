<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class DeleteOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_order()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $order = Order::factory()->create([
            'client_id' => $user->id,
            'pickup_address' => '123 Pickup St',
            'delivery_address' => '456 Delivery St',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($user)->deleteJson('/api/orders/' . $order->id);

        // dump($response->json());

        $response->assertStatus(204);
        $this->assertDatabaseMissing('orders', [
            'id' => $order->id
        ]);
    }
}
