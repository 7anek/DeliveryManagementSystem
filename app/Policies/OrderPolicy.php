<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use App\Models\Team;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        // return $user->hasRole(['admin']) || $user->id === $order->client_id || $user->id === $order->manager_id;
        return $user->hasRole(['admin', 'manager', 'client']);
    }

    public function view(User $user, Order $order)
    {
        return $user->hasRole(['admin']) || $user->id === $order->client_id || $user->id === $order->manager_id;
    }

    public function create(User $user)
    {
        return $user->hasRole(['admin', 'client']);
    }

    public function update(User $user, Order $order)
    {
        return $user->hasRole(['admin']) || $user->id === $order->manager_id;
    }

    public function delete(User $user, Order $order)
    {
        return $user->hasRole(['admin']) || $user->id === $order->manager_id;
    }
}
