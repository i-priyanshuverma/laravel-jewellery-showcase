<?php

namespace App\Jobs;

use App\Models\CsvImport;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use Throwable;

class ProcessCsvImport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public CsvImport $csvImport) {}

    public function handle(): void
    {
        if ($this->csvImport->status === 'failed') {
            return;
        }

        $this->csvImport->update(['status' => 'processing']);

        $disk = config('filesystems.default', 'public');
        if (! Storage::disk($disk)->exists($this->csvImport->path)) {
            $this->csvImport->update(['status' => 'failed']);

            return;
        }

        try {
            $stream = Storage::disk($disk)->readStream($this->csvImport->path);
            if (! is_resource($stream)) {
                $this->csvImport->update(['status' => 'failed']);

                return;
            }

            $csv = Reader::createFromStream($stream);
            $csv->setHeaderOffset(0);

            $records = iterator_to_array($csv->getRecords());
            $totalRows = count($records);

            $this->csvImport->update([
                'total_rows' => $totalRows,
            ]);

            if ($totalRows === 0) {
                $this->csvImport->update(['status' => 'completed']);

                return;
            }

            $chunkSize = 100;
            $chunks = array_chunk($records, $chunkSize, true);

            $startRowIndex = 2;
            foreach ($chunks as $chunk) {
                ProcessCsvImportChunk::dispatch($this->csvImport->id, $chunk, $startRowIndex);
                $startRowIndex += count($chunk);
            }
        } catch (Throwable $e) {
            $this->csvImport->update(['status' => 'failed']);
        }
    }
}
