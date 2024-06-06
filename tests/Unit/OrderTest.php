<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_creation()
    {
        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'pickup_address' => '123 Pickup St',
            'delivery_address' => '456 Delivery St',
            'status' => 'pending'
        ]);

        $this->assertDatabaseHas('orders', [
            'pickup_address' => '123 Pickup St',
            'delivery_address' => '456 Delivery St'
        ]);
    }
}
