@extends('layouts.app')
@section('title')
    <title>Category Lists</title>
@endsection
@section('page-heading', 'Categories')
@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1 admin-page-title">Categories</h1>
            <p class="admin-page-subtitle mb-0">Organize products and control category visibility.</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-dark">
            <i class="fa-solid fa-plus me-1"></i>Create Category
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form action="{{ route('categories.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search categories..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="products_filter" class="form-select">
                        <option value="">All Categories</option>
                        <option value="with_products" {{ request('products_filter') == 'with_products' ? 'selected' : '' }}>With Products</option>
                        <option value="without_products" {{ request('products_filter') == 'without_products' ? 'selected' : '' }}>Without Products</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="fa-solid fa-search"></i>
                    </button>
                </div>
                @if (request()->hasAny(['search', 'status', 'products_filter']))
                    <div class="col-12">
                        <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-danger">
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
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Products</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td class="admin-muted">{{ $category->dec }}</td>
                            <td>
                                <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="fw-semibold">{{ $category->products_count ?? 0 }}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#category{{ $category->id }}">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteCategory{{ $category->id }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>

                                @include('categories.show')
                                @include('categories.delete')
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center admin-empty">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
