<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Notifications\OrderStatusChangedNotification;
use App\Http\Requests\OrderRequest;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); 
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->hasRole('admin')) {
            $orders = Order::all();
        } elseif ($user->hasRole('manager')) {
            $orders = Order::where('manager_id', $user->id)->get();
        } else {
            $orders = Order::where('client_id', $user->id)->get();
        }

        return response()->json($orders);
    }

    public function store(OrderRequest $request)
    {

        $validatedData = $request->validated();

        $order = new Order($validatedData);

        $order->save();

        return response()->json($order, 201);
    }

    public function show(Order $order)
    {
        // $this->authorize('view', $order);

        return $order;
    }

    public function update(OrderRequest $request, Order $order)
    {
        if ($request->user()->id !== $order->user_id && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $validatedData = $request->validated();

        // Sprawdź, czy status zmienia się
        $oldStatus = $order->status;

        $order->update($validatedData);

        // Jeśli status się zmienił, wyślij notyfikację
        if ($order->status !== $oldStatus) {
            $order->user->notify(new OrderStatusChangedNotification($order));
        }


        // $order->update($request->only(['pickup_address', 'delivery_address', 'status']));

        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(null, 204);
    }
}
