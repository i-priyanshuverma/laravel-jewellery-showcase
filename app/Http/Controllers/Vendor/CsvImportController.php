<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\CsvImportRequest;
use App\Jobs\ProcessCsvImport;
use App\Models\CsvImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvImportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $imports = CsvImport::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('vendor.imports.index', compact('imports'));
    }

    public function create(): View
    {
        return view('vendor.imports.create');
    }

    public function sampleCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="jewellery_products_sample.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = [
            'product_name',
            'category',
            'description',
            'status',
            'is_featured',
            'sku',
            'price',
            'stock',
            'metal',
            'purity',
            'colour',
            'size',
            'weight',
        ];

        $sampleRows = [
            [
                'Gold Solitaire Diamond Ring',
                'Rings',
                'Handcrafted 18K Yellow Gold ring with 4-prong cathedral solitaire diamond setting.',
                'draft',
                'yes',
                'GSR-18K-YG-12',
                '45000.00',
                '10',
                'Gold',
                '18K',
                'Yellow Gold',
                'Size 12',
                '4.200',
            ],
            [
                'Gold Solitaire Diamond Ring',
                'Rings',
                'Handcrafted 18K Yellow Gold ring with 4-prong cathedral solitaire diamond setting.',
                'draft',
                'yes',
                'GSR-18K-YG-14',
                '48000.00',
                '8',
                'Gold',
                '18K',
                'Yellow Gold',
                'Size 14',
                '4.600',
            ],
            [
                'Brilliant Diamond Eternity Bangle',
                'Bangles',
                'Continuous channel of brilliant-cut diamonds in lustrous 18K white gold.',
                'draft',
                'no',
                'BDE-18K-WG-24',
                '175000.00',
                '4',
                'Gold',
                '18K',
                'White Gold',
                '2.4',
                '16.200',
            ],
            [
                'Royal Nizam Teardrop Emerald Gold Pendant',
                'Pendants',
                'Hand-cut teardrop Zambian emerald surrounded by halo micro-pavé diamonds.',
                'draft',
                'yes',
                'RNE-22K-YG-STD',
                '76000.00',
                '6',
                'Gold',
                '22K',
                'Yellow Gold',
                'Standard',
                '5.600',
            ],
            [
                'Artisan Oxidized 925 Silver Curb Chain',
                'Chains',
                'Classic beveled curb link chain in 925 sterling silver with vintage dark oxidation.',
                'draft',
                'no',
                'SCC-925-SL-20',
                '8900.00',
                '20',
                'Silver',
                '925 Silver',
                'Silver',
                '20 Inch',
                '22.000',
            ],
        ];

        $callback = function () use ($columns, $sampleRows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($sampleRows as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function store(CsvImportRequest $request): RedirectResponse
    {
        $user = $request->user();
        $file = $request->file('csv_file');

        $disk = config('filesystems.default', 'public');
        $path = $file->store('csv-imports', $disk);

        $csvImport = CsvImport::create([
            'user_id' => $user->id,
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'status' => 'pending',
        ]);

        ProcessCsvImport::dispatch($csvImport);

        return redirect()->route('vendor.imports.show', $csvImport)
            ->with('success', 'CSV import uploaded and processing started in background.');
    }

    public function show(CsvImport $import): View
    {
        if ($import->user_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            abort(403);
        }

        $import->load(['rows' => function ($query) {
            $query->orderBy('row_number', 'asc');
        }]);

        $failedRows = $import->rows()->where('status', 'failed')->paginate(20, ['*'], 'failed_page');
        $successRows = $import->rows()->where('status', 'success')->paginate(20, ['*'], 'success_page');

        return view('vendor.imports.show', compact('import', 'failedRows', 'successRows'));
    }

    public function progress(CsvImport $import): JsonResponse
    {
        if ($import->user_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'id' => $import->id,
            'status' => $import->status,
            'total_rows' => $import->total_rows ?? 0,
            'processed_rows' => $import->processed_rows,
            'successful_rows' => $import->successful_rows,
            'failed_rows' => $import->failed_rows,
            'percentage' => $import->progressPercentage(),
        ]);
    }
}
