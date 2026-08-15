<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JewellerySizeRequest;
use App\Models\Category;
use App\Models\JewellerySize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JewellerySizeController extends Controller
{
    public function index(Request $request): View
    {
        $query = JewellerySize::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sizes = $query->orderBy('category_id')->orderBy('sort_order')->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.sizes.index', compact('sizes', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.sizes.create', compact('categories'));
    }

    public function store(JewellerySizeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        JewellerySize::create([
            'category_id' => $validated['category_id'] ?? null,
            'name' => $validated['name'],
            'value' => $validated['value'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.sizes.index')
            ->with('success', 'Size option created successfully.');
    }

    public function edit(JewellerySize $size): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.sizes.edit', compact('size', 'categories'));
    }

    public function update(JewellerySizeRequest $request, JewellerySize $size): RedirectResponse
    {
        $validated = $request->validated();

        $size->update([
            'category_id' => $validated['category_id'] ?? null,
            'name' => $validated['name'],
            'value' => $validated['value'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.sizes.index')
            ->with('success', 'Size option updated successfully.');
    }

    public function destroy(JewellerySize $size): RedirectResponse
    {
        $size->delete();

        return redirect()->route('admin.sizes.index')
            ->with('success', 'Size option deleted successfully.');
    }
}
