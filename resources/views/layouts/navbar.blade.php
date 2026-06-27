<aside class="admin-sidebar">
    <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
        <span class="brand-mark">
            <i class="fa-solid fa-shop"></i>
        </span>
        <span>
            <span class="d-block fw-bold">Ecommerce</span>
            <span class="d-block small text-white-50">Admin Panel</span>
        </span>
    </a>

    <nav class="sidebar-nav">
        <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            href="{{ route('admin.dashboard') }}">
            <i class="fa-solid fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
        <a class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
            href="{{ route('products.index') }}">
            <i class="fa-solid fa-box"></i>
            <span>Products</span>
        </a>
        <a class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
            href="{{ route('categories.index') }}">
            <i class="fa-solid fa-layer-group"></i>
            <span>Categories</span>
        </a>
        <a class="sidebar-link {{ request()->routeIs('orders.*') ? 'active' : '' }}"
            href="{{ route('orders.index') }}">
            <i class="fa-solid fa-receipt"></i>
            <span>Orders</span>
        </a>
        <a class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
            href="{{ route('users.index') }}">
            <i class="fa-solid fa-users"></i>
            <span>Users</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('profile') }}" class="sidebar-user d-flex align-items-center gap-2 text-decoration-none text-light">
            <span class="brand-mark user-avatar"
                @if (auth()->user()->image_url) style="background-image: url('{{ auth()->user()->image_url }}'); background-size: cover; background-position: center;" @endif>
                @unless (auth()->user()->image_url)
                    <i class="fa-solid fa-user-shield"></i>
                @endunless
            </span>
            <div class="text-truncate">
                <div class="fw-semibold text-truncate">{{ auth()->user()->name }}</div>
                <div class="small text-white-50">Administrator</div>
            </div>
        </a>
    </div>
</aside>
