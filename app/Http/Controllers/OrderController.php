<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'pickup_address' => 'required|string|max:255',
            'delivery_address' => 'required|string|max:255',
        ]);

        $order = Order::create([
            'user_id' => auth()->id(),
            'pickup_address' => $validatedData['pickup_address'],
            'delivery_address' => $validatedData['delivery_address'],
            'status' => 'pending',
        ]);

        return response()->json($order, 201);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validatedData = $request->validate([
            'pickup_address' => 'sometimes|string|max:255',
            'delivery_address' => 'sometimes|string|max:255',
            'status' => 'sometimes|string|in:pending,in_progress,delivered',
        ]);

        $order->update($validatedData);

        return response()->json($order, 200);
    }

    public function destroy($id)
    {
        Order::destroy($id);
        return response()->json(null, 204);
    }
}
