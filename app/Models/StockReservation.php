<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class StockReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'session_id',
        'idempotency_key',
        'quantity',
        'expires_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'expires_at' => 'datetime',
            'status' => ReservationStatus::class,
        ];
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function isExpired(): bool
    {
        return Carbon::parse($this->expires_at)->isPast();
    }
}
