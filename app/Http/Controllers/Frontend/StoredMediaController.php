<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Storage\CloudFirstStorageRouter;
use Illuminate\Http\Response;

class StoredMediaController extends Controller
{
    public function serveBlogImage(string $filename): Response
    {
        return $this->serve('blog/images/'.$filename);
    }

    public function serveHeroBackground(string $filename): Response
    {
        return $this->serve('hero-slides/backgrounds/'.$filename);
    }

    public function serveHeroVisual(string $filename): Response
    {
        return $this->serve('hero-slides/visuals/'.$filename);
    }

    protected function serve(string $path): Response
    {
        $file = app(CloudFirstStorageRouter::class)->retrieve($path);

        if (! $file) {
            abort(404);
        }

        return response($file['content'], 200, [
            'Content-Type' => $file['mime'],
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
