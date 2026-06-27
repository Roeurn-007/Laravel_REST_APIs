@extends('layouts.app')
@section('title')
    <title>Edit Product</title>
@endsection
@section('page-heading', 'Edit Product')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card stat-card">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h2 class="h4 mb-1 admin-page-title">
                        <i class="fa-solid fa-pen-to-square me-2"></i>Edit Product
                    </h2>
                    <p class="admin-page-subtitle mb-0 small">Update the product details and save your changes.</p>
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

                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">
                                <i class="fa-solid fa-tag me-1 text-muted"></i>Product Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control form-control-lg @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label fw-semibold">
                                    <i class="fa-solid fa-dollar-sign me-1 text-muted"></i>Price (USD) <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="0.01" min="0"
                                    class="form-control form-control-lg @error('price') is-invalid @enderror"
                                    id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label fw-semibold">
                                    <i class="fa-solid fa-cubes me-1 text-muted"></i>Stock Quantity <span class="text-danger">*</span>
                                </label>
                                <input type="number" min="0"
                                    class="form-control form-control-lg @error('stock') is-invalid @enderror"
                                    id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-semibold">
                                <i class="fa-solid fa-layer-group me-1 text-muted"></i>Category <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" id="category_id"
                                class="form-select form-select-lg @error('category_id') is-invalid @enderror" required>
                                <option value="" disabled>— Select a category —</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">
                                <i class="fa-solid fa-align-left me-1 text-muted"></i>Description
                            </label>
                            <textarea
                                class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="4"
                                placeholder="Enter product description...">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-semibold">
                                <i class="fa-solid fa-image me-1 text-muted"></i>Product Image
                            </label>
                            <input type="file"
                                class="form-control form-control-lg @error('image') is-invalid @enderror"
                                id="image" name="image" accept="image/*" onchange="previewImage(event)">
                            <div class="form-text">Leave empty to keep the current image. Max 2MB.</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            @if ($product->image_url)
                                <div id="currentImage" class="mt-3">
                                    <span class="small text-muted d-block mb-1">Current image:</span>
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                        class="img-thumbnail" style="max-width: 140px;">
                                </div>
                            @endif
                            
                            <div id="newImagePreview" class="mt-3" style="display: none;">
                                <span class="small text-muted d-block mb-1">New image preview:</span>
                                <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>

                        <script>
                            function previewImage(event) {
                                const preview = document.getElementById('newImagePreview');
                                const img = document.getElementById('previewImg');
                                const currentImage = document.getElementById('currentImage');
                                const file = event.target.files[0];
                                
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        img.src = e.target.result;
                                        preview.style.display = 'block';
                                        if (currentImage) {
                                            currentImage.style.display = 'none';
                                        }
                                    }
                                    reader.readAsDataURL(file);
                                } else {
                                    preview.style.display = 'none';
                                    if (currentImage) {
                                        currentImage.style.display = 'block';
                                    }
                                }
                            }
                        </script>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">
                                <i class="fa-solid fa-circle-check me-1 text-success"></i>Active (visible to customers)
                            </label>
                        </div>

                        <hr class="border-secondary opacity-25">

                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-xmark me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i>Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
