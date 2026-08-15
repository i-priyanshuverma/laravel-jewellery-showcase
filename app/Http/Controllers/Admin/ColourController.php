<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ColourRequest;
use App\Models\Colour;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ColourController extends Controller
{
    public function index(): View
    {
        $colours = Colour::orderBy('sort_order')->get();

        return view('admin.colours.index', compact('colours'));
    }

    public function create(): View
    {
        return view('admin.colours.create');
    }

    public function store(ColourRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Colour::create([
            'name' => $validated['name'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.colours.index')
            ->with('success', 'Colour created successfully.');
    }

    public function edit(Colour $colour): View
    {
        return view('admin.colours.edit', compact('colour'));
    }

    public function update(ColourRequest $request, Colour $colour): RedirectResponse
    {
        $validated = $request->validated();

        $colour->update([
            'name' => $validated['name'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.colours.index')
            ->with('success', 'Colour updated successfully.');
    }

    public function destroy(Colour $colour): RedirectResponse
    {
        $colour->delete();

        return redirect()->route('admin.colours.index')
            ->with('success', 'Colour deleted successfully.');
    }
}
