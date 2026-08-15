<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'path',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->path, 'http://') || str_starts_with($this->path, 'https://')) {
            return $this->path;
        }

        $path = ltrim($this->path, '/');

        if (str_starts_with($path, 'images/')) {
            return asset($path);
        }

        $disk = config('filesystems.default', 'public');
        if ($disk === 's3' || $disk === 'cloud' || (config('filesystems.disks.s3.bucket') && config('filesystems.disks.s3.key'))) {
            return Storage::disk('s3')->url($path);
        }

        // Remap legacy 'products/' to 'sample-products/' on local storage disk
        if (str_starts_with($path, 'products/')) {
            $path = 'sample-products/'.substr($path, 9);
        }

        return asset('storage/'.$path);
    }
}
