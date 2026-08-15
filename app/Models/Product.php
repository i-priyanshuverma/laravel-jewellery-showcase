<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'description',
        'status',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'status' => ProductStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $baseSlug = Str::slug($product->name);
                $slug = $baseSlug;
                $count = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug.'-'.$count++;
                }
                $product->slug = $slug;
            }
        });
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->where('status', 'active');
    }

    public function isActive(): bool
    {
        return $this->status === ProductStatus::Active;
    }

    public function isDraft(): bool
    {
        return $this->status === ProductStatus::Draft;
    }

    public function isInactive(): bool
    {
        return $this->status === ProductStatus::Inactive;
    }

    public function primaryImage(): ?ProductImage
    {
        /** @var ProductImage|null $image */
        $image = $this->images->first();

        return $image;
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $primary = $this->images->first();
        if ($primary) {
            return $primary->url;
        }

        return asset('images/products/placeholder.svg');
    }
}
