<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    // public function viewAny(User $user)
    // {
    //     return $user->hasRole(['admin', 'manager', 'client']);
    // }

    public function view(User $user, Order $order)
    {
        return $user->hasRole(['admin', 'manager']) || $user->id === $order->user_id;
    }

    public function create(User $user)
    {
        return $user->hasRole(['admin', 'client']);
    }

    public function update(User $user, Order $order)
    {
        return $user->hasRole(['admin', 'manager']) || $user->id === $order->user_id;
    }

    public function delete(User $user, Order $order)
    {
        return $user->hasRole(['admin', 'manager']) || $user->id === $order->user_id;
    }
}
