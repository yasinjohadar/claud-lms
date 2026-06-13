<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;

class VideoReferenceService
{
    /**
     * @return array{provider: string, reference: string}
     */
    public function normalize(string $provider, string $input): array
    {
        $input = trim($input);

        if ($input === '') {
            throw ValidationException::withMessages([
                'video_reference' => 'مرجع الفيديو مطلوب.',
            ]);
        }

        return match ($provider) {
            'youtube' => ['provider' => 'youtube', 'reference' => $this->normalizeYoutube($input)],
            'vimeo' => ['provider' => 'vimeo', 'reference' => $this->normalizeVimeo($input)],
            'bunny_stream' => ['provider' => 'bunny_stream', 'reference' => $this->normalizeBunnyStream($input)],
            'bunny_cdn' => ['provider' => 'bunny_cdn', 'reference' => $this->normalizeBunnyCdn($input)],
            default => throw ValidationException::withMessages([
                'video_provider' => 'مصدر الفيديو غير مدعوم.',
            ]),
        };
    }

    protected function normalizeYoutube(string $input): string
    {
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/', $input, $matches)) {
            return $matches[1];
        }

        if (preg_match('/^[A-Za-z0-9_-]{11}$/', $input)) {
            return $input;
        }

        throw ValidationException::withMessages([
            'video_reference' => 'رابط أو معرّف YouTube غير صالح.',
        ]);
    }

    protected function normalizeVimeo(string $input): string
    {
        if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $input, $matches)) {
            return $matches[1];
        }

        if (preg_match('/^\d+$/', $input)) {
            return $input;
        }

        throw ValidationException::withMessages([
            'video_reference' => 'رابط أو معرّف Vimeo غير صالح.',
        ]);
    }

    protected function normalizeBunnyStream(string $input): string
    {
        if (preg_match('/iframe\.mediadelivery\.net\/embed\/([^\/\s]+)\/([^\/\s?]+)/', $input, $matches)) {
            return json_encode([
                'library_id' => $matches[1],
                'video_id' => $matches[2],
            ], JSON_UNESCAPED_UNICODE);
        }

        $decoded = json_decode($input, true);

        if (is_array($decoded) && ! empty($decoded['library_id']) && ! empty($decoded['video_id'])) {
            return json_encode([
                'library_id' => trim((string) $decoded['library_id']),
                'video_id' => trim((string) $decoded['video_id']),
            ], JSON_UNESCAPED_UNICODE);
        }

        if (preg_match('/^([^\/\s]+)\/([^\/\s]+)$/', $input, $matches)) {
            return json_encode([
                'library_id' => $matches[1],
                'video_id' => $matches[2],
            ], JSON_UNESCAPED_UNICODE);
        }

        throw ValidationException::withMessages([
            'video_reference' => 'أدخل library_id/video_id أو رابط Bunny Stream embed.',
        ]);
    }

    protected function normalizeBunnyCdn(string $input): string
    {
        if (! filter_var($input, FILTER_VALIDATE_URL)) {
            throw ValidationException::withMessages([
                'video_reference' => 'رابط Bunny CDN غير صالح.',
            ]);
        }

        $host = parse_url($input, PHP_URL_HOST) ?? '';

        if (! str_contains($host, 'b-cdn.net') && ! str_contains($host, 'bunnycdn.com')) {
            throw ValidationException::withMessages([
                'video_reference' => 'يجب أن يكون الرابط من نطاق Bunny CDN.',
            ]);
        }

        return $input;
    }

    public function displayReference(string $provider, string $reference): string
    {
        if ($provider === 'bunny_stream') {
            $decoded = json_decode($reference, true);

            if (is_array($decoded)) {
                return ($decoded['library_id'] ?? '') . ' / ' . ($decoded['video_id'] ?? '');
            }
        }

        return $reference;
    }
}
