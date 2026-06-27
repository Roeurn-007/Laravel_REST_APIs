@extends('layouts.app')
@section('title')
    <title>Users</title>
@endsection
@section('page-heading', 'Users')
@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1 admin-page-title">Users</h1>
            <p class="admin-page-subtitle mb-0">See administrators and customers registered in the system.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-dark">
            <i class="fa-solid fa-user-plus me-1"></i>Add User
        </a>
    </div>

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

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form action="{{ route('users.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="orders_filter" class="form-select">
                        <option value="">All Users</option>
                        <option value="with_orders" {{ request('orders_filter') == 'with_orders' ? 'selected' : '' }}>With Orders</option>
                        <option value="without_orders" {{ request('orders_filter') == 'without_orders' ? 'selected' : '' }}>Without Orders</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="fa-solid fa-search"></i>
                    </button>
                </div>
                @if (request()->hasAny(['search', 'role', 'orders_filter']))
                    <div class="col-12">
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-danger">
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
                        <th style="width: 60px;">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Orders</th>
                        <th>Joined</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($user->image_url)
                                        <img src="{{ $user->image_url }}" alt="{{ $user->name }}" class="customer-avatar" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover;">
                                    @else
                                        <div class="customer-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    @endif
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td class="admin-muted">{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-{{ $user->is_admin ? 'warning' : 'secondary' }}">
                                    <i class="fa-solid {{ $user->is_admin ? 'fa-user-shield' : 'fa-user' }} me-1"></i>
                                    {{ $user->is_admin ? 'Admin' : 'Customer' }}
                                </span>
                            </td>
                            <td class="fw-semibold">{{ $user->orders_count }}</td>
                            <td>{{ $user->created_at?->format('M d, Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @if (auth()->id() !== $user->id)
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal" data-bs-target="#deleteUser{{ $user->id }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>

                                    <div class="modal fade" id="deleteUser{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form action="{{ route('users.destroy', $user) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>Delete User</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete <strong>{{ $user->name }}</strong>? This action cannot be undone.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash me-1"></i>Delete</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center admin-empty py-5">
                                <i class="fa-solid fa-users fa-2x mb-2 d-block opacity-50"></i>
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
