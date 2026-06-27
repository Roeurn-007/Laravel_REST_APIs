<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'shipping_address' => 'required|string|max:1000',
            'coupon_code' => 'sometimes|nullable|string',
        ]);

        $cartItems = CartItem::with('product')
            ->where('user_id', $request->user()->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty',
            ], 422);
        }

        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Not enough stock for {$item->product->name}",
                ], 422);
            }
        }

        $order = DB::transaction(function () use ($request, $data, $cartItems) {
            $total = $cartItems->sum(fn ($item) => $item->quantity * $item->product->price);

            if (($data['coupon_code'] ?? '') === 'OFFER25') {
                $total = $total * 0.75;
            }

            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_price' => $total,
                'status' => 'pending',
                'shipping_address' => $data['shipping_address'],
            ]);

            foreach ($cartItems as $item) {
                $order->orderItems()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            CartItem::where('user_id', $request->user()->id)->delete();

            return $order->load('orderItems.product');
        });

        return response()->json([
            'success' => true,
            'message' => 'Checkout completed',
            'data' => $order,
        ], 201);
    }
}
