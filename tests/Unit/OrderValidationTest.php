<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class OrderValidationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
    }

    public function test_order_requires_pickup_address()
    {
        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/orders', [
            'delivery_address' => '123 Delivery St'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('pickup_address');
    }

    public function test_order_requires_delivery_address()
    {
        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/orders', [
            'pickup_address' => '123 Pickup St'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('delivery_address');
    }
}
