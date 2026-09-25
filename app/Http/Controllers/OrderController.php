<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'items' => 'required|array',
            'total_amount' => 'required|numeric',
            'delivery_address' => 'required|string',
        ]);
        $orderNumber = 'ORD-' .strtoupper(substr(uniqid(), 7));

        Order::create([
            'status' => 'Pending',
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'items' => $request->items,
            'total_amount' => $request->total_amount,
            'delivery_address' => $request->delivery_address,
            'order_number' => $orderNumber,
        ]);
        return response()->json(['message' => 'Order created successfully', 'order_number' => $orderNumber], 201);
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Pending,Processing,Out for Delivery,Completed,Cancelled',
        ]);
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();
        return response()->json(['message' => 'Order status updated successfully']);
    }
}