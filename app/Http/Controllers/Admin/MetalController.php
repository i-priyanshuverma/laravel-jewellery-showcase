<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MetalRequest;
use App\Models\Metal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MetalController extends Controller
{
    public function index(): View
    {
        $metals = Metal::with('purities')->orderBy('sort_order')->get();

        return view('admin.metals.index', compact('metals'));
    }

    public function create(): View
    {
        return view('admin.metals.create');
    }

    public function store(MetalRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $metal = Metal::create([
            'name' => $validated['name'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $validated['status'],
        ]);

        if (! empty($validated['purities'])) {
            foreach ($validated['purities'] as $purity) {
                if (! empty($purity['name']) && ! empty($purity['value'])) {
                    $metal->purities()->create([
                        'name' => $purity['name'],
                        'value' => $purity['value'],
                        'sort_order' => $purity['sort_order'] ?? 0,
                        'status' => 'active',
                    ]);
                }
            }
        }

        return redirect()->route('admin.metals.index')
            ->with('success', "Metal '{$metal->name}' created successfully.");
    }

    public function edit(Metal $metal): View
    {
        $metal->load('purities');

        return view('admin.metals.edit', compact('metal'));
    }

    public function update(MetalRequest $request, Metal $metal): RedirectResponse
    {
        $validated = $request->validated();

        $metal->update([
            'name' => $validated['name'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $validated['status'],
        ]);

        $submittedIds = [];
        if (! empty($validated['purities'])) {
            foreach ($validated['purities'] as $purityData) {
                if (! empty($purityData['name']) && ! empty($purityData['value'])) {
                    if (! empty($purityData['id'])) {
                        $purity = $metal->purities()->find($purityData['id']);
                        if ($purity) {
                            $purity->update([
                                'name' => $purityData['name'],
                                'value' => $purityData['value'],
                                'sort_order' => $purityData['sort_order'] ?? 0,
                                'status' => $purityData['status'] ?? 'active',
                            ]);
                            $submittedIds[] = $purity->id;
                        }
                    } else {
                        $newPurity = $metal->purities()->create([
                            'name' => $purityData['name'],
                            'value' => $purityData['value'],
                            'sort_order' => $purityData['sort_order'] ?? 0,
                            'status' => $purityData['status'] ?? 'active',
                        ]);
                        $submittedIds[] = $newPurity->id;
                    }
                }
            }
        }

        $metal->purities()->whereNotIn('id', $submittedIds)->delete();

        return redirect()->route('admin.metals.index')
            ->with('success', "Metal '{$metal->name}' updated successfully.");
    }

    public function destroy(Metal $metal): RedirectResponse
    {
        $metal->purities()->delete();
        $metal->delete();

        return redirect()->route('admin.metals.index')
            ->with('success', 'Metal and its purities deleted successfully.');
    }

    public function purities(Metal $metal): JsonResponse
    {
        return response()->json(
            $metal->activePurities()->get(['id', 'name', 'value'])
        );
    }
}
