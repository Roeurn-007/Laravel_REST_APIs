<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @yield('title')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <style>
        :root {
            --admin-sidebar-width: 260px;
            --admin-primary: #4F46E5;
            --admin-secondary: #10B981;
            --admin-accent: #F59E0B;
            --admin-danger: #EF4444;
            --admin-bg: #0F172A;
            --admin-card: #111827;
            --admin-border: #1F2937;
            --admin-ink: #F9FAFB;
            --admin-muted: #9CA3AF;
        }

        html {
            color-scheme: dark;
        }

        body {
            background: var(--admin-bg);
            color: var(--admin-ink);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .admin-shell {
            min-height: 100vh;
        }

        .admin-sidebar {
            width: var(--admin-sidebar-width);
            min-height: 100vh;
            background: #0B1120;
            color: var(--admin-ink);
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1030;
            border-right: 1px solid var(--admin-border);
        }

        .admin-main {
            margin-left: var(--admin-sidebar-width);
            min-height: 100vh;
        }

        .admin-content {
            padding: 24px;
        }

        .sidebar-brand {
            height: 64px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 20px;
            border-bottom: 1px solid var(--admin-border);
            color: var(--admin-ink);
            text-decoration: none;
        }

        .sidebar-brand:hover {
            color: #fff;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--admin-primary), #7C3AED);
            color: #fff;
            box-shadow: 0 12px 24px rgba(79, 70, 229, .32);
        }

        .sidebar-nav {
            padding: 18px 12px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 12px;
            color: var(--admin-muted);
            text-decoration: none;
            margin-bottom: 6px;
            transition: background .18s ease, color .18s ease, transform .18s ease;
        }

        .sidebar-link i {
            width: 18px;
            text-align: center;
            font-size: .95rem;
            opacity: .95;
        }

        .sidebar-link:hover {
            background: #172033;
            color: #F9FAFB;
            transform: translateX(2px);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, var(--admin-primary), #6366F1);
            color: #fff;
            box-shadow: 0 12px 28px rgba(79, 70, 229, .28);
        }

        .sidebar-footer {
            position: absolute;
            right: 12px;
            bottom: 16px;
            left: 12px;
            padding-top: 16px;
            border-top: 1px solid var(--admin-border);
        }

        .admin-topbar {
            height: 64px;
            padding: 0 24px;
            background: rgba(17, 24, 39, .86);
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            backdrop-filter: blur(16px);
            color: var(--admin-ink);
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .admin-topbar .text-muted {
            color: var(--admin-muted) !important;
        }

        .stat-card {
            background: var(--admin-card);
            border: 1px solid var(--admin-border);
            border-radius: 16px;
            color: var(--admin-ink);
            box-shadow: 0 20px 44px rgba(0, 0, 0, .18);
        }

        .metric-icon {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .admin-page-title {
            color: var(--admin-ink);
            font-weight: 800;
            letter-spacing: 0;
        }

        .admin-page-subtitle,
        .admin-muted {
            color: var(--admin-muted) !important;
        }

        .admin-table {
            color: var(--admin-ink);
            margin-bottom: 0;
            border: 1px solid #EF4444;
        }

        .admin-table thead th {
            background: #0B1120;
            color: var(--admin-muted);
            border-bottom: 1px solid var(--admin-border);
            font-size: .74rem;
            font-weight: 700;
            letter-spacing: .04em;
            padding: 18px 16px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .admin-table tbody td {
            color: #E5E7EB;
            border-color: var(--admin-border);
            padding: 14px 16px;
            vertical-align: middle;
        }

        .admin-table tbody tr {
            transition: background 0.2s ease;
        }

        .admin-table tbody tr:hover td {
            background: #F8FAFC;
        }

        .admin-table .fw-semibold {
            color: #F9FAFB;
        }

        .admin-empty {
            color: var(--admin-muted);
            padding: 32px 16px !important;
        }

        .admin-thumb {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border: 1px solid var(--admin-border);
            border-radius: 12px;
            background: #0F172A;
            padding: 3px;
        }

        .badge.bg-success {
            background: rgba(16, 185, 129, .15) !important;
            color: #064E3B;
            font-weight: 700;
        }

        .badge.bg-secondary {
            background: rgba(107, 114, 128, .15) !important;
            color: #1F2937;
            font-weight: 700;
        }

        .badge.bg-warning {
            background: rgba(245, 158, 11, .15) !important;
            color: #78350F;
            font-weight: 700;
        }

        .badge.bg-info,
        .badge.bg-primary {
            background: rgba(79, 70, 229, .15) !important;
            color: #172554;
            font-weight: 700;
        }

        .badge.bg-danger {
            background: rgba(239, 68, 68, .15) !important;
            color: #7F1D1D;
            font-weight: 700;
        }

        .btn-outline-warning {
            border-color: rgba(245, 158, 11, .55);
            color: #FCD34D;
        }

        .btn-outline-warning:hover {
            background: var(--admin-accent);
            border-color: var(--admin-accent);
            color: #111827;
        }

        .btn {
            border-radius: 12px;
            transition: transform .16s ease, filter .16s ease, background .16s ease, border-color .16s ease;
        }

        .btn:hover {
            transform: translateY(-1px) scale(1.02);
        }

        .btn-dark,
        .btn-primary {
            background: linear-gradient(135deg, var(--admin-primary), #6366F1);
            border-color: transparent;
            color: #fff;
        }

        .btn-dark:hover,
        .btn-primary:hover {
            filter: brightness(.94);
            color: #fff;
        }

        .btn-outline-dark,
        .btn-outline-primary {
            border-color: #1F2937;
            color: #1F2937;
            font-weight: 600;
        }

        .btn-outline-dark:hover,
        .btn-outline-primary:hover {
            background: var(--admin-primary);
            border-color: var(--admin-primary);
            color: #fff;
        }

        .btn-outline-danger {
            border-color: #7F1D1D;
            color: #7F1D1D;
            font-weight: 600;
        }

        .btn-outline-danger:hover {
            background: var(--admin-danger);
            border-color: var(--admin-danger);
            color: #fff;
        }

        .form-control,
        .form-select {
            background: #0F172A;
            color: var(--admin-ink);
            border-color: var(--admin-border);
        }

        .form-control:focus,
        .form-select:focus {
            background: #0B1120;
            color: #fff;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 .2rem rgba(79, 70, 229, .25);
        }

        .form-control::placeholder {
            color: #6B7280;
        }

        .form-text {
            color: var(--admin-muted) !important;
        }

        .input-group-text {
            background: #0B1120;
            color: var(--admin-muted);
            border-color: var(--admin-border);
        }

        .alert {
            border-radius: 12px;
        }

        .alert-success {
            background: rgba(16, 185, 129, .12);
            color: #6EE7B7;
            border: 1px solid rgba(16, 185, 129, .25);
        }

        .alert-danger {
            background: rgba(239, 68, 68, .12);
            color: #FCA5A5;
            border: 1px solid rgba(239, 68, 68, .25);
        }

        .alert-info {
            background: rgba(79, 70, 229, .12);
            color: #C7D2FE;
            border: 1px solid rgba(79, 70, 229, .25);
        }

        .nav-pills .nav-link {
            color: var(--admin-muted);
            background: transparent;
            border: 1px solid var(--admin-border);
            border-radius: 10px !important;
            padding: 8px 14px;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, var(--admin-primary), #6366F1);
            color: #fff;
            border-color: transparent;
        }

        .nav-pills .nav-link:hover:not(.active) {
            color: var(--admin-ink);
            background: #172033;
        }

        .customer-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--admin-primary), #7C3AED);
            color: #fff;
            font-weight: 600;
            font-size: .9rem;
            flex-shrink: 0;
        }

        body.auth-page {
            background:
                radial-gradient(1200px 600px at 10% -10%, rgba(79, 70, 229, .25), transparent 60%),
                radial-gradient(900px 500px at 100% 110%, rgba(124, 58, 237, .22), transparent 60%),
                var(--admin-bg);
        }

        .auth-card {
            background: var(--admin-card);
            color: var(--admin-ink);
            border: 1px solid var(--admin-border);
            border-radius: 18px;
            overflow: hidden;
        }

        .auth-header {
            background: linear-gradient(135deg, var(--admin-primary), #6366F1);
            color: #fff;
            border: none;
        }

        .auth-header-register {
            background: linear-gradient(135deg, #10B981, #059669);
        }

        .auth-icon {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: rgba(255, 255, 255, .18);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
        }

        .auth-link {
            color: #C7D2FE;
            text-decoration: none;
        }

        .auth-link:hover {
            color: #fff;
            text-decoration: underline;
        }

        .admin-page-title {
            color: var(--admin-ink);
        }

        .admin-page-subtitle {
            color: var(--admin-muted);
        }

        .admin-muted {
            color: var(--admin-muted) !important;
        }

        .modal-content {
            background: var(--admin-card);
            color: var(--admin-ink);
            border: 1px solid var(--admin-border);
        }

        .modal-header,
        .modal-footer {
            border-color: var(--admin-border);
        }

        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* ===== Profile avatar ===== */
        .profile-avatar-lg {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background-color: #0B1120;
            background-size: cover;
            background-position: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 3px solid var(--admin-border);
            box-shadow: 0 12px 28px rgba(0, 0, 0, .35);
            cursor: pointer;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
            position: relative;
            overflow: hidden;
        }

        .profile-avatar-lg:hover {
            transform: scale(1.03);
            border-color: var(--admin-primary);
            box-shadow: 0 16px 36px rgba(79, 70, 229, .35);
        }

        .avatar-initials {
            font-size: 2.4rem;
            font-weight: 700;
            color: #F9FAFB;
            background: linear-gradient(135deg, var(--admin-primary), #7C3AED);
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .avatar-uploader {
            cursor: pointer;
        }

        .avatar-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, .85);
            backdrop-filter: blur(2px);
            color: #4F46E5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: .9rem;
            font-weight: 600;
            opacity: 0;
            transition: opacity .18s ease;
        }

        .profile-avatar-lg:hover .avatar-overlay {
            opacity: 1;
        }

        .avatar-overlay i {
            font-size: 1.3rem;
        }

        .sidebar-user {
            padding: 6px;
            border-radius: 12px;
            transition: background .18s ease;
        }

        .sidebar-user:hover {
            background: #172033;
            color: #fff !important;
        }

        .user-avatar {
            overflow: hidden;
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                position: static;
                width: 100%;
                min-height: auto;
            }

            .admin-main {
                margin-left: 0;
            }

            .sidebar-footer {
                position: static;
                margin: 0 12px 16px;
            }
        }

        /* Light Theme Overrides */
        [data-bs-theme="light"] {
            --admin-bg: #FFFFFF;
            --admin-card: #FFFFFF;
            --admin-border: #CBD5E1;
            --admin-ink: #000000;
            --admin-muted: #334155;
        }

        [data-bs-theme="light"] body {
            background: #FFFFFF !important;
            color: #000000 !important;
        }

        [data-bs-theme="light"] .admin-sidebar {
            background: #FFFFFF !important;
            border-right: 1px solid #E2E8F0;
        }

        [data-bs-theme="light"] .sidebar-brand {
            border-bottom: 1px solid #E2E8F0;
            color: #000000 !important;
        }

        [data-bs-theme="light"] .sidebar-brand .fw-bold {
            color: #000000 !important;
            font-weight: 800;
        }

        [data-bs-theme="light"] .sidebar-brand span.small {
            color: #000000 !important;
            font-weight: 600;
        }

        [data-bs-theme="light"] .sidebar-link {
            color: #000000 !important;
            font-weight: 700;
        }

        [data-bs-theme="light"] .sidebar-link i {
            color: #000000 !important;
            font-weight: 800;
        }

        [data-bs-theme="light"] .sidebar-link:hover {
            background: #E2E8F0;
            color: #000000 !important;
        }

        [data-bs-theme="light"] .sidebar-link.active {
            color: #FFFFFF !important;
        }

        [data-bs-theme="light"] .sidebar-footer {
            border-top: 1px solid #E2E8F0;
        }

        [data-bs-theme="light"] .sidebar-footer .text-white-50 {
            color: #000000 !important;
            font-weight: 600;
        }

        [data-bs-theme="light"] .sidebar-user {
            color: #000000 !important;
        }

        [data-bs-theme="light"] .sidebar-user:hover {
            background: #F8FAFC !important;
            color: #000000 !important;
        }

        [data-bs-theme="light"] .sidebar-user .fw-semibold {
            color: #000000 !important;
            font-weight: 700;
        }

        [data-bs-theme="light"] .sidebar-user .small {
            color: #000000 !important;
            font-weight: 500;
        }

        [data-bs-theme="light"] .admin-topbar {
            background: rgba(255, 255, 255, .95);
            border-bottom: 1px solid #E2E8F0;
            color: #000000 !important;
        }

        [data-bs-theme="light"] .admin-topbar .text-muted {
            color: #475569 !important;
            font-weight: 500;
        }

        [data-bs-theme="light"] .stat-card {
            background: var(--admin-card);
            border: 1px solid #E2E8F0;
            color: var(--admin-ink);
        }

        [data-bs-theme="light"] .admin-table {
            border: 1px solid #E2E8F0;
        }

        [data-bs-theme="light"] .admin-table thead th {
            background: #F8FAFC;
            color: #0F172A;
            font-weight: 700;
            border-bottom: 1px solid #E2E8F0;
        }

        [data-bs-theme="light"] .admin-table tbody td {
            color: #0F172A;
            font-weight: 600;
            border-bottom: 1px solid #E2E8F0;
        }

        [data-bs-theme="light"] .admin-table .admin-muted {
            color: #475569 !important;
            font-weight: 600;
        }

        [data-bs-theme="light"] .admin-table .fw-semibold {
            color: #0F172A;
            font-weight: 700;
        }

        [data-bs-theme="light"] .admin-table tbody tr {
            transition: background 0.2s ease;
        }

        [data-bs-theme="light"] .admin-table tbody tr:hover td {
            background: #F8FAFC;
        }

        [data-bs-theme="light"] .admin-empty {
            color: #475569;
            font-weight: 600;
        }

        [data-bs-theme="light"] .admin-thumb {
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
        }

        [data-bs-theme="light"] .form-control,
        [data-bs-theme="light"] .form-select {
            background: #FFFFFF;
            color: #0F172A;
            border: 1px solid #E2E8F0;
            font-weight: 500;
        }

        [data-bs-theme="light"] .form-control:focus,
        [data-bs-theme="light"] .form-select:focus {
            background: #FFFFFF;
            color: #0F172A;
            border-color: var(--admin-primary);
        }

        [data-bs-theme="light"] .customer-avatar {
            background: linear-gradient(135deg, var(--admin-primary), #7C3AED);
        }

        [data-bs-theme="light"] .btn-close {
            filter: invert(0) grayscale(100%) brightness(20%);
        }

        [data-bs-theme="light"] .profile-avatar-lg {
            background-color: #FFFFFF;
            border: 2px solid #E2E8F0;
        }

        [data-bs-theme="light"] .auth-page {
            background:
                radial-gradient(1200px 600px at 10% -10%, rgba(79, 70, 229, .15), transparent 60%),
                radial-gradient(900px 500px at 100% 110%, rgba(124, 58, 237, .12), transparent 60%),
                var(--admin-bg);
        }
    </style>
</head>

<body class="{{ request()->routeIs('login.form', 'register.form') ? 'auth-page' : '' }}">
    @auth
        <div class="admin-shell">
            @include('layouts.navbar')
            <main class="admin-main">
                <div class="admin-topbar">
                    <div>
                        <div class="text-muted small">Admin System</div>
                        <strong>@yield('page-heading', 'Dashboard')</strong>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-warning" id="themeToggle" title="Toggle theme">
                            <i class="fa-solid fa-moon"></i>
                        </button>
                        <a href="{{ route('profile') }}" class="topbar-avatar-link" title="My profile">
                            <span class="topbar-avatar"
                                @if (auth()->user()->image_url) style="background-image: url('{{ auth()->user()->image_url }}'); background-size: cover; background-position: center;" @endif>
                                @unless (auth()->user()->image_url)
                                    <i class="fa-solid fa-user"></i>
                                @endunless
                            </span>
                        </a>
                        <span class="text-muted small d-none d-md-inline">{{ auth()->user()->name }}</span>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="logoutBtn">
                            <i class="fa-solid fa-right-from-bracket me-1"></i>Logout
                        </button>
                        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
                <div class="admin-content">
                    @yield('content')
                </div>
            </main>
        </div>

        @stack('scripts')

        {{-- Real-time polling for admin pages --}}
        @if(request()->routeIs('orders.index'))
        <script>
            let lastOrderCount = {{ $orders->count() }};
            let orderTableBody = document.querySelector('.admin-table tbody');
            
            function checkForNewOrders() {
                fetch("{{ route('admin.dashboard.recent-orders') }}")
                    .then(response => response.json())
                    .then(data => {
                        if (data.orders && data.orders.length > lastOrderCount) {
                            location.reload();
                        }
                    })
                    .catch(err => console.log('Polling error:', err));
            }
            
            setInterval(checkForNewOrders, 3000);
        </script>
        @endif

        {{-- Theme Toggle --}}
        <script>
            (function() {
                const toggleBtn = document.getElementById('themeToggle');
                if (!toggleBtn) return;

                const key = 'admin_theme';
                const setTheme = (theme) => {
                    document.documentElement.setAttribute('data-bs-theme', theme);
                    localStorage.setItem(key, theme);
                    updateToggleText(theme);
                };

                const updateToggleText = (theme) => {
                    const span = toggleBtn.querySelector('span');
                    const icon = toggleBtn.querySelector('i');
                    if (!span || !icon) return;
                    if (theme === 'light') {
                        span.textContent = 'Switch to Dark Mode';
                        icon.className = 'fa-solid fa-sun';
                    } else {
                        span.textContent = 'Switch to Light Mode';
                        icon.className = 'fa-solid fa-moon';
                    }
                };

                const saved = localStorage.getItem(key) || 'dark';
                setTheme(saved);

                toggleBtn.addEventListener('click', () => {
                    const current = document.documentElement.getAttribute('data-bs-theme') || 'dark';
                    setTheme(current === 'dark' ? 'light' : 'dark');
                });
            })();
        </script>

        {{-- Logout confirmation modal --}}
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa-solid fa-right-from-bracket text-danger me-2"></i>Confirm Logout
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Are you sure you want to end your session? You'll need to log in again to access the admin panel.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i>Cancel
                        </button>
                        <button type="button" class="btn btn-danger" id="confirmLogoutBtn">
                            <i class="fa-solid fa-right-from-bracket me-1"></i>Yes, Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        @yield('content')
    @endauth
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const logoutBtn = document.getElementById('logoutBtn');
            const confirmBtn = document.getElementById('confirmLogoutBtn');
            const logoutForm = document.getElementById('logoutForm');
            const modalEl = document.getElementById('logoutModal');

            if (logoutBtn && modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                logoutBtn.addEventListener('click', () => modal.show());
                confirmBtn.addEventListener('click', () => logoutForm.submit());
            }
        });
    </script>
</body>

</html>