<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariantStone extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'stone_type_id',
        'carat_weight',
        'clarity',
        'setting_type',
    ];

    protected function casts(): array
    {
        return [
            'carat_weight' => 'decimal:3',
        ];
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function stoneType(): BelongsTo
    {
        return $this->belongsTo(StoneType::class);
    }
}
