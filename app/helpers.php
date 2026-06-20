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
        $mediaUrl = '';

        try {
            $mediaUrl = media_public_url($imagePath);
            if (! empty($mediaUrl) && preg_match('#^https?://#i', $mediaUrl)) {
                return $mediaUrl;
            }
        } catch (\Throwable $e) {
            // continue
        }

        try {
            $localExists = \Illuminate\Support\Facades\Storage::disk(
                config('storage.fallback_disk', 'public')
            )->exists($imagePath);

            if ($localExists) {
                if (str_contains($imagePath, 'courses/thumbnails/')) {
                    return route('course.thumbnail', ['filename' => $filename], false);
                }
                if (str_contains($imagePath, 'courses/images/')) {
                    return route('course.image', ['filename' => $filename], false);
                }
            }
        } catch (\Throwable $e) {
            // continue
        }

        if (! empty($mediaUrl)) {
            return $mediaUrl;
        }

        return asset('storage/'.$imagePath);
    }
}

if (! function_exists('blog_image_url')) {
    function blog_image_url(?string $imagePath): string
    {
        if (empty($imagePath)) {
            return asset('frontend/assets/images/placeholder.jpg');
        }

        if (str_starts_with($imagePath, 'http')) {
            return $imagePath;
        }

        $imagePath = ltrim($imagePath, '/');
        $filename = basename($imagePath);
        $mediaUrl = '';

        try {
            $mediaUrl = media_public_url($imagePath);
            if (! empty($mediaUrl) && preg_match('#^https?://#i', $mediaUrl)) {
                return $mediaUrl;
            }
        } catch (\Throwable $e) {
            // continue
        }

        try {
            $localExists = \Illuminate\Support\Facades\Storage::disk(
                config('storage.fallback_disk', 'public')
            )->exists($imagePath);

            if ($localExists && str_contains($imagePath, 'blog/images/')) {
                return route('blog.image', ['filename' => $filename], false);
            }
        } catch (\Throwable $e) {
            // continue
        }

        if (! empty($mediaUrl)) {
            return $mediaUrl;
        }

        return asset('storage/'.$imagePath);
    }
}

if (! function_exists('hero_image_url')) {
    function hero_image_url(?string $imagePath): ?string
    {
        if (empty($imagePath)) {
            return null;
        }

        if (str_starts_with($imagePath, 'http')) {
            return $imagePath;
        }

        $imagePath = ltrim($imagePath, '/');
        $filename = basename($imagePath);
        $mediaUrl = '';

        try {
            $mediaUrl = media_public_url($imagePath);
            if (! empty($mediaUrl) && preg_match('#^https?://#i', $mediaUrl)) {
                return $mediaUrl;
            }
        } catch (\Throwable $e) {
            // continue
        }

        try {
            $localExists = \Illuminate\Support\Facades\Storage::disk(
                config('storage.fallback_disk', 'public')
            )->exists($imagePath);

            if ($localExists) {
                if (str_contains($imagePath, 'hero-slides/backgrounds/')) {
                    return route('hero.background', ['filename' => $filename], false);
                }
                if (str_contains($imagePath, 'hero-slides/visuals/')) {
                    return route('hero.visual', ['filename' => $filename], false);
                }
            }
        } catch (\Throwable $e) {
            // continue
        }

        if (! empty($mediaUrl)) {
            return $mediaUrl;
        }

        return asset('storage/'.$imagePath);
    }
}
