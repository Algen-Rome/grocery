<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB; // 🌟 MANDATORY FOR DEDUCTIONS & TRANSACTIONS

class OrderController extends Controller
{
    /**
     * Step 1: Initialize the Order Record (Saved as Pending)
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'items' => 'required|array',
            'total_amount' => 'required|numeric',
            'delivery_address' => 'required|string',
        ]);

        $orderNumber = 'ORD-' . strtoupper(substr(uniqid(), 7));

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

    /**
     * Step 2: Transition Status & Deduct Stock automatically when marked "Completed"
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Pending,Processing,Out for Delivery,Completed,Cancelled',
        ]);

        // Wrap the update process inside a database transaction
        DB::beginTransaction();

        try {
            $order = Order::findOrFail($id);

            // 🚨 CRITICAL PROTECTION RULE: Only execute deduction if transitioning to "Completed" 
            // and ensure we haven't already deducted stock for this order previously.
            if ($request->status === 'Completed' && $order->status !== 'Completed') {

                // Unpack the items text/JSON array parameter block saved in this order row
                if (is_string($order->items)) {
                    $orderItems = json_decode((string) $order->items, true);
                } else {
                    $orderItems = $order->items;
                }

                if (is_array($orderItems)) {
                    foreach ($orderItems as $cartItem) {

                        // Look up the active shelf record by checking either 'product_id' or 'id' keys
                        $productId = $cartItem['product_id'] ?? $cartItem['id'] ?? null;
                        $quantityToDeduct = intval($cartItem['quantity'] ?? 0);

                        if ($productId) {
                            // Find the product item safely
                            $product = DB::table('products')->where('id', $productId)->first();

                            if ($product) {
                                // Verification: Ensure you don't sell items past zero stock limits
                                if ($product->stock_count < $quantityToDeduct) {
                                    throw new \Exception("Insufficient stock for item '{$product->name}'. Order requests {$quantityToDeduct} units, but only {$product->stock_count} are left on shelf.");
                                }

                                // 📉 TRIGGER OPERATION: Deduct transaction units straight from the registry table row
                                DB::table('products')
                                    ->where('id', $productId)
                                    ->decrement('stock_count', $quantityToDeduct);
                            }
                        }
                    }
                }
            }

            // Save the state transition permanently into history
            $order->status = $request->status;
            $order->save();

            // Commit transaction changes cleanly to disk channels
            DB::commit();

            return response()->json(['message' => 'Order status updated successfully and warehouse stock adjusted.']);

        } catch (\Exception $e) {
            // 🚨 SAFETY ROLLBACK: If any product fails stock check, cancel all previous deductions instantly!
            DB::rollBack();

            return response()->json([
                'error' => 'Status update failed',
                'message' => $e->getMessage()
            ], 422);
        }
    }
}