<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StorageFallbackController extends Controller
{
    /**
     * Resiliently serve files requested via /storage/{path}.
     * Checks S3 cloud storage, local app/public storage, and falls back to placeholder SVG.
     */
    public function show(string $path): BinaryFileResponse|Response|RedirectResponse
    {
        $path = ltrim($path, '/');

        // 1. Check local public disk
        $localPublicPath = storage_path('app/public/'.$path);
        if (file_exists($localPublicPath) && is_file($localPublicPath)) {
            $mime = mime_content_type($localPublicPath) ?: 'image/jpeg';

            return response()->file($localPublicPath, [
                'Content-Type' => $mime,
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        }

        // 2. Check S3 cloud storage if configured
        try {
            $s3Disk = Storage::disk('s3');
            if (config('filesystems.disks.s3.bucket') && $s3Disk->exists($path)) {
                return redirect()->away($s3Disk->url($path), 302, [
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }
        } catch (\Throwable) {
            // S3 not reachable or not configured, continue to fallback
        }

        // 3. Check legacy / public images path
        $publicFallbackPath = public_path($path);
        if (file_exists($publicFallbackPath) && is_file($publicFallbackPath)) {
            return response()->file($publicFallbackPath);
        }

        // 4. Return graceful placeholder SVG if file cannot be found
        $placeholder = public_path('images/products/placeholder.svg');
        if (file_exists($placeholder)) {
            return response()->file($placeholder, [
                'Content-Type' => 'image/svg+xml',
                'Cache-Control' => 'public, max-age=3600',
            ]);
        }

        abort(404);
    }
}
