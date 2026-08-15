<?php

namespace App\Providers;

use App\Models\StockReservation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->environment('local', 'testing') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    public function boot(): void
    {
        Password::defaults(function () {
            $rule = Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols();

            return $this->app->environment('testing')
                ? Password::min(8)
                : $rule->uncompromised();
        });

        Paginator::defaultView('components.pagination');

        RateLimiter::for('reservations', function (Request $request) {
            return Limit::perMinute(60)->by($request->session()->getId() ?: $request->ip());
        });

        RateLimiter::for('csv-imports', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });

        View::composer('layouts.navigation', function ($view) {
            $sessionId = session()->get('reservation_session_id', session()->getId());
            $activeHolds = StockReservation::where('session_id', $sessionId)
                ->where('status', 'active')
                ->where('expires_at', '>', now())
                ->with(['variant.product.images', 'variant.stones.stoneType'])
                ->orderBy('expires_at', 'asc')
                ->get();

            $view->with('activeSessionHolds', $activeHolds);
        });
    }
}
