<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Support\ApiResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $sort = in_array($request->query('sort'), ['id', 'name', 'created_at'], true)
            ? $request->query('sort')
            : 'id';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';
        $perPage = min((int) $request->query('per_page', 12), 50);

        $categories = Category::query()
            ->select(['id', 'name', 'dec', 'is_active', 'created_at'])
            ->withCount('products')
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%' . $request->query('search') . '%'))
            ->when($request->filled('is_active'), fn ($query) => $query->where('is_active', $request->boolean('is_active')))
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return $this->resourceResponse('Categories retrieved successfully', CategoryResource::collection($categories));
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $category = Category::create($data);

        return $this->resourceResponse('Category created successfully', new CategoryResource($category), 201);
    }

    public function show(string $id)
    {
        $category = Category::withCount('products')->find($id);

        if (!$category) {
            return $this->errorResponse('Category not found', [], 404);
        }

        return $this->resourceResponse('Category retrieved successfully', new CategoryResource($category));
    }

    public function update(CategoryRequest $request, string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->errorResponse('Category not found', [], 404);
        }

        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $category->update($data);

        return $this->resourceResponse('Category updated successfully', new CategoryResource($category->fresh()));
    }

    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->errorResponse('Category not found', [], 404);
        }

        $category->delete();

        return $this->successResponse('Category deleted successfully');
    }
}
