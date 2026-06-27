@extends('layouts.app')
@section('title')
    <title>E-commerce Analytics Dashboard</title>
@endsection
@section('page-heading', 'Analytics Dashboard')
@section('content')
    @php
        $money = fn ($value) => '$' . number_format((float) $value, 2);
        $number = fn ($value) => number_format((float) $value);
        $statusClass = fn ($status) => match ($status) {
            'completed', 'delivered' => 'success',
            'cancelled' => 'danger',
            'processing', 'shipped' => 'primary',
            default => 'warning',
        };
        $dashboardPayload = [
            'revenue' => $revenue,
            'orders' => $orders,
            'categories' => $categories,
            'customers' => $customers,
            'topProducts' => $topProducts,
            'endpoints' => [
                'revenue' => route('admin.dashboard.revenue'),
                'orders' => route('admin.dashboard.orders'),
                'categories' => route('admin.dashboard.categories'),
                'customers' => route('admin.dashboard.customers'),
                'topProducts' => route('admin.dashboard.top-products'),
            ],
        ];
    @endphp

    <style>
        .dashboard-hero {
            background: linear-gradient(135deg, rgba(79, 70, 229, .18), rgba(16, 185, 129, .08)), var(--admin-card);
            border: 1px solid var(--admin-border);
            border-radius: 18px;
            padding: 26px;
            color: var(--admin-ink);
            box-shadow: 0 24px 60px rgba(0, 0, 0, .18);
        }

        .analytics-card {
            background: var(--admin-card);
            border: 1px solid var(--admin-border);
            border-radius: 18px;
            color: var(--admin-ink);
            box-shadow: 0 20px 44px rgba(0, 0, 0, .18);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .analytics-card .card-header {
            background: transparent !important;
            border-bottom: 1px solid var(--admin-border);
            color: var(--admin-ink);
            padding: 18px 20px;
        }

        .analytics-card .card-body {
            padding: 20px;
        }

        .dashboard-title {
            color: var(--admin-ink);
            font-weight: 800;
            letter-spacing: 0;
        }

        .dashboard-subtitle,
        .dashboard-muted {
            color: var(--admin-muted) !important;
        }

        .kpi-label {
            color: var(--admin-muted);
            font-weight: 600;
        }

        .kpi-value {
            color: var(--admin-ink);
            font-weight: 800;
        }

        .kpi-comparison {
            color: var(--admin-muted);
        }

        .analytics-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 28px 70px rgba(0, 0, 0, .25);
        }

        .kpi-card {
            position: relative;
            overflow: hidden;
            border: 0;
            color: #ffffff;
        }

        .kpi-card::after {
            content: "";
            position: absolute;
            width: 130px;
            height: 130px;
            top: -48px;
            right: -46px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .16);
            transition: transform .2s ease;
        }

        .kpi-card:hover::after {
            transform: scale(1.2);
        }

        .kpi-card-success {
            background: linear-gradient(135deg, #065F46, var(--admin-secondary));
        }

        .kpi-card-primary {
            background: linear-gradient(135deg, #3730A3, var(--admin-primary));
        }

        .kpi-card-info {
            background: linear-gradient(135deg, #3730A3, #06B6D4);
        }

        .kpi-card-dark {
            background: linear-gradient(135deg, #111827, #374151);
        }

        .kpi-card-warning {
            background: linear-gradient(135deg, #92400E, var(--admin-accent));
        }

        .kpi-card-danger {
            background: linear-gradient(135deg, #991B1B, var(--admin-danger));
        }

        .kpi-icon {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .kpi-card .card-body {
            position: relative;
            z-index: 1;
        }

        .kpi-card .kpi-icon {
            background: rgba(255, 255, 255, .18) !important;
            color: #ffffff !important;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .18);
        }

        .kpi-card .badge {
            background: rgba(255, 255, 255, .18) !important;
            color: #ffffff !important;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .18);
        }

        .kpi-card .kpi-label,
        .kpi-card .kpi-comparison {
            color: rgba(255, 255, 255, .86);
        }

        .kpi-card .kpi-value {
            color: #ffffff;
        }

        .chart-box {
            min-height: 330px;
        }

        .chart-box canvas {
            max-height: 280px;
        }

        .revenue-chart-box {
            min-height: 400px;
        }

        .revenue-chart-box canvas {
            max-height: 350px;
        }

        .orders-chart-box {
            min-height: 400px;
        }

        .orders-chart-box canvas {
            max-height: 350px;
        }

        .pie-chart-box {
            min-height: auto;
        }

        .pie-chart-box canvas {
            max-height: 250px;
        }

        .dashboard-table th {
            font-size: .74rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #0F172A;
            background: #F8FAFC;
            border-bottom-color: var(--admin-border);
            padding: 14px 16px;
            font-weight: 700;
        }

        .dashboard-table td {
            color: var(--admin-ink);
            border-color: var(--admin-border);
            padding: 14px 16px;
        }

        .dashboard-table tbody tr {
            transition: background 0.2s ease;
        }

        .dashboard-table tbody tr:hover td {
            background: #F8FAFC;
        }

        .empty-state {
            border: 1px dashed #374151;
            background: #172033;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            color: var(--admin-muted);
        }

        .activity-dot {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(79, 70, 229, .35);
            color: #3730A3;
        }

        .skeleton {
            position: relative;
            overflow: hidden;
            background: #e5e7eb;
            min-height: 14px;
            border-radius: 8px;
        }

        .skeleton::after {
            content: "";
            position: absolute;
            inset: 0;
            transform: translateX(-100%);
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .65), transparent);
            animation: shimmer 1.2s infinite;
        }

        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        .badge.bg-success {
            background: rgba(16, 185, 129, .15) !important;
            color: #064E3B;
            font-weight: 700;
        }

        .badge.bg-primary,
        .badge.bg-info {
            background: rgba(79, 70, 229, .15) !important;
            color: #172554;
            font-weight: 700;
        }

        .badge.bg-warning {
            background: rgba(245, 158, 11, .15) !important;
            color: #78350F;
            font-weight: 700;
        }

        .badge.bg-danger {
            background: rgba(239, 68, 68, .15) !important;
            color: #7F1D1D;
            font-weight: 700;
        }
    </style>

    <div class="dashboard-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1 class="h3 mb-1 dashboard-title">Store Analytics</h1>
                <p class="dashboard-subtitle mb-0">Revenue, orders, inventory, and customer movement from real store data.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('orders.index') }}" class="btn btn-outline-dark">
                    <i class="fa-solid fa-receipt me-1"></i>Orders
                </a>
                <a href="{{ route('products.create') }}" class="btn btn-dark">
                    <i class="fa-solid fa-plus me-1"></i>Add Product
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4" id="kpiGrid">
        @foreach ($stats['cards'] as $card)
            @php
                $isCurrency = $card['format'] === 'currency';
                $isUp = $card['trend'] === 'up';
            @endphp
            <div class="col-md-6 col-xl-3">
                <div class="card analytics-card kpi-card kpi-card-{{ $card['color'] }} h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="kpi-icon">
                                <i class="fa-solid {{ $card['icon'] }}"></i>
                            </span>
                            <span class="badge">
                                <i class="fa-solid fa-arrow-{{ $isUp ? 'up' : 'down' }} me-1"></i>{{ abs($card['change_percent']) }}%
                            </span>
                        </div>
                        <div class="kpi-label small">{{ $card['label'] }}</div>
                        <div class="h3 mb-2 kpi-value count-up"
                            data-value="{{ $card['value'] }}"
                            data-format="{{ $card['format'] }}">0</div>
                        <div class="small kpi-comparison">
                            This month {{ $isCurrency ? $money($card['current_month']) : $number($card['current_month']) }}
                            vs {{ $isCurrency ? $money($card['previous_month']) : $number($card['previous_month']) }} last month
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-8">
            <div class="card analytics-card revenue-chart-box">
                <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <strong>Revenue Overview</strong>
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-dark revenue-range" data-range="daily">Daily</button>
                        <button class="btn btn-outline-dark revenue-range" data-range="weekly">Weekly</button>
                        <button class="btn btn-outline-dark revenue-range" data-range="monthly">Monthly</button>
                        <button class="btn btn-outline-dark revenue-range" data-range="yearly">Yearly</button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card analytics-card pie-chart-box h-100">
                <div class="card-header bg-white"><strong>Order Status Distribution</strong></div>
                <div class="card-body">
                    <canvas id="statusChart"></canvas>
                    <div id="statusLegend" class="mt-3 text-center"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-6">
            <div class="card analytics-card orders-chart-box">
                <div class="card-header bg-white"><strong>Orders Analytics</strong></div>
                <div class="card-body"><canvas id="ordersChart"></canvas></div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card analytics-card pie-chart-box h-100">
                <div class="card-header bg-white"><strong>Sales by Category</strong></div>
                <div class="card-body">
                    <canvas id="categoryChart"></canvas>
                    <div id="categoryLegend" class="mt-3 text-center"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card analytics-card chart-box">
                <div class="card-header bg-white"><strong>Top Selling Products</strong></div>
                <div class="card-body"><canvas id="topProductsChart"></canvas></div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card analytics-card chart-box">
                <div class="card-header bg-white"><strong>Customer Growth</strong></div>
                <div class="card-body"><canvas id="customersChart"></canvas></div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-8">
            <div class="card analytics-card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>Recent Orders</strong>
                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table dashboard-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order['id'] }}</td>
                                    <td>{{ $order['customer'] }}</td>
                                    <td>{{ $money($order['total']) }}</td>
                                    <td><span class="badge bg-{{ $statusClass($order['status']) }}">{{ ucfirst($order['status']) }}</span></td>
                                    <td>{{ $order['date'] }}</td>
                                    <td><a href="{{ $order['url'] }}" class="btn btn-sm btn-outline-dark">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6"><div class="empty-state">No orders yet.</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card analytics-card h-100">
                <div class="card-header bg-white"><strong>Recent Activities</strong></div>
                <div class="card-body">
                    @forelse ($activities as $activity)
                        <div class="d-flex gap-3 mb-3">
                            <span class="activity-dot"><i class="fa-solid {{ $activity['icon'] }}"></i></span>
                            <div>
                                <div class="fw-semibold">{{ $activity['message'] }}</div>
                                <div class="small dashboard-muted">{{ $activity['date'] }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">No recent activity.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card analytics-card h-100">
                <div class="card-header bg-white"><strong>Low Stock Alert</strong></div>
                <div class="table-responsive">
                    <table class="table dashboard-table align-middle mb-0">
                        <thead><tr><th>Product</th><th>Category</th><th>Stock</th><th>Price</th><th></th></tr></thead>
                        <tbody>
                            @forelse ($lowStockProducts as $product)
                                <tr>
                                    <td>{{ $product['name'] }}</td>
                                    <td>{{ $product['category'] ?? 'Uncategorized' }}</td>
                                    <td><span class="badge bg-danger">{{ $product['stock'] }}</span></td>
                                    <td>{{ $money($product['price']) }}</td>
                                    <td><a href="{{ $product['url'] }}" class="btn btn-sm btn-outline-dark">Edit</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5"><div class="empty-state">Inventory looks healthy.</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card analytics-card h-100">
                <div class="card-header bg-white"><strong>Top Customers</strong></div>
                <div class="table-responsive">
                    <table class="table dashboard-table align-middle mb-0">
                        <thead><tr><th>Customer</th><th>Email</th><th>Orders</th><th>Total Spent</th></tr></thead>
                        <tbody>
                            @forelse ($topCustomers as $customer)
                                <tr>
                                    <td>{{ $customer['name'] }}</td>
                                    <td>{{ $customer['email'] }}</td>
                                    <td>{{ $customer['orders_count'] }}</td>
                                    <td>{{ $money($customer['total_spent']) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4"><div class="empty-state">No customer purchases yet.</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
    <script>
        const dashboardData = @json($dashboardPayload);

        const chartColors = {
            primary: '#4F46E5',
            primarySoft: 'rgba(79, 70, 229, .16)',
            secondary: '#10B981',
            accent: '#F59E0B',
            danger: '#EF4444',
            muted: '#9CA3AF',
            border: '#1F2937',
            grid: 'rgba(156, 163, 175, .12)',
            text: '#9CA3AF',
            panel: '#111827'
        };
        const palette = [chartColors.primary, chartColors.secondary, chartColors.accent, chartColors.danger, '#6366F1', '#14B8A6', '#FBBF24', '#F87171', '#8B5CF6', '#EC4899', '#06B6D4', '#84CC16'];
        const moneyTick = value => '$' + Number(value).toLocaleString();
        const labels = rows => rows.map(item => item.label || item.category || item.name || item.status);
        const totals = (rows, key = 'total') => rows.map(item => Number(item[key] || 0));
        const emptyRows = rows => !rows || rows.length === 0;
        const baseChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    labels: {
                        color: chartColors.text,
                        boxWidth: 10,
                        boxHeight: 10,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: '#020617',
                    titleColor: '#F9FAFB',
                    bodyColor: '#D1D5DB',
                    borderColor: chartColors.border,
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 12
                }
            },
            scales: {
                x: {
                    ticks: { color: chartColors.text },
                    grid: { color: chartColors.grid, drawBorder: false }
                },
                y: {
                    ticks: { color: chartColors.text },
                    grid: { color: chartColors.grid, drawBorder: false }
                }
            }
        };

        function indigoGradient(canvas) {
            const gradient = canvas.getContext('2d').createLinearGradient(0, 0, 0, 280);
            gradient.addColorStop(0, 'rgba(79, 70, 229, .36)');
            gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');
            return gradient;
        }

        function statusColor(status) {
            const key = String(status || '').toLowerCase();
            const statusColors = {
                'completed': chartColors.secondary,
                'delivered': '#14B8A6',
                'shipped': '#06B6D4',
                'pending': chartColors.accent,
                'processing': '#FBBF24',
                'cancelled': chartColors.danger
            };
            return statusColors[key] || chartColors.primary;
        }

        function chartOrEmpty(canvasId, config, rows) {
            const canvas = document.getElementById(canvasId);
            if (emptyRows(rows)) {
                canvas.replaceWith(Object.assign(document.createElement('div'), {
                    className: 'empty-state',
                    textContent: 'No data available yet.'
                }));
                return null;
            }
            return new Chart(canvas, config);
        }

        let revenueChart = chartOrEmpty('revenueChart', revenueConfig('daily'), dashboardData.revenue.daily);

        function revenueConfig(range) {
            const canvas = document.getElementById('revenueChart');
            const rows = dashboardData.revenue[range] || [];
            return {
                type: 'line',
                data: { labels: labels(rows), datasets: [{ label: 'Revenue', data: totals(rows), borderColor: chartColors.primary, backgroundColor: indigoGradient(canvas), pointBackgroundColor: chartColors.primary, pointBorderColor: '#F9FAFB', pointHoverRadius: 6, borderWidth: 3, fill: true, tension: .38 }] },
                options: { ...baseChartOptions, plugins: { ...baseChartOptions.plugins, legend: { display: false } }, scales: { ...baseChartOptions.scales, y: { ...baseChartOptions.scales.y, ticks: { color: chartColors.text, callback: moneyTick } } } }
            };
        }

        document.querySelectorAll('.revenue-range').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.revenue-range').forEach(item => item.className = 'btn btn-outline-dark revenue-range');
                button.className = 'btn btn-dark revenue-range';
                if (revenueChart) {
                    revenueChart.destroy();
                }
                revenueChart = new Chart(document.getElementById('revenueChart'), revenueConfig(button.dataset.range));
            });
        });

        chartOrEmpty('ordersChart', {
            type: 'bar',
            data: { labels: labels(dashboardData.orders.daily), datasets: [{ label: 'Orders', data: totals(dashboardData.orders.daily), backgroundColor: chartColors.secondary, hoverBackgroundColor: '#34D399', borderRadius: 10, borderSkipped: false }] },
            options: { ...baseChartOptions, plugins: { ...baseChartOptions.plugins, legend: { display: false } } }
        }, dashboardData.orders.daily);

        chartOrEmpty('statusChart', {
            type: 'doughnut',
            data: { labels: labels(dashboardData.orders.status_distribution), datasets: [{ data: totals(dashboardData.orders.status_distribution), backgroundColor: dashboardData.orders.status_distribution.map(item => statusColor(item.status)), borderColor: chartColors.panel, borderWidth: 4, hoverOffset: 8 }] },
            options: { ...baseChartOptions, scales: {}, cutout: '70%', plugins: { ...baseChartOptions.plugins, legend: { display: false } } }
        }, dashboardData.orders.status_distribution);

        document.addEventListener('DOMContentLoaded', function() {
            const statusChartEl = document.getElementById('statusChart');
            if (statusChartEl) {
                const chart = Chart.getChart(statusChartEl);
                if (chart && chart.data && chart.data.labels) {
                    const legendContainer = document.getElementById('statusLegend');
                    if (legendContainer) {
                        const colors = chart.data.datasets[0].backgroundColor;
                        const labels = chart.data.labels;
                        let legendHtml = '<div class="row g-2 justify-content-center">';
                        labels.forEach((label, index) => {
                            legendHtml += `<div class="col-auto"><span class="badge" style="background: ${colors[index]}; color: #fff; cursor: pointer;" data-index="${index}">${label}</span></div>`;
                        });
                        legendHtml += '</div>';
                        legendContainer.innerHTML = legendHtml;

                        legendContainer.querySelectorAll('.badge').forEach(badge => {
                            badge.addEventListener('click', function() {
                                const index = parseInt(this.dataset.index);
                                const meta = chart.getDatasetMeta(0);
                                meta.data[index].hidden = !meta.data[index].hidden;
                                chart.update();
                                this.style.opacity = meta.data[index].hidden ? '0.3' : '1';
                            });
                        });
                    }
                }
            }
        });

        chartOrEmpty('categoryChart', {
            type: 'doughnut',
            data: { labels: labels(dashboardData.categories), datasets: [{ data: totals(dashboardData.categories, 'revenue'), backgroundColor: palette.slice(0, dashboardData.categories.length), borderColor: chartColors.panel, borderWidth: 4, hoverOffset: 8 }] },
            options: { 
                ...baseChartOptions, 
                scales: {}, 
                cutout: '64%', 
                plugins: { 
                    ...baseChartOptions.plugins, 
                    legend: { display: false },
                    tooltip: { 
                        ...baseChartOptions.plugins.tooltip, 
                        callbacks: { 
                            label: ctx => `${ctx.label}: ${moneyTick(ctx.raw)}` 
                        } 
                    } 
                } 
            }
        }, dashboardData.categories);

        document.addEventListener('DOMContentLoaded', function() {
            const categoryChartEl = document.getElementById('categoryChart');
            if (categoryChartEl) {
                const chart = Chart.getChart(categoryChartEl);
                if (chart && chart.data && chart.data.labels) {
                    const legendContainer = document.getElementById('categoryLegend');
                    if (legendContainer) {
                        const colors = chart.data.datasets[0].backgroundColor;
                        const labels = chart.data.labels;
                        let legendHtml = '<div class="row g-2 justify-content-center">';
                        labels.forEach((label, index) => {
                            legendHtml += `<div class="col-auto"><span class="badge" style="background: ${colors[index]}; color: #fff; cursor: pointer;" data-index="${index}">${label}</span></div>`;
                        });
                        legendHtml += '</div>';
                        legendContainer.innerHTML = legendHtml;

                        legendContainer.querySelectorAll('.badge').forEach(badge => {
                            badge.addEventListener('click', function() {
                                const index = parseInt(this.dataset.index);
                                const meta = chart.getDatasetMeta(0);
                                meta.data[index].hidden = !meta.data[index].hidden;
                                chart.update();
                                this.style.opacity = meta.data[index].hidden ? '0.3' : '1';
                            });
                        });
                    }
                }
            }
        });

        chartOrEmpty('topProductsChart', {
            type: 'bar',
            data: { labels: labels(dashboardData.topProducts), datasets: [{ label: 'Units sold', data: totals(dashboardData.topProducts, 'units_sold'), backgroundColor: chartColors.primary, hoverBackgroundColor: '#6366F1', borderRadius: 10, borderSkipped: false }] },
            options: { ...baseChartOptions, indexAxis: 'y', plugins: { ...baseChartOptions.plugins, legend: { display: false } } }
        }, dashboardData.topProducts);

        chartOrEmpty('customersChart', {
            type: 'line',
            data: { labels: labels(dashboardData.customers), datasets: [{ label: 'New customers', data: totals(dashboardData.customers), borderColor: chartColors.secondary, backgroundColor: 'rgba(16, 185, 129, .14)', pointBackgroundColor: chartColors.secondary, pointBorderColor: '#F9FAFB', pointHoverRadius: 6, borderWidth: 3, fill: true, tension: .38 }] },
            options: { ...baseChartOptions, plugins: { ...baseChartOptions.plugins, legend: { display: false } } }
        }, dashboardData.customers);

        function formatCountValue(value, format) {
            if (format === 'currency') {
                return '$' + value.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
            return Math.round(value).toLocaleString();
        }

        function animateCount(element) {
            const endValue = Number(element.dataset.value || 0);
            const format = element.dataset.format || 'number';
            const duration = 1100;
            const startTime = performance.now();
            function tick(now) {
                const progress = Math.min((now - startTime) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                element.textContent = formatCountValue(endValue * eased, format);
                if (progress < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        }

        document.querySelectorAll('.count-up').forEach(animateCount);
    </script>
@endsection