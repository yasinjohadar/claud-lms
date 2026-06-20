<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class HeroSlideImageStorage
{
    public function __construct(private ManagedImageStorage $storage) {}

    public function storeBackground(UploadedFile $file): string
    {
        return $this->storage->store(
            $file,
            'hero-slides/backgrounds',
            'تعذر حفظ صورة خلفية السلايدر. تحقق من إعدادات التخزين السحابي.'
        );
    }

    public function storeVisual(UploadedFile $file): string
    {
        return $this->storage->store(
            $file,
            'hero-slides/visuals',
            'تعذر حفظ الصورة المرئية للسلايدر. تحقق من إعدادات التخزين السحابي.'
        );
    }

    public function delete(?string $path): void
    {
        $this->storage->delete($path);
    }
}
