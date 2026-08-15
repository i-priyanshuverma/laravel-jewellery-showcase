<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CsvImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'path',
        'total_rows',
        'processed_rows',
        'successful_rows',
        'failed_rows',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total_rows' => 'integer',
            'processed_rows' => 'integer',
            'successful_rows' => 'integer',
            'failed_rows' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rows(): HasMany
    {
        return $this->hasMany(CsvImportRow::class);
    }

    public function progressPercentage(): int
    {
        $total = (int) $this->total_rows;
        if ($total <= 0) {
            return 0;
        }

        return (int) min(100, round(((int) $this->processed_rows / $total) * 100));
    }
}
