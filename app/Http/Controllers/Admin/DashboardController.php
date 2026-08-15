<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockReservation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalVendors = User::where('role', 'vendor')->count();
        $pendingVendors = User::where('role', 'vendor')->where('status', 'pending')->count();
        $approvedVendors = User::where('role', 'vendor')->where('status', 'approved')->count();
        $suspendedVendors = User::where('role', 'vendor')->where('status', 'suspended')->count();

        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'active')->count();
        $totalCategories = Category::count();
        $activeReservations = StockReservation::where('status', 'active')->where('expires_at', '>', now())->count();

        $pendingVendorsList = User::where('role', 'vendor')
            ->where('status', 'pending')
            ->with('vendorProfile')
            ->latest()
            ->get();

        $recentVendors = User::where('role', 'vendor')
            ->with('vendorProfile')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalVendors',
            'pendingVendors',
            'approvedVendors',
            'suspendedVendors',
            'totalProducts',
            'activeProducts',
            'totalCategories',
            'activeReservations',
            'pendingVendorsList',
            'recentVendors'
        ));
    }

    public function activeHoldsCount(): JsonResponse
    {
        return response()->json([
            'count' => StockReservation::where('status', 'active')->where('expires_at', '>', now())->count(),
        ]);
    }
}
