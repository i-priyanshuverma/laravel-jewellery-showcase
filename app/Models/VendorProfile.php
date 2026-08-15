<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class VendorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'phone',
        'address',
        'description',
        'logo',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo) {
            return null;
        }

        if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
            return $this->logo;
        }

        $path = ltrim($this->logo, '/');
        $disk = config('filesystems.default', 'public');
        if ($disk === 's3' || $disk === 'cloud' || (config('filesystems.disks.s3.bucket') && config('filesystems.disks.s3.key'))) {
            return Storage::disk('s3')->url($path);
        }

        return asset('storage/'.$path);
    }
}
