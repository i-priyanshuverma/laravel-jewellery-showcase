<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Metal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sort_order',
        'status',
    ];

    public function purities(): HasMany
    {
        return $this->hasMany(Purity::class);
    }

    public function activePurities(): HasMany
    {
        return $this->hasMany(Purity::class)
            ->where('status', 'active')
            ->orderBy('sort_order');
    }
}
