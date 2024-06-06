<?php
// tests/Unit/OrderValidationTest.php

namespace Tests\Unit;

use Tests\TestCase;

class OrderValidationTest extends TestCase
{
    public function test_order_requires_pickup_address()
    {
        $response = $this->postJson('/api/orders', [
            'delivery_address' => '123 Delivery St'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('pickup_address');
    }

    public function test_order_requires_delivery_address()
    {
        $response = $this->postJson('/api/orders', [
            'pickup_address' => '123 Pickup St'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('delivery_address');
    }
}
