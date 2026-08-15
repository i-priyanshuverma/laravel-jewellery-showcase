<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\StockReservationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(protected StockReservationService $reservationService) {}

    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Product::where('user_id', $user->id)
            ->with(['category', 'images', 'variants']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('vendor.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('vendor.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $product = Product::create([
            'user_id' => $user->id,
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => 'draft',
            'is_featured' => $request->boolean('is_featured'),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $imageFile) {
                $path = $imageFile->store('product-images', 'public');
                $product->images()->create([
                    'path' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('vendor.products.show', $product)
            ->with('success', 'Product created in Draft mode. You can now add variants and specifications before publishing.');
    }

    public function show(Product $product): View
    {
        $this->authorize('view', $product);

        $product->load([
            'category',
            'images',
            'variants.stones.stoneType',
            'variants.activeReservations',
        ]);

        return view('vendor.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $this->authorize('update', $product);

        $product->load(['variants.stones.stoneType', 'images']);
        $categories = Category::orderBy('name')->get();

        return view('vendor.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();
        $wasActive = $product->isActive();
        $status = $validated['status'];

        if ($status === 'active' && $product->variants()->count() === 0) {
            $status = $product->isDraft() ? 'draft' : 'inactive';
            session()->flash('warning', 'Product cannot be set to active because it has no variants. Please add at least one variant first.');
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $status,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        if (in_array($status, ['inactive', 'draft']) && $wasActive) {
            $released = $this->reservationService->releaseForProduct($product->id);
            if ($released > 0) {
                return redirect()->route('vendor.products.show', $product)
                    ->with('info', "Product status updated to {$status}. {$released} active reservation(s) were released.");
            }
        }

        return redirect()->route('vendor.products.show', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        $released = $this->reservationService->releaseForProduct($product->id);

        $product->delete();

        return redirect()->route('vendor.products.index')
            ->with('success', "Product deleted successfully. {$released} active reservation(s) were released.");
    }
}
