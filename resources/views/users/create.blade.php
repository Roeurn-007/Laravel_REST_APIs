@extends('layouts.app')
@section('title')
    <title>Add New User</title>
@endsection
@section('page-heading', 'Add User')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card stat-card">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h2 class="h4 mb-1 admin-page-title">
                        <i class="fa-solid fa-user-plus me-2"></i>Create New User
                    </h2>
                    <p class="admin-page-subtitle mb-0 small">Add a new administrator or customer account.</p>
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

                    <form action="{{ route('users.store') }}" method="POST" novalidate>
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="fa-solid fa-user me-1 text-muted"></i>Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="Enter full name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fa-solid fa-envelope me-1 text-muted"></i>Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
                                    placeholder="user@example.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fa-solid fa-lock me-1 text-muted"></i>Password <span class="text-danger">*</span>
                                </label>
                                <input type="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="At least 8 characters" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    <i class="fa-solid fa-shield-halved me-1 text-muted"></i>Confirm Password <span class="text-danger">*</span>
                                </label>
                                <input type="password"
                                    class="form-control form-control-lg"
                                    id="password_confirmation" name="password_confirmation"
                                    placeholder="Re-type password" required>
                            </div>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" value="1"
                                {{ old('is_admin') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_admin">
                                <i class="fa-solid fa-user-shield me-1 text-warning"></i>Grant admin access (full panel privileges)
                            </label>
                        </div>

                        <hr class="border-secondary opacity-25">

                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-xmark me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i>Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
