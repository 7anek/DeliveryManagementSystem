<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OrderStatusChangedNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sends_notification_when_order_status_changes()
    {
        // Utwórz użytkownika i zamówienie
        $user = User::factory()->create();

        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        // Użyj facade Notification do "śledzenia" wysyłanych notyfikacji
        Notification::fake();

        // Zmień status zamówienia
        $order->update(['status' => 'in_progress']);

        // Sprawdź, czy notyfikacja została wysłana
        Notification::assertSentTo(
            [$user], OrderStatusChangedNotification::class
        );
    }
}
