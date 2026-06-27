<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $orders = Order::with('orderItems.product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(min((int) $request->query('per_page', 10), 50))
            ->withQueryString();

        return $this->resourceResponse('Orders retrieved successfully', OrderResource::collection($orders));
    }

    public function show(Request $request, Order $order)
    {
        abort_if($order->user_id !== $request->user()->id, 403);

        return $this->resourceResponse('Order retrieved successfully', new OrderResource($order->load('orderItems.product.category')));
    }
}
