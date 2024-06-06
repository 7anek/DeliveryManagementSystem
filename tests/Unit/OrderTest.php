<?php
// tests/Unit/OrderTest.php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_creation()
    {
        $order = Order::create([
            'user_id' => 1,
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
