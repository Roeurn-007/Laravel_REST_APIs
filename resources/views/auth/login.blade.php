@extends('layouts.app')

@section('title')
    <title>Admin Login</title>
@endsection

@section('content')
    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="row justify-content-center w-100">
            <div class="col-md-5 col-lg-4">

                <div class="card auth-card border-0 shadow-lg">
                    <div class="card-header auth-header text-center py-4">
                        <div class="auth-icon mb-3">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <h3 class="fw-bold mb-1">Welcome Back</h3>
                        <p class="mb-0 small opacity-75">Sign in to access the admin panel</p>
                    </div>

                    <div class="card-body p-4 p-md-5">

                        {{-- Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger border-0 rounded-3 mb-4">
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

                        <form method="POST" action="{{ route('login') }}" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fa-solid fa-envelope me-1 text-muted"></i>Email Address
                                </label>
                                <input type="email" name="email" id="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fa-solid fa-lock me-1 text-muted"></i>Password
                                </label>
                                <input type="password" name="password" id="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    placeholder="Enter your password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In
                            </button>
                        </form>

                        <p class="text-center mt-4 mb-0 text-muted small">
                            Don't have an account?
                            <a href="{{ route('register.form') }}" class="auth-link fw-semibold">Create one</a>
                        </p>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
