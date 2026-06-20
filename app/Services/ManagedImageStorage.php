<?php

namespace App\Services;

use App\Services\Storage\MediaStorageService;
use Illuminate\Http\UploadedFile;

/**
 * تخزين الصور: سحابة أولاً (S3) مع fallback محلي تلقائي عبر MediaStorageService.
 */
class ManagedImageStorage
{
    public function store(UploadedFile $file, string $directory, ?string $errorMessage = null): string
    {
        $directory = trim(str_replace('\\', '/', $directory), '/');

        $result = MediaStorageService::uploadImage($file, $directory);

        if (empty($result['success']) || empty($result['path'])) {
            throw new \RuntimeException(
                $result['error'] ?? ($errorMessage ?? 'تعذر حفظ الصورة. تحقق من إعدادات التخزين السحابي.')
            );
        }

        return $result['path'];
    }

    public function delete(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http')) {
            MediaStorageService::delete($path);
        }
    }
}
