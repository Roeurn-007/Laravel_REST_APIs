@extends('layouts.app')
@section('title')
    <title>Order #{{ $order->id }}</title>
@endsection
@section('page-heading', 'Order Details')
@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1 admin-page-title">
                <i class="fa-solid fa-receipt me-2"></i>Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
            </h1>
            <p class="admin-page-subtitle mb-0">Placed on {{ $order->created_at?->format('M d, Y \a\t H:i A') }}</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            @php
                $badge = match(true) {
                    in_array($order->status, ['completed', 'delivered']) => 'success',
                    $order->status === 'cancelled' => 'danger',
                    $order->status === 'pending' => 'warning',
                    default => 'info',
                };
            @endphp
            <span class="badge bg-{{ $badge }} fs-6 px-3 py-2">
                <i class="fa-solid {{ $badge === 'success' ? 'fa-circle-check' : ($badge === 'warning' ? 'fa-clock' : ($badge === 'danger' ? 'fa-ban' : 'fa-circle-info')) }} me-1"></i>
                {{ ucfirst($order->status) }}
            </span>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i>Back to Orders
            </a>
        </div>
    </div>

    <!-- Status Update Section -->
    <div class="card stat-card mt-3">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h5 class="mb-0"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Update Order Status</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('orders.update-status', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label text-light fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status === 'completed' || $order->status === 'delivered' ? 'selected' : '' }}>Completed/Delivered</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa-solid fa-check me-1"></i>Update Status
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card stat-card h-100">
                <div class="card-body p-4">
                    <h5 class="mb-3"><i class="fa-solid fa-user me-2 text-primary"></i>Customer Information</h5>
                    @if ($order->user)
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="customer-avatar" style="width: 56px; height: 56px; font-size: 1.3rem;">
                                {{ strtoupper(substr($order->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold fs-6">{{ $order->user->name }}</div>
                                <div class="admin-muted small">{{ $order->user->email }}</div>
                            </div>
                        </div>
                        <div class="row g-2 small">
                            <div class="col-6">
                                <div class="admin-muted">Customer ID</div>
                                <div class="fw-semibold">#{{ $order->user->id }}</div>
                            </div>
                            <div class="col-6">
                                <div class="admin-muted">Total Orders</div>
                                <div class="fw-semibold">{{ $order->user->orders()->count() }}</div>
                            </div>
                        </div>
                    @else
                        <div class="admin-muted">Unknown customer</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card stat-card h-100">
                <div class="card-body p-4">
                    <h5 class="mb-3"><i class="fa-solid fa-truck me-2 text-primary"></i>Shipping Information</h5>
                    <div class="mb-3">
                        <div class="admin-muted small mb-1">Shipping Address</div>
                        <div class="fw-semibold">{{ $order->shipping_address }}</div>
                    </div>
                    <div class="row g-2 small">
                        <div class="col-6">
                            <div class="admin-muted">Order Date</div>
                            <div class="fw-semibold">{{ $order->created_at?->format('M d, Y') }}</div>
                        </div>
                        <div class="col-6">
                            <div class="admin-muted">Order Time</div>
                            <div class="fw-semibold">{{ $order->created_at?->format('H:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card mt-3">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h5 class="mb-0"><i class="fa-solid fa-box me-2 text-primary"></i>Order Items</h5>
        </div>
        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($item->product?->image_url)
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="admin-thumb">
                                        @else
                                            <div class="admin-thumb d-flex align-items-center justify-content-center admin-muted">
                                                <i class="fa-solid fa-image"></i>
                                            </div>
                                        @endif
                                        <div class="fw-semibold">{{ $item->product?->name ?? 'Deleted product' }}</div>
                                    </div>
                                </td>
                                <td class="admin-muted">{{ $item->product?->category?->name ?? '—' }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td><span class="badge bg-secondary">×{{ $item->quantity }}</span></td>
                                <td class="text-end fw-semibold">${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center admin-empty py-4">No items in this order.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-semibold">Total</td>
                            <td class="text-end fw-bold fs-5 text-primary">${{ number_format($order->total_price, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
