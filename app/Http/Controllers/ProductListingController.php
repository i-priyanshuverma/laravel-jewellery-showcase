<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Colour;
use App\Models\JewellerySize;
use App\Models\Metal;
use App\Models\Product;
use App\Models\StockReservation;
use App\Models\StoneType;
use App\Models\User;
use App\Services\ProductSearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductListingController extends Controller
{
    public function __construct(protected ProductSearchService $searchService) {}

    public function index(Request $request): View|RedirectResponse
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->isVendor()) {
                return redirect()->route('vendor.dashboard');
            }
        }

        $products = $this->searchService->search($request->all(), perPage: 12, publicOnly: true);

        $categories = Category::orderBy('name')->get();
        $vendors = User::where('role', 'vendor')->where('status', 'approved')->with('vendorProfile')->get();

        $metals = Metal::where('status', 'active')->with('activePurities')->orderBy('sort_order')->get();
        $colours = Colour::where('status', 'active')->orderBy('sort_order')->get();
        $stoneTypes = StoneType::where('status', 'active')->orderBy('sort_order')->get();

        $sizesQuery = JewellerySize::where('status', 'active');
        if ($request->filled('category_id')) {
            $sizesQuery->where('category_id', $request->category_id);
        }
        $sizes = $sizesQuery->orderBy('sort_order')->get();

        return view('products.index', compact(
            'products',
            'categories',
            'vendors',
            'metals',
            'colours',
            'sizes',
            'stoneTypes'
        ));
    }

    public function show(Product $product): View|RedirectResponse
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->isVendor()) {
                return redirect()->route('vendor.dashboard');
            }
        }

        $vendor = $product->vendor;
        if (! $product->isActive() || ($vendor instanceof User && ! $vendor->isApproved())) {
            abort(404);
        }

        $product->load([
            'category',
            'vendor.vendorProfile',
            'images',
            'activeVariants.stones.stoneType',
            'activeVariants.activeReservations',
        ]);

        $userReservations = [];
        if (session()->has('reservation_session_id')) {
            $userReservations = StockReservation::where('session_id', session('reservation_session_id'))
                ->whereIn('product_variant_id', $product->activeVariants->pluck('id'))
                ->where('status', 'active')
                ->where('expires_at', '>', now())
                ->get()
                ->keyBy('product_variant_id');
        }

        return view('products.show', compact('product', 'userReservations'));
    }
}
