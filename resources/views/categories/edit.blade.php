@extends('layouts.app')
@section('title')
    <title>Edit Category</title>
@endsection
@section('page-heading', 'Edit Category')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card stat-card">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h2 class="h4 mb-1 admin-page-title">
                        <i class="fa-solid fa-pen-to-square me-2"></i>Edit Category
                    </h2>
                    <p class="admin-page-subtitle mb-0 small">Update the category details and save your changes.</p>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 rounded-3">
                            <div class="d-flex align-items-start gap-2">
                                <i class="fa-solid fa-circle-exclamation mt-1"></i>
                                <ul class="mb-0 ps-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('categories.update', $category->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">
                                <i class="fa-solid fa-tag me-1 text-muted"></i>Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control form-control-lg @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="dec" class="form-label fw-semibold">
                                <i class="fa-solid fa-align-left me-1 text-muted"></i>Description <span class="text-danger">*</span>
                            </label>
                            <textarea
                                class="form-control form-control-lg @error('dec') is-invalid @enderror"
                                id="dec" name="dec" rows="5" required>{{ old('dec', $category->dec) }}</textarea>
                            @error('dec')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">
                                <i class="fa-solid fa-circle-check me-1 text-success"></i>Active (visible to customers)
                            </label>
                        </div>

                        <hr class="border-secondary opacity-25">

                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-xmark me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i>Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
