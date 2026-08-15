<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockReservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $totalProducts = Product::where('user_id', $user->id)->count();
        $activeProducts = Product::where('user_id', $user->id)->where('status', 'active')->count();

        $productIds = Product::where('user_id', $user->id)->pluck('id');
        $variantIds = ProductVariant::whereIn('product_id', $productIds)->pluck('id');

        $totalVariants = count($variantIds);
        $activeReservationsCount = StockReservation::whereIn('product_variant_id', $variantIds)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->count();

        $recentProducts = Product::where('user_id', $user->id)
            ->with(['category', 'variants', 'images'])
            ->latest()
            ->take(5)
            ->get();

        return view('vendor.dashboard', compact(
            'user',
            'totalProducts',
            'activeProducts',
            'totalVariants',
            'activeReservationsCount',
            'recentProducts'
        ));
    }
}
