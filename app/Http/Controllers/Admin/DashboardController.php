<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private const LOW_STOCK_THRESHOLD = 10;
    private const CACHE_TTL = 300;

    public function index()
    {
        return view('admin.dashboard', [
            'stats' => $this->statsData(),
            'revenue' => $this->revenueData(),
            'orders' => $this->ordersData(),
            'categories' => $this->categoriesData(),
            'customers' => $this->customersData(),
            'topProducts' => $this->topProductsData(),
            'recentOrders' => $this->recentOrdersData(),
            'activities' => $this->activitiesData(),
            'lowStockProducts' => $this->lowStockProductsData(),
            'topCustomers' => $this->topCustomersData(),
        ]);
    }

    public function stats(): JsonResponse
    {
        return $this->success('Dashboard stats retrieved successfully', $this->statsData());
    }

    public function revenue(): JsonResponse
    {
        return $this->success('Revenue analytics retrieved successfully', $this->revenueData());
    }

    public function orders(): JsonResponse
    {
        return $this->success('Order analytics retrieved successfully', $this->ordersData());
    }

    public function categories(): JsonResponse
    {
        return $this->success('Category sales retrieved successfully', $this->categoriesData());
    }

    public function customers(): JsonResponse
    {
        return $this->success('Customer growth retrieved successfully', $this->customersData());
    }

    public function topProducts(): JsonResponse
    {
        return $this->success('Top products retrieved successfully', $this->topProductsData());
    }

    public function recentOrders(): JsonResponse
    {
        return $this->success('Recent orders retrieved successfully', $this->recentOrdersData());
    }

    public function activities(): JsonResponse
    {
        return $this->success('Recent activities retrieved successfully', $this->activitiesData());
    }

    private function statsData(): array
    {
        return Cache::remember('admin.dashboard.stats', self::CACHE_TTL, function () {
            $now = now();
            $currentStart = $now->copy()->startOfMonth();
            $previousStart = $now->copy()->subMonthNoOverflow()->startOfMonth();
            $previousEnd = $now->copy()->subMonthNoOverflow()->endOfMonth();

            return [
                'cards' => [
                    $this->card('Total Revenue', (float) Order::sum('total_price'), (float) Order::whereBetween('created_at', [$currentStart, $now])->sum('total_price'), (float) Order::whereBetween('created_at', [$previousStart, $previousEnd])->sum('total_price'), 'fa-dollar-sign', 'success', 'currency'),
                    $this->card('Total Orders', Order::count(), Order::whereBetween('created_at', [$currentStart, $now])->count(), Order::whereBetween('created_at', [$previousStart, $previousEnd])->count(), 'fa-bag-shopping', 'primary'),
                    $this->card('Total Products', Product::count(), Product::whereBetween('created_at', [$currentStart, $now])->count(), Product::whereBetween('created_at', [$previousStart, $previousEnd])->count(), 'fa-box', 'info'),
                    $this->card('Total Customers', User::where('is_admin', false)->count(), User::where('is_admin', false)->whereBetween('created_at', [$currentStart, $now])->count(), User::where('is_admin', false)->whereBetween('created_at', [$previousStart, $previousEnd])->count(), 'fa-users', 'dark'),
                    $this->card('Pending Orders', Order::where('status', 'pending')->count(), Order::where('status', 'pending')->whereBetween('created_at', [$currentStart, $now])->count(), Order::where('status', 'pending')->whereBetween('created_at', [$previousStart, $previousEnd])->count(), 'fa-clock', 'warning'),
                    $this->card('Completed Orders', Order::whereIn('status', ['completed', 'delivered'])->count(), Order::whereIn('status', ['completed', 'delivered'])->whereBetween('created_at', [$currentStart, $now])->count(), Order::whereIn('status', ['completed', 'delivered'])->whereBetween('created_at', [$previousStart, $previousEnd])->count(), 'fa-circle-check', 'success'),
                    $this->card('Cancelled Orders', Order::where('status', 'cancelled')->count(), Order::where('status', 'cancelled')->whereBetween('created_at', [$currentStart, $now])->count(), Order::where('status', 'cancelled')->whereBetween('created_at', [$previousStart, $previousEnd])->count(), 'fa-ban', 'danger'),
                    $this->card('Low Stock Products', Product::where('stock', '<=', self::LOW_STOCK_THRESHOLD)->count(), Product::where('stock', '<=', self::LOW_STOCK_THRESHOLD)->whereBetween('updated_at', [$currentStart, $now])->count(), Product::where('stock', '<=', self::LOW_STOCK_THRESHOLD)->whereBetween('updated_at', [$previousStart, $previousEnd])->count(), 'fa-triangle-exclamation', 'danger'),
                ],
                'low_stock_threshold' => self::LOW_STOCK_THRESHOLD,
                'generated_at' => $now->toIso8601String(),
            ];
        });
    }

    private function revenueData(): array
    {
        return Cache::remember('admin.dashboard.revenue', self::CACHE_TTL, fn () => [
            'daily' => $this->orderTotalsByPeriod(now()->subDays(29)->startOfDay(), 'day'),
            'weekly' => $this->orderTotalsByPeriod(now()->subWeeks(11)->startOfWeek(), 'week'),
            'monthly' => $this->orderTotalsByPeriod(now()->subMonths(11)->startOfMonth(), 'month'),
            'yearly' => $this->orderTotalsByPeriod(now()->subYears(4)->startOfYear(), 'year'),
        ]);
    }

    private function ordersData(): array
    {
        return Cache::remember('admin.dashboard.orders', self::CACHE_TTL, fn () => [
            'daily' => $this->orderCountsByPeriod(now()->subDays(29)->startOfDay(), 'day'),
            'monthly' => $this->orderCountsByPeriod(now()->subMonths(11)->startOfMonth(), 'month'),
            'status_distribution' => Order::select('status', DB::raw('COUNT(*) as total'))
                ->where('status', '!=', 'delivered')
                ->groupBy('status')
                ->orderBy('status')
                ->get()
                ->map(fn ($row) => ['status' => ucfirst($row->status), 'total' => (int) $row->total])
                ->values()
                ->all(),
        ]);
    }

    private function categoriesData(): array
    {
        return Cache::remember('admin.dashboard.categories', self::CACHE_TTL, fn () => DB::table('categories')
            ->leftJoin('products', 'products.category_id', '=', 'categories.id')
            ->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
            ->select('categories.name', DB::raw('COALESCE(SUM(order_items.quantity * order_items.price), 0) as revenue'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get()
            ->map(fn ($row) => ['category' => $row->name, 'revenue' => (float) $row->revenue])
            ->all());
    }

    private function customersData(): array
    {
        return Cache::remember('admin.dashboard.customers', self::CACHE_TTL, fn () => $this->customerCountsByPeriod(now()->subMonths(11)->startOfMonth()));
    }

    private function topProductsData(): array
    {
        return Cache::remember('admin.dashboard.top-products', self::CACHE_TTL, fn () => OrderItem::query()
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as units_sold'), DB::raw('SUM(order_items.quantity * order_items.price) as revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('units_sold')
            ->limit(8)
            ->get()
            ->map(fn ($row) => [
                'id' => (int) $row->id,
                'name' => $row->name,
                'units_sold' => (int) $row->units_sold,
                'revenue' => (float) $row->revenue,
            ])
            ->all());
    }

    private function recentOrdersData(): array
    {
        return Cache::remember('admin.dashboard.recent-orders', 5, fn () => Order::with('user:id,name,email')
            ->latest()
            ->limit(8)
            ->get(['id', 'user_id', 'total_price', 'status', 'created_at'])
            ->map(fn ($order) => [
                'id' => $order->id,
                'customer' => $order->user?->name ?? 'Guest',
                'total' => (float) $order->total_price,
                'status' => $order->status,
                'date' => $order->created_at?->format('Y-m-d H:i'),
                'url' => route('orders.show', $order),
            ])
            ->all());
    }

    private function activitiesData(): array
    {
        return Cache::remember('admin.dashboard.activities', 120, function () {
            $products = Product::latest()->limit(4)->get(['id', 'name', 'created_at'])->map(fn ($product) => [
                'type' => 'product',
                'icon' => 'fa-box',
                'message' => "New product: {$product->name}",
                'date' => $product->created_at?->diffForHumans(),
            ]);

            $customers = User::where('is_admin', false)->latest()->limit(4)->get(['id', 'name', 'created_at'])->map(fn ($user) => [
                'type' => 'customer',
                'icon' => 'fa-user-plus',
                'message' => "New customer: {$user->name}",
                'date' => $user->created_at?->diffForHumans(),
            ]);

            $orders = Order::latest()->limit(4)->get(['id', 'total_price', 'created_at'])->map(fn ($order) => [
                'type' => 'order',
                'icon' => 'fa-receipt',
                'message' => 'New order #' . $order->id . ' for $' . number_format($order->total_price, 2),
                'date' => $order->created_at?->diffForHumans(),
            ]);

            $updatedProducts = Product::whereColumn('updated_at', '>', 'created_at')->latest('updated_at')->limit(4)->get(['id', 'name', 'updated_at'])->map(fn ($product) => [
                'type' => 'product_update',
                'icon' => 'fa-pen-to-square',
                'message' => "Updated product: {$product->name}",
                'date' => $product->updated_at?->diffForHumans(),
            ]);

            return $products->merge($customers)->merge($orders)->merge($updatedProducts)->take(10)->values()->all();
        });
    }

    private function lowStockProductsData(): array
    {
        return Cache::remember('admin.dashboard.low-stock', self::CACHE_TTL, fn () => Product::with('category:id,name')
            ->where('stock', '<=', self::LOW_STOCK_THRESHOLD)
            ->orderBy('stock')
            ->limit(8)
            ->get(['id', 'category_id', 'name', 'stock', 'price'])
            ->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category?->name,
                'stock' => $product->stock,
                'price' => (float) $product->price,
                'url' => route('products.edit', $product->id),
            ])
            ->all());
    }

    private function topCustomersData(): array
    {
        return Cache::remember('admin.dashboard.top-customers', self::CACHE_TTL, fn () => User::query()
            ->join('orders', 'orders.user_id', '=', 'users.id')
            ->where('users.is_admin', false)
            ->select('users.id', 'users.name', 'users.email', DB::raw('COUNT(orders.id) as orders_count'), DB::raw('SUM(orders.total_price) as total_spent'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(8)
            ->get()
            ->map(fn ($row) => [
                'id' => (int) $row->id,
                'name' => $row->name,
                'email' => $row->email,
                'orders_count' => (int) $row->orders_count,
                'total_spent' => (float) $row->total_spent,
            ])
            ->all());
    }

    private function card(string $label, int|float $value, int|float $current, int|float $previous, string $icon, string $color, string $format = 'number'): array
    {
        $change = $previous > 0 ? (($current - $previous) / $previous) * 100 : ($current > 0 ? 100 : 0);

        return [
            'label' => $label,
            'value' => $value,
            'current_month' => $current,
            'previous_month' => $previous,
            'change_percent' => round($change, 1),
            'trend' => $change >= 0 ? 'up' : 'down',
            'icon' => $icon,
            'color' => $color,
            'format' => $format,
        ];
    }

    private function orderTotalsByPeriod(Carbon $startDate, string $period): array
    {
        return Order::where('created_at', '>=', $startDate)
            ->selectRaw($this->periodExpression($period) . ' as period, COALESCE(SUM(total_price), 0) as total')
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(fn ($row) => ['label' => $row->period, 'total' => (float) $row->total])
            ->all();
    }

    private function orderCountsByPeriod(Carbon $startDate, string $period): array
    {
        return Order::where('created_at', '>=', $startDate)
            ->selectRaw($this->periodExpression($period) . ' as period, COUNT(*) as total')
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(fn ($row) => ['label' => $row->period, 'total' => (int) $row->total])
            ->all();
    }

    private function customerCountsByPeriod(Carbon $startDate): array
    {
        return User::where('is_admin', false)
            ->where('created_at', '>=', $startDate)
            ->selectRaw($this->periodExpression('month') . ' as period, COUNT(*) as total')
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(fn ($row) => ['label' => $row->period, 'total' => (int) $row->total])
            ->all();
    }

    private function periodExpression(string $period): string
    {
        $sqlite = DB::connection()->getDriverName() === 'sqlite';

        return match ($period) {
            'day' => $sqlite ? "strftime('%Y-%m-%d', created_at)" : "DATE_FORMAT(created_at, '%Y-%m-%d')",
            'week' => $sqlite ? "strftime('%Y-W%W', created_at)" : "DATE_FORMAT(created_at, '%x-W%v')",
            'year' => $sqlite ? "strftime('%Y', created_at)" : "DATE_FORMAT(created_at, '%Y')",
            default => $sqlite ? "strftime('%Y-%m', created_at)" : "DATE_FORMAT(created_at, '%Y-%m')",
        };
    }

    private function success(string $message, array $data): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
