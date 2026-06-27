<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $sort = in_array($request->query('sort'), ['id', 'name', 'price', 'stock', 'created_at'], true)
            ? $request->query('sort')
            : 'id';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';
        $perPage = min((int) $request->query('per_page', 12), 50);

        $products = Product::query()
            ->select(['id', 'category_id', 'name', 'description', 'image', 'price', 'stock', 'is_active', 'created_at'])
            ->with('category:id,name,dec,is_active,created_at')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->query('search');
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhereHas('category', fn ($query) => $query->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->query('category_id')))
            ->when($request->filled('is_active'), fn ($query) => $query->where('is_active', $request->boolean('is_active')))
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return $this->resourceResponse('Products retrieved successfully', ProductResource::collection($products));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        $data['is_active'] = $request->boolean('is_active', true);

        $product = Product::create($data)->load('category:id,name,dec,is_active,created_at');

        return $this->resourceResponse('Product created successfully', new ProductResource($product), 201);
    }

    public function show(string $id)
    {
        $product = Product::with('category:id,name,dec,is_active,created_at')->find($id);

        if (!$product) {
            return $this->errorResponse('Product not found', [], 404);
        }

        return $this->resourceResponse('Product retrieved successfully', new ProductResource($product));
    }

    public function update(ProductRequest $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->errorResponse('Product not found', [], 404);
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');
        $product->update($data);

        return $this->resourceResponse(
            'Product updated successfully',
            new ProductResource($product->fresh()->load('category:id,name,dec,is_active,created_at'))
        );
    }

    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->errorResponse('Product not found', [], 404);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return $this->successResponse('Product deleted successfully');
    }
}
