<?php

namespace App\Http\Controllers\Vendor;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductVariantRequest;
use App\Http\Requests\UpdateProductVariantRequest;
use App\Models\Colour;
use App\Models\JewellerySize;
use App\Models\Metal;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StoneType;
use App\Services\StockReservationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductVariantController extends Controller
{
    public function __construct(protected StockReservationService $reservationService) {}

    public function create(Product $product): View
    {
        $this->authorize('update', $product);

        $metals = Metal::where('status', 'active')->orderBy('sort_order')->get();
        $colours = Colour::where('status', 'active')->orderBy('sort_order')->get();
        $sizes = JewellerySize::where('status', 'active')
            ->where('category_id', $product->category_id)
            ->orderBy('sort_order')
            ->get();
        $stoneTypes = StoneType::where('status', 'active')->orderBy('sort_order')->get();

        return view('vendor.variants.create', compact('product', 'metals', 'colours', 'sizes', 'stoneTypes'));
    }

    public function store(StoreProductVariantRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();
        $stonesData = $validated['stones'] ?? [];
        unset($validated['stones']);

        /** @var ProductVariant $variant */
        $variant = DB::transaction(function () use ($product, $validated, $stonesData): ProductVariant {
            /** @var ProductVariant $variant */
            $variant = $product->variants()->create($validated);

            foreach ($stonesData as $stone) {
                if (! empty($stone['stone_type_id'])) {
                    $variant->stones()->create([
                        'stone_type_id' => $stone['stone_type_id'],
                        'carat_weight' => $stone['carat_weight'] ?? null,
                        'clarity' => $stone['clarity'] ?? null,
                        'setting_type' => $stone['setting_type'] ?? null,
                    ]);
                }
            }

            if ($product->variants()->count() === 1 && $product->isInactive() && $variant->isActive()) {
                $product->update(['status' => ProductStatus::Active]);
            }

            return $variant;
        });

        return redirect()->route('vendor.products.show', $product)
            ->with('success', "Variant {$variant->sku} created successfully.");
    }

    public function edit(Product $product, ProductVariant $variant): View
    {
        $this->authorize('update', $product);

        $variant->load('stones.stoneType');

        $metals = Metal::where('status', 'active')->orderBy('sort_order')->get();
        $colours = Colour::where('status', 'active')->orderBy('sort_order')->get();
        $sizes = JewellerySize::where('status', 'active')
            ->where('category_id', $product->category_id)
            ->orderBy('sort_order')
            ->get();
        $stoneTypes = StoneType::where('status', 'active')->orderBy('sort_order')->get();

        return view('vendor.variants.edit', compact('product', 'variant', 'metals', 'colours', 'sizes', 'stoneTypes'));
    }

    public function update(UpdateProductVariantRequest $request, Product $product, ProductVariant $variant): RedirectResponse
    {
        $validated = $request->validated();
        $stonesData = $validated['stones'] ?? [];
        unset($validated['stones']);

        $wasActive = $variant->isActive();

        DB::transaction(function () use ($variant, $validated, $stonesData) {
            $variant->update($validated);

            $variant->stones()->delete();
            foreach ($stonesData as $stone) {
                if (! empty($stone['stone_type_id'])) {
                    $variant->stones()->create([
                        'stone_type_id' => $stone['stone_type_id'],
                        'carat_weight' => $stone['carat_weight'] ?? null,
                        'clarity' => $stone['clarity'] ?? null,
                        'setting_type' => $stone['setting_type'] ?? null,
                    ]);
                }
            }
        });

        if ($validated['status'] === 'inactive' && $wasActive) {
            $released = $this->reservationService->releaseForVariant($variant->id);
            if ($released > 0) {
                return redirect()->route('vendor.products.show', $product)
                    ->with('info', "Variant status set to inactive. {$released} active reservation(s) were released.");
            }
        }

        return redirect()->route('vendor.products.show', $product)
            ->with('success', "Variant {$variant->sku} updated successfully.");
    }

    public function destroy(Product $product, ProductVariant $variant): RedirectResponse
    {
        $this->authorize('update', $product);

        $released = $this->reservationService->releaseForVariant($variant->id);

        $variant->delete();

        $message = "Variant deleted successfully. {$released} active reservation(s) were released.";
        if ($product->variants()->count() === 0 && $product->status === 'active') {
            $product->update(['status' => 'inactive']);
            $message .= ' Since no variants remain, this product has been marked inactive.';
        }

        return redirect()->route('vendor.products.show', $product)
            ->with('success', $message);
    }
}
