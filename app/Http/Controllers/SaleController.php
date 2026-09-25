<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'items' => 'required|array',
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);
        DB::beginTransaction();
        try{
            foreach($request->items as $item){
                $product = Product::find($item['product_id']);
                if(!$product){
                    DB::rollBack();
                    return response()->json(['error' => 'Product not found'], 404);
                }
                if ($product->stock_count < $item['quantity']) {
                    DB::rollBack();
                    return response()->json(['error' => 'Insufficient stock for product: ' . $item['product_id']], 400);
                }
                $product->stock_count -= $item['quantity'];
                $product->save();
            }
            Sale::create([
                'items' => $request->items,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
            ]);
            DB::commit();
            return response()->json(['message' => 'Sale created successfully'], 201);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(['error' => 'Failed to create sale', 'message' => $e->getMessage()], 500);
        }
    }
}
