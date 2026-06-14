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
}
