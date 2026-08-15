<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purity extends Model
{
    use HasFactory;

    protected $fillable = [
        'metal_id',
        'name',
        'value',
        'sort_order',
        'status',
    ];

    public function metal(): BelongsTo
    {
        return $this->belongsTo(Metal::class);
    }
}
