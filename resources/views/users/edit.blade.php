@extends('layouts.app')
@section('title')
    <title>Edit User</title>
@endsection
@section('page-heading', 'Edit User')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card stat-card">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h2 class="h4 mb-1 admin-page-title">
                        <i class="fa-solid fa-pen-to-square me-2"></i>Edit User
                    </h2>
                    <p class="admin-page-subtitle mb-0 small">Update account details. Leave the password fields empty to keep the current password.</p>
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

                    <form action="{{ route('users.update', $user) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="fa-solid fa-user me-1 text-muted"></i>Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
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
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fa-solid fa-lock me-1 text-muted"></i>New Password
                                </label>
                                <input type="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Leave empty to keep current">
                                <div class="form-text">Min. 8 characters. Only fill in if you want to change it.</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    <i class="fa-solid fa-shield-halved me-1 text-muted"></i>Confirm New Password
                                </label>
                                <input type="password"
                                    class="form-control form-control-lg"
                                    id="password_confirmation" name="password_confirmation"
                                    placeholder="Re-type new password">
                            </div>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" value="1"
                                {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                                {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_admin">
                                <i class="fa-solid fa-user-shield me-1 text-warning"></i>Grant admin access
                            </label>
                            @if (auth()->id() === $user->id)
                                <div class="form-text text-warning">You cannot change your own admin status.</div>
                            @endif
                        </div>

                        <hr class="border-secondary opacity-25">

                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-xmark me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i>Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
