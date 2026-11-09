<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\ReviewController as UserReviewController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\User\NotificationController as UserNotificationController;
use App\Http\Controllers\User\UserAnalyticsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes - Santi Furniture E-Commerce Platform
|--------------------------------------------------------------------------
|
| Rate limiting and role-based access control are applied to protect
| the application against brute force attacks, bot spamming, and unauthorized access.
|
*/

// ==========================================
// 1. Public Storefront Routes (Global Rate Limit)
// ==========================================
Route::middleware('throttle:global')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/products', [UserProductController::class, 'index'])->name('products.index');
    Route::get('/products/{slug}', [UserProductController::class, 'show'])->name('products.show');
});

// ==========================================
// 2. Authentication Routes (Strict Throttling)
// ==========================================
Route::middleware('guest')->group(function () {
    // Customer Auth
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:register');

    // Admin Auth
    Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'login'])->middleware('throttle:login');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==========================================
// 3. Customer Protected Routes
// ==========================================
Route::middleware(['auth', 'user'])->group(function () {
    // Cart & Item Management (Rate limited)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add')->middleware('throttle:cart');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update')->middleware('throttle:cart');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove')->middleware('throttle:cart');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear')->middleware('throttle:cart');

    // Checkout & Orders (Throttled to prevent spam and inventory lock attacks)
    Route::get('/checkout', [UserOrderController::class, 'checkout'])->name('checkout.index');
    Route::post('/orders', [UserOrderController::class, 'store'])->name('orders.store')->middleware('throttle:checkout');
    Route::get('/my-orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
    Route::post('/my-orders/{order}/cancel', [UserOrderController::class, 'cancel'])->name('orders.cancel');

    // Reviews (Only delivered purchases, throttled)
    Route::get('/my-reviews', [UserReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews', [UserReviewController::class, 'store'])->name('reviews.store')->middleware('throttle:reviews');

    // User Profile
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password');

    // Notifications
    Route::get('/notifications', [UserNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [UserNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [UserNotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // User Analytics
    Route::get('/my-analytics', [UserAnalyticsController::class, 'index'])->name('user.analytics');
});

// ==========================================
// 4. Admin Protected Routes (Admin Middleware + Throttled)
// ==========================================
Route::middleware(['auth', 'admin', 'throttle:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Products Management CRUD
    Route::resource('products', AdminProductController::class);
    Route::delete('products/images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.images.destroy');

    // Categories Management CRUD
    Route::resource('categories', AdminCategoryController::class)->except(['create', 'show', 'edit']);

    // Orders Management & Delivery Workflow
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Customer Accounts
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');

    // Review Moderation
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Sales Analytics & Reporting
    Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('analytics');

    // Admin Profile & Security
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
});
