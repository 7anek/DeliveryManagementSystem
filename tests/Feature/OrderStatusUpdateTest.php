<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OrderStatusUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function client_receives_notification_on_order_status_change()
    {
        // Utwórz użytkownika i zamówienie
        $user = User::factory()->create();
        $user->assignRole('client');
        $order = Order::factory()->create(['client_id' => $user->id, 'status' => 'pending']);

        // Zaloguj użytkownika
        $this->actingAs($user);

        // Użyj facade Notification do "śledzenia" wysyłanych notyfikacji
        Notification::fake();

        // Zaktualizuj status zamówienia przez endpoint API
        $this->putJson("/api/orders/{$order->id}", ['status' => 'in_progress'])
            ->assertStatus(200);

        // Sprawdź, czy notyfikacja została wysłana
        Notification::assertSentTo(
            [$user], OrderStatusChangedNotification::class
        );
    }
}
