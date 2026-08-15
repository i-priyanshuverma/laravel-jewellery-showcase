<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductImageRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductImageController extends Controller
{
    public function index(Product $product): View
    {
        $this->authorize('update', $product);
        $product->load('images');

        return view('vendor.products.images', compact('product'));
    }

    public function store(StoreProductImageRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $currentMaxSort = $product->images()->max('sort_order') ?? -1;

        $disk = config('filesystems.default', 'public');

        DB::transaction(function () use ($request, $product, $currentMaxSort, $disk) {
            foreach ($request->file('images') as $index => $imageFile) {
                $path = $imageFile->storePublicly('product-images', $disk);
                $product->images()->create([
                    'path' => $path,
                    'sort_order' => $currentMaxSort + 1 + $index,
                ]);
            }
        });

        return back()->with('success', 'Image(s) uploaded successfully.');
    }

    public function destroy(Product $product, ProductImage $image): RedirectResponse
    {
        $this->authorize('update', $product);

        if ($image->product_id !== $product->id) {
            abort(403);
        }

        $disk = config('filesystems.default', 'public');
        Storage::disk($disk)->delete($image->path);
        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }
}
