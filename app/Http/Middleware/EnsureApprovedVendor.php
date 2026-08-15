<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApprovedVendor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isVendor()) {
            abort(403, 'Unauthorized access. Vendor privileges required.');
        }

        if ($user->isPending()) {
            return redirect()->route('vendor.dashboard')
                ->with('warning', 'Your vendor account is pending approval by an administrator.');
        }

        if ($user->isSuspended()) {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'Your vendor account has been suspended by an administrator.');
        }

        return $next($request);
    }
}
