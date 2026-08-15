<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\ProductSearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(protected ProductSearchService $searchService) {}

    public function index(Request $request): View
    {
        $products = $this->searchService->search($request->all(), perPage: 10, publicOnly: false);
        $categories = Category::orderBy('name')->get();
        $vendors = User::where('role', 'vendor')->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'vendors'));
    }

    public function show(Product $product): View
    {
        $product->load([
            'category',
            'vendor.vendorProfile',
            'images',
            'variants.stones.stoneType',
            'variants.activeReservations',
        ]);

        return view('admin.products.show', compact('product'));
    }
}
