@extends('layouts.app')
@section('title')
    <title>Orders</title>
@endsection
@section('page-heading', 'Orders')
@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1 admin-page-title">Orders</h1>
            <p class="admin-page-subtitle mb-0">Review customer purchases, track payment and shipping status.</p>
        </div>
        <div class="d-flex align-items-center gap-2 small text-muted">
            <i class="fa-solid fa-receipt"></i>
            <span>Total: <strong class="text-light">{{ $orders->count() }}</strong> orders</span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>{{ session('success') }}
        </div>
    @endif

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form action="{{ route('orders.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search orders..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" placeholder="From" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" placeholder="To" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="fa-solid fa-search"></i>
                    </button>
                </div>
                @if (request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                    <div class="col-md-2">
                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-danger w-100">
                            <i class="fa-solid fa-times me-1"></i>Clear Filters
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <ul class="nav nav-pills gap-2" id="orderStatusTabs" role="tablist">
                @php
                    $statuses = [
                        'all' => ['label' => 'All Orders', 'icon' => 'fa-layer-group', 'color' => 'primary'],
                        'pending' => ['label' => 'Pending', 'icon' => 'fa-clock', 'color' => 'warning'],
                        'completed' => ['label' => 'Completed', 'icon' => 'fa-circle-check', 'color' => 'success'],
                        'cancelled' => ['label' => 'Cancelled', 'icon' => 'fa-ban', 'color' => 'danger'],
                    ];
                @endphp
                @foreach ($statuses as $key => $meta)
                    @php
                        $count = $key === 'all'
                            ? $orders->count()
                            : $orders->filter(fn ($o) => $key === 'completed'
                                ? in_array($o->status, ['completed', 'delivered'])
                                : $o->status === $key)->count();
                    @endphp
                    <li class="nav-item">
                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="pill"
                            data-bs-target="#orders-{{ $key }}" type="button">
                            <i class="fa-solid {{ $meta['icon'] }} me-1"></i>{{ $meta['label'] }}
                            <span class="badge bg-{{ $meta['color'] }} ms-1">{{ $count }}</span>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="card-body p-0">
            <div class="tab-content">
                @foreach ($statuses as $key => $meta)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="orders-{{ $key }}">
                        <div class="table-responsive">
                            <table class="table admin-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">Order #</th>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $filtered = $key === 'all'
                                            ? $orders
                                            : $orders->filter(fn ($o) => $key === 'completed'
                                                ? in_array($o->status, ['completed', 'delivered'])
                                                : $o->status === $key);
                                    @endphp
                                    @forelse ($filtered as $order)
                                        <tr>
                                            <td><span class="badge bg-secondary">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if ($order->user?->image_url)
                                                        <img src="{{ $order->user->image_url }}" alt="{{ $order->user->name }}" class="customer-avatar" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover;">
                                                    @else
                                                        <div class="customer-avatar">{{ strtoupper(substr($order->user?->name ?? 'U', 0, 1)) }}</div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold">{{ $order->user?->name ?? 'Unknown customer' }}</div>
                                                        <div class="small admin-muted">ID: {{ $order->user_id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="admin-muted">{{ $order->user?->email ?? '—' }}</td>
                                            <td class="fw-semibold">{{ $order->order_items_count ?? $order->orderItems->count() ?? '—' }}</td>
                                            <td class="fw-semibold">${{ number_format($order->total_price, 2) }}</td>
                                            <td>
                                                @php
                                                    $badge = match(true) {
                                                        in_array($order->status, ['completed', 'delivered']) => 'success',
                                                        $order->status === 'cancelled' => 'danger',
                                                        $order->status === 'pending' => 'warning',
                                                        default => 'info',
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $badge }}">
                                                    <i class="fa-solid {{ $badge === 'success' ? 'fa-circle-check' : ($badge === 'warning' ? 'fa-clock' : ($badge === 'danger' ? 'fa-ban' : 'fa-circle-info')) }} me-1"></i>
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>{{ $order->created_at?->format('M d, Y') }}</div>
                                                <div class="small admin-muted">{{ $order->created_at?->format('H:i A') }}</div>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('orders.show', $order) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-eye me-1"></i>View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center admin-empty py-5">
                                                <i class="fa-solid fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                                                No {{ $key === 'all' ? '' : $key }} orders found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
