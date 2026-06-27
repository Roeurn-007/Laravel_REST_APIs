<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

/*
| Home
*/

Route::get('/', function () {
    return auth()->check() ? redirect()->route('admin.dashboard') : redirect()->route('login.form');
});

/*
| AUTH WEB ROUTES
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthWebController::class, 'showRegister'])->name('register.form');
    Route::post('/register', [AuthWebController::class, 'register'])->name('register');
    Route::get('/login', [AuthWebController::class, 'showLogin'])->name('login.form');
    Route::post('/login', [AuthWebController::class, 'login'])->name('login');
});

Route::post('/logout', [AuthWebController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/profile', [AuthWebController::class, 'profile'])
    ->middleware('auth')
    ->name('profile');
Route::put('/profile', [AuthWebController::class, 'updateProfile'])
    ->middleware('auth')
    ->name('profile.update');
Route::delete('/profile/avatar', [AuthWebController::class, 'removeAvatar'])
    ->middleware('auth')
    ->name('profile.avatar.remove');

/*
| ADMIN ROUTES
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('admin.dashboard.stats');
    Route::get('/dashboard/revenue', [DashboardController::class, 'revenue'])->name('admin.dashboard.revenue');
    Route::get('/dashboard/orders', [DashboardController::class, 'orders'])->name('admin.dashboard.orders');
    Route::get('/dashboard/categories', [DashboardController::class, 'categories'])->name('admin.dashboard.categories');
    Route::get('/dashboard/customers', [DashboardController::class, 'customers'])->name('admin.dashboard.customers');
    Route::get('/dashboard/top-products', [DashboardController::class, 'topProducts'])->name('admin.dashboard.top-products');
    Route::get('/dashboard/recent-orders', [DashboardController::class, 'recentOrders'])->name('admin.dashboard.recent-orders');
    Route::get('/dashboard/activities', [DashboardController::class, 'activities'])->name('admin.dashboard.activities');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    /*
    | PRODUCTS
    */
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    /*
    | CATEGORIES
    */
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

});
