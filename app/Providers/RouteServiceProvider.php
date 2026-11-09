<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure rate limiters for authentication, checkout, administrative endpoints, and API.
     */
    protected function configureRateLimiting(): void
    {
        // Standard API limiter
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Global web traffic limiter: 120 requests/min per IP
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });

        // Authentication limiter: 5 attempts/min by email + IP
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input('email', '')) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey)->response(function (Request $request, array $headers) {
                return back()->withErrors([
                    'email' => 'Too many login attempts. Please wait 1 minute before trying again.',
                ])->withHeaders($headers);
            });
        });

        // Registration limiter: 3 accounts per minute per IP to combat registration bots
        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())->response(function (Request $request, array $headers) {
                return back()->withErrors([
                    'email' => 'Too many registration requests. Please wait a moment before trying again.',
                ])->withHeaders($headers);
            });
        });

        // Checkout & order creation limiter: 5 orders per 10 minutes per user/IP to prevent spam & fraud
        RateLimiter::for('checkout', function (Request $request) {
            $key = $request->user()?->id ?: $request->ip();

            return Limit::perMinutes(10, 5)->by($key)->response(function (Request $request, array $headers) {
                return back()->with('error', 'Too many checkout requests. Please wait a few minutes before placing another order.')->withHeaders($headers);
            });
        });

        // Cart modification limiter: 30 requests per minute
        RateLimiter::for('cart', function (Request $request) {
            $key = $request->user()?->id ?: $request->ip();
            return Limit::perMinute(30)->by($key);
        });

        // Product review submission limiter: 5 reviews per 10 minutes
        RateLimiter::for('reviews', function (Request $request) {
            $key = $request->user()?->id ?: $request->ip();

            return Limit::perMinutes(10, 5)->by($key)->response(function (Request $request, array $headers) {
                return back()->with('error', 'Review submission limit reached. Please wait a few minutes.')->withHeaders($headers);
            });
        });

        // Admin route limiter: 60 requests per minute per authenticated admin
        RateLimiter::for('admin', function (Request $request) {
            $key = $request->user()?->id ?: $request->ip();
            return Limit::perMinute(60)->by($key);
        });
    }
}
