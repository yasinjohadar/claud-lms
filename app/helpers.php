<?php

if (! function_exists('storage_disk')) {
    function storage_disk(string $diskName)
    {
        return app(\App\Services\Storage\AppStorageManager::class)->getDisk($diskName);
    }
}

if (! function_exists('media_public_url')) {
    /**
     * رابط عام للملف المخزّن (يفضّل السحابة عند توفر الملف هناك).
     */
    function media_public_url(?string $path): string
    {
        if ($path === null || $path === '') {
            return '';
        }

        $path = trim((string) $path);
        if (preg_match('#^https?://[^/]+/storage/(.+)$#i', $path, $m)) {
            $path = $m[1];
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        try {
            return \App\Services\Storage\MediaStorageService::url($normalized);
        } catch (\Throwable $e) {
            try {
                return \Illuminate\Support\Facades\Storage::disk(
                    config('storage.fallback_disk', 'public')
                )->url($normalized);
            } catch (\Throwable $inner) {
                $p = ltrim(str_replace('\\', '/', $normalized), '/');

                return '/storage/'.$p;
            }
        }
    }
}

if (! function_exists('course_image_url')) {
    function course_image_url(?string $imagePath): string
    {
        if (empty($imagePath)) {
            return asset('frontend/assets/img/default-course.jpg');
        }

        $imagePath = ltrim($imagePath, '/');
        $filename = basename($imagePath);

        try {
            $url = media_public_url($imagePath);
            if (! empty($url)) {
                return $url;
            }
        } catch (\Throwable $e) {
            // continue
        }

        try {
            if (str_contains($imagePath, 'courses/thumbnails/')) {
                return route('course.thumbnail', ['filename' => $filename]);
            }
            if (str_contains($imagePath, 'courses/images/')) {
                return route('course.image', ['filename' => $filename]);
            }
        } catch (\Throwable $e) {
            // continue
        }

        return asset('storage/'.$imagePath);
    }
}
