@extends('layouts.app')
@section('title')
    <title>Product Lists</title>
@endsection
@section('page-heading', 'Products')
@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1 admin-page-title">Products</h1>
            <p class="admin-page-subtitle mb-0">Manage product inventory, prices, stock, and status.</p>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-dark">
            <i class="fa-solid fa-plus me-1"></i>Add Product
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form action="{{ route('products.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="stock_filter" class="form-select">
                        <option value="">All Stock</option>
                        <option value="available" {{ request('stock_filter') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="low" {{ request('stock_filter') == 'low' ? 'selected' : '' }}>Low Stock (≤10)</option>
                        <option value="out" {{ request('stock_filter') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="fa-solid fa-search"></i>
                    </button>
                </div>
                @if (request()->hasAny(['search', 'category_id', 'status', 'stock_filter']))
                    <div class="col-12">
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-danger">
                            <i class="fa-solid fa-times me-1"></i>Clear Filters
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="table-responsive">
            <table class="table admin-table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if ($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="admin-thumb">
                                @else
                                    <span class="admin-muted small">No image</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $product->name }}</td>
                            <td class="fw-semibold">${{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->category?->name ?? 'Uncategorized' }}</td>
                            <td>
                                <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#product{{ $product->id }}">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteproduct{{ $product->id }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                @include('products.show')
                                @include('products.delete')
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center admin-empty">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
