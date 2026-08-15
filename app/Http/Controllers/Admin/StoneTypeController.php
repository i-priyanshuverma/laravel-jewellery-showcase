<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoneTypeRequest;
use App\Models\StoneType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StoneTypeController extends Controller
{
    public function index(): View
    {
        $stoneTypes = StoneType::orderBy('sort_order')->get();

        return view('admin.stone-types.index', compact('stoneTypes'));
    }

    public function create(): View
    {
        return view('admin.stone-types.create');
    }

    public function store(StoneTypeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        StoneType::create([
            'name' => $validated['name'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.stone-types.index')
            ->with('success', 'Stone type created successfully.');
    }

    public function edit(StoneType $stoneType): View
    {
        return view('admin.stone-types.edit', compact('stoneType'));
    }

    public function update(StoneTypeRequest $request, StoneType $stoneType): RedirectResponse
    {
        $validated = $request->validated();

        $stoneType->update([
            'name' => $validated['name'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.stone-types.index')
            ->with('success', 'Stone type updated successfully.');
    }

    public function destroy(StoneType $stoneType): RedirectResponse
    {
        $stoneType->delete();

        return redirect()->route('admin.stone-types.index')
            ->with('success', 'Stone type deleted successfully.');
    }
}
