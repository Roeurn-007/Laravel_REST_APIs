@extends('layouts.app')

@section('title')
    <title>My Profile</title>
@endsection

@section('page-heading', 'My Profile')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">

            @if (session('success'))
                <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>{{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger border-0 rounded-3 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i>{{ session('error') }}
                </div>
            @endif

            <div class="card stat-card">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h2 class="h4 mb-1 admin-page-title">
                        <i class="fa-solid fa-id-badge me-2"></i>Account Settings
                    </h2>
                    <p class="admin-page-subtitle mb-0 small">Update your name, email, and profile image.</p>
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

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Clickable Avatar --}}
                        <div class="text-center mb-4">
                            <label for="imageInput" class="avatar-uploader d-inline-block position-relative" title="Click to change image">
                                <div class="profile-avatar-lg" id="avatarPreview"
                                    style="background-image: {{ $user->image_url ? 'url(' . $user->image_url . ')' : 'none' }};">
                                    @unless ($user->image_url)
                                        <span class="avatar-initials">{{ $user->initials }}</span>
                                    @endunless
                                    <div class="avatar-overlay">
                                        <i class="fa-solid fa-camera"></i>
                                        <span>Change</span>
                                    </div>
                                </div>
                                <input type="file" name="image" id="imageInput" accept="image/*" class="d-none">
                            </label>
                            <div class="mt-2 small admin-muted">Click the avatar to upload a new image (JPG, PNG, WEBP — max 2MB).</div>

                            @if ($user->image_url)
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="removeAvatarBtn">
                                    <i class="fa-solid fa-trash me-1"></i>Remove Image
                                </button>
                                <form id="removeAvatarForm" action="{{ route('profile.avatar.remove') }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>

                        <hr class="border-secondary opacity-25">

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

                        <div class="d-flex flex-wrap gap-2 justify-content-end mt-3">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-xmark me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                    <div class="d-flex flex-wrap justify-content-between align-items-center small admin-muted">
                        <div>
                            <i class="fa-regular fa-calendar me-1"></i>
                            Member since {{ $user->created_at->format('M d, Y') }}
                        </div>
                        <div>
                            <i class="fa-solid fa-user-shield me-1 text-warning"></i>
                            Role: {{ $user->is_admin ? 'Administrator' : 'Customer' }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Remove avatar confirmation --}}
    <div class="modal fade" id="removeAvatarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>Remove Profile Image
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to remove your profile image? Your initials will be shown instead.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmRemoveAvatar">
                        <i class="fa-solid fa-trash me-1"></i>Yes, Remove
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Live preview for the chosen image
            const input = document.getElementById('imageInput');
            const preview = document.getElementById('avatarPreview');
            if (input && preview) {
                input.addEventListener('change', function (e) {
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = function (ev) {
                        preview.style.backgroundImage = `url(${ev.target.result})`;
                        // Remove initials overlay if present
                        const initials = preview.querySelector('.avatar-initials');
                        if (initials) initials.remove();
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Remove avatar confirmation
            const removeBtn = document.getElementById('removeAvatarBtn');
            const removeForm = document.getElementById('removeAvatarForm');
            const modalEl = document.getElementById('removeAvatarModal');
            if (removeBtn && removeForm && modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                removeBtn.addEventListener('click', () => modal.show());
                document.getElementById('confirmRemoveAvatar').addEventListener('click', () => removeForm.submit());
            }
        });
    </script>
@endpush
