<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole(['admin', 'manager'])) {
            return Order::all();
        } elseif (Auth::user()->hasRole('client')) {
            return Auth::user()->orders;
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    public function store(Request $request)
    {
        // $validatedData = $request->validate([
        //     'pickup_address' => 'required|string|max:255',
        //     'delivery_address' => 'required|string|max:255',
        // ]);

        // $order = Order::create([
        //     'user_id' => Auth::id(),
        //     'pickup_address' => $validatedData['pickup_address'],
        //     'delivery_address' => $validatedData['delivery_address'],
        //     'status' => 'pending',
        // ]);

        // $order = Order::create([
        //     'user_id' => Auth::id(),
        //     'pickup_address' => $request->pickup_address,
        //     'delivery_address' => $request->delivery_address,
        //     'status' => 'pending'
        // ]);

        // Gate::authorize(Auth::user());

        $validatedData = $request->validate([
            'pickup_address' => 'required|string|max:255',
            'delivery_address' => 'required|string|max:255',
            // 'status' => 'required|string|in:pending,in_progress,delivered',
        ]);

        $order = new Order($validatedData);
        $order->user_id = Auth::id();
        $order->save();

        return response()->json($order, 201);
    }

    public function show(Order $order)
    {
        // $this->authorize('view', $order);

        return $order;
    }

    public function update(Request $request, Order $order)
    {
        // $validatedData = $request->validate([
        //     'pickup_address' => 'sometimes|string|max:255',
        //     'delivery_address' => 'sometimes|string|max:255',
        //     'status' => 'sometimes|string|in:pending,in_progress,delivered',
        // ]);

        // $order->update($validatedData);
        $order->update($request->only(['pickup_address', 'delivery_address', 'status']));

        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(null, 204);
    }
}
