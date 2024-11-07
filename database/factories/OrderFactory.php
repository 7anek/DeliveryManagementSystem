<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => User::factory()->afterCreating(function (User $user) {
                $user->assignRole('client');
            }),
            'manager_id' => User::factory()->afterCreating(function (User $user) {
                $user->assignRole('manager'); 
            }),
            'pickup_address' => $this->faker->address,
            'pickup_latitude' => $this->faker->latitude,
            'pickup_longitude' => $this->faker->longitude,
            'pickup_at' => $this->faker->dateTimeBetween('-1 days', 'now'),
            'current_address' => $this->faker->address,
            'current_latitude' => $this->faker->latitude,
            'current_longitude' => $this->faker->longitude,
            'delivery_address' => $this->faker->address,
            'delivery_latitude' => $this->faker->latitude,
            'delivery_longitude' => $this->faker->longitude,
            'delivered_at' => $this->faker->optional()->dateTimeBetween('now', '+1 days'), 
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed', 'canceled']),
        ];
    }
}
