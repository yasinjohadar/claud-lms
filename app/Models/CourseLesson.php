<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseLesson extends Model
{
    public const PROVIDERS = ['youtube', 'vimeo', 'bunny_stream', 'bunny_cdn'];

    protected $fillable = [
        'course_section_id',
        'title',
        'video_provider',
        'video_reference',
        'duration_seconds',
        'sort_order',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'sort_order' => 'integer',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function module(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(CourseModule::class, 'modulable');
    }

    public function scopePublished($query)
    {
        return $query;
    }

    public function getCourseIdAttribute(): ?int
    {
        return $this->section?->course_id;
    }

    public function progress(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LessonProgress::class, 'course_lesson_id');
    }

    public function getFormattedDurationAttribute(): ?string
    {
        if (! $this->duration_seconds) {
            return null;
        }

        $minutes = intdiv($this->duration_seconds, 60);
        $seconds = $this->duration_seconds % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    public function getProviderLabelAttribute(): string
    {
        return match ($this->video_provider) {
            'youtube' => 'YouTube',
            'vimeo' => 'Vimeo',
            'bunny_stream' => 'Bunny Stream',
            'bunny_cdn' => 'Bunny CDN',
            default => $this->video_provider,
        };
    }

    public function isDirectVideo(): bool
    {
        return $this->video_provider === 'bunny_cdn';
    }

    public function getEmbedUrlAttribute(): ?string
    {
        if (! $this->video_provider || ! $this->video_reference) {
            return null;
        }

        return match ($this->video_provider) {
            'youtube' => 'https://www.youtube.com/embed/' . $this->video_reference . '?rel=0',
            'vimeo' => 'https://player.vimeo.com/video/' . $this->video_reference,
            'bunny_stream' => $this->resolveBunnyStreamEmbedUrl(),
            default => null,
        };
    }

    protected function resolveBunnyStreamEmbedUrl(): ?string
    {
        $decoded = json_decode($this->video_reference, true);

        if (! is_array($decoded) || empty($decoded['library_id']) || empty($decoded['video_id'])) {
            return null;
        }

        return sprintf(
            'https://iframe.mediadelivery.net/embed/%s/%s',
            $decoded['library_id'],
            $decoded['video_id']
        );
    }
}
