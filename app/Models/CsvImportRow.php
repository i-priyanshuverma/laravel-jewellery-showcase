<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CsvImportRow extends Model
{
    use HasFactory;

    protected $fillable = [
        'csv_import_id',
        'row_number',
        'data',
        'status',
        'errors',
    ];

    protected function casts(): array
    {
        return [
            'row_number' => 'integer',
            'data' => 'array',
            'errors' => 'array',
        ];
    }

    public function csvImport(): BelongsTo
    {
        return $this->belongsTo(CsvImport::class);
    }
}
