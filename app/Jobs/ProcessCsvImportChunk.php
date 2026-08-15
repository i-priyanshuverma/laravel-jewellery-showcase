<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\CsvImport;
use App\Models\CsvImportRow;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class ProcessCsvImportChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $csvImportId,
        public array $rows,
        public int $startRowNumber
    ) {}

    public function handle(): void
    {
        $csvImport = CsvImport::find($this->csvImportId);
        if (! $csvImport) {
            return;
        }

        $currentRow = $this->startRowNumber;

        foreach ($this->rows as $row) {
            $this->processRow($csvImport, $row, $currentRow);
            $currentRow++;
        }

        $csvImport->refresh();
        if ($csvImport->total_rows && $csvImport->processed_rows >= $csvImport->total_rows) {
            $csvImport->update(['status' => 'completed']);
        }
    }

    protected function processRow(CsvImport $csvImport, array $row, int $rowNumber): void
    {
        $rules = [
            'product_name' => 'required|string|min:2|max:255',
            'category' => 'required|string|min:2|max:255',
            'description' => 'nullable|string|max:5000',
            'status' => 'nullable|in:draft,active,inactive',
            'is_featured' => 'nullable|string',
            'sku' => 'required|string|max:100|regex:/^[A-Za-z0-9\-\_]+$/',
            'price' => 'required|numeric|gt:0',
            'stock' => 'required|integer|min:0|max:100000',
            'metal' => 'nullable|string|max:100',
            'purity' => 'nullable|string|max:100',
            'colour' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|gt:0|max:10000',
        ];

        $validator = Validator::make($row, $rules);

        if ($validator->fails()) {
            CsvImportRow::create([
                'csv_import_id' => $csvImport->id,
                'row_number' => $rowNumber,
                'data' => $row,
                'status' => 'failed',
                'errors' => $validator->errors()->toArray(),
            ]);

            DB::table('csv_imports')
                ->where('id', $csvImport->id)
                ->incrementEach([
                    'processed_rows' => 1,
                    'failed_rows' => 1,
                ]);

            return;
        }

        try {
            DB::transaction(function () use ($csvImport, $row, $rowNumber) {
                $categoryName = trim($row['category']);
                $category = Category::firstOrCreate(
                    ['name' => $categoryName],
                    ['slug' => Str::slug($categoryName)]
                );

                $isFeatured = isset($row['is_featured']) && in_array(strtolower(trim($row['is_featured'])), ['1', 'yes', 'true']);

                $productName = trim($row['product_name']);
                $product = Product::updateOrCreate(
                    [
                        'user_id' => $csvImport->user_id,
                        'name' => $productName,
                    ],
                    [
                        'category_id' => $category->id,
                        'description' => $row['description'] ?? null,
                        'status' => 'draft',
                        'is_featured' => $isFeatured,
                    ]
                );

                $sku = trim($row['sku']);
                $existingVariant = ProductVariant::where('sku', $sku)->with('product')->first();
                if ($existingVariant && $existingVariant->product && (int) $existingVariant->product->user_id !== (int) $csvImport->user_id) {
                    throw new \Exception("SKU '{$sku}' is already registered to another vendor.");
                }

                ProductVariant::updateOrCreate(
                    [
                        'sku' => $sku,
                    ],
                    [
                        'product_id' => $product->id,
                        'price' => (float) $row['price'],
                        'stock' => (int) $row['stock'],
                        'metal' => ! empty($row['metal']) ? trim($row['metal']) : null,
                        'purity' => ! empty($row['purity']) ? trim($row['purity']) : null,
                        'colour' => ! empty($row['colour']) ? trim($row['colour']) : null,
                        'size' => ! empty($row['size']) ? trim($row['size']) : null,
                        'weight' => ! empty($row['weight']) ? (float) $row['weight'] : null,
                        'status' => 'active',
                    ]
                );

                CsvImportRow::create([
                    'csv_import_id' => $csvImport->id,
                    'row_number' => $rowNumber,
                    'data' => $row,
                    'status' => 'success',
                    'errors' => null,
                ]);

                DB::table('csv_imports')
                    ->where('id', $csvImport->id)
                    ->incrementEach([
                        'processed_rows' => 1,
                        'successful_rows' => 1,
                    ]);
            });
        } catch (Throwable $e) {
            CsvImportRow::create([
                'csv_import_id' => $csvImport->id,
                'row_number' => $rowNumber,
                'data' => $row,
                'status' => 'failed',
                'errors' => ['system' => [$e->getMessage()]],
            ]);

            DB::table('csv_imports')
                ->where('id', $csvImport->id)
                ->incrementEach([
                    'processed_rows' => 1,
                    'failed_rows' => 1,
                ]);
        }
    }
}
