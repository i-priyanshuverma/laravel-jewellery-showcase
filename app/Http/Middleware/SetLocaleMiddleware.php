<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Supported locales in the application.
     */
    public const SUPPORTED_LOCALES = ['en', 'hi', 'ar'];

    /**
     * Handle an incoming request and set the active application locale.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('locale', $request->cookie('locale', config('app.locale', 'en')));

        if (in_array($locale, self::SUPPORTED_LOCALES, true)) {
            App::setLocale($locale);
        } else {
            App::setLocale(config('app.fallback_locale', 'en'));
        }

        return $next($request);
    }
}
