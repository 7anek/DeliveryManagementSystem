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
        Notification::fake();

        // Utwórz użytkownika i zamówienie
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        // Wywołaj metodę update z nowym statusem poprzez żądanie HTTP
        $response = $this->actingAs($user)->patchJson(route('orders.update', $order->id), [
            'status' => 'in_progress',
        ]);

        $response->assertStatus(200);

        // Sprawdź, czy notyfikacja została wysłana
        Notification::assertSentTo(
            [$user], OrderStatusChangedNotification::class
        );
    }
}
