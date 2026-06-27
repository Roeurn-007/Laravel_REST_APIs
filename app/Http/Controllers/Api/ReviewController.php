<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ApiResponse;

    public function index(Product $product)
    {
        $reviews = $product->reviews()->with('user:id,name,email,is_admin,image,created_at')->latest()->paginate(10);

        return $this->resourceResponse('Reviews retrieved successfully', ReviewResource::collection($reviews));
    }

    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:2000',
        ]);

        $review = Review::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
            ],
            $data
        );

        return $this->resourceResponse('Review saved', new ReviewResource($review->load('user:id,name,email,is_admin,image,created_at')), 201);
    }
}
