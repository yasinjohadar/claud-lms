<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class BlogFeaturedImageStorage
{
    private const DIRECTORY = 'blog/images';

    public function __construct(private ManagedImageStorage $storage) {}

    public function store(UploadedFile $file): string
    {
        return $this->storage->store($file, self::DIRECTORY, 'تعذر حفظ صورة المقال. تحقق من إعدادات التخزين السحابي.');
    }

    public function delete(?string $path): void
    {
        $this->storage->delete($path);
    }
}
