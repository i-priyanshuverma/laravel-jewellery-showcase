<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'stock',
        'metal',
        'purity',
        'colour',
        'size',
        'weight',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'weight' => 'decimal:3',
            'status' => ProductStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::deleted(function (ProductVariant $variant) {
            $product = $variant->product;
            if ($product instanceof Product && $product->variants()->count() === 0 && $product->isActive()) {
                $product->update(['status' => ProductStatus::Inactive]);
            }
        });
    }

    public function isActive(): bool
    {
        return $this->status === ProductStatus::Active;
    }

    public function isInactive(): bool
    {
        return $this->status === ProductStatus::Inactive;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(StockReservation::class);
    }

    public function activeReservations(): HasMany
    {
        return $this->hasMany(StockReservation::class)
            ->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    public function getReservedQuantityAttribute(): int
    {
        return (int) $this->activeReservations()->sum('quantity');
    }

    public function stones(): HasMany
    {
        return $this->hasMany(VariantStone::class);
    }
}
