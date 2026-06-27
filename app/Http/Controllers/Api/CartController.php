<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $items = CartItem::with('product.category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return $this->successResponse('Cart retrieved successfully', CartItemResource::collection($items), [
            'total' => (float) $items->sum(fn ($item) => $item->quantity * $item->product->price),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $item = CartItem::firstOrNew([
            'user_id' => $request->user()->id,
            'product_id' => $data['product_id'],
        ]);

        $item->quantity = $item->exists
            ? $item->quantity + ($data['quantity'] ?? 1)
            : ($data['quantity'] ?? 1);
        $item->save();

        return $this->resourceResponse('Product added to cart', new CartItemResource($item->load('product.category')), 201);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        abort_if($cartItem->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update($data);

        return $this->resourceResponse('Cart item updated', new CartItemResource($cartItem->load('product.category')));
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        abort_if($cartItem->user_id !== $request->user()->id, 403);

        $cartItem->delete();

        return $this->successResponse('Cart item removed');
    }
}
