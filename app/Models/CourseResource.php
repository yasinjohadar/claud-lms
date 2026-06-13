<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseResource extends Model
{
    public const TYPES = ['link', 'file'];

    protected $fillable = [
        'course_id',
        'course_section_id',
        'title',
        'slug',
        'description',
        'type',
        'url',
        'file_path',
        'file_original_name',
        'file_mime',
        'file_size',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'sort_order' => 'integer',
        'is_published' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (CourseResource $resource) {
            if (empty($resource->slug)) {
                $resource->slug = static::generateUniqueSlug($resource->course_id, $resource->title);
            }
        });

        static::updating(function (CourseResource $resource) {
            if ($resource->isDirty('title') && $resource->isGlobal()) {
                $resource->slug = static::generateUniqueSlug(
                    $resource->course_id,
                    $resource->title,
                    $resource->id
                );
            }
        });
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeGlobal(Builder $query): Builder
    {
        return $query->whereNull('course_section_id');
    }

    public function isLink(): bool
    {
        return $this->type === 'link';
    }

    public function isFile(): bool
    {
        return $this->type === 'file';
    }

    public function isGlobal(): bool
    {
        return $this->course_section_id === null;
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'link' => 'رابط',
            'file' => 'ملف',
            default => $this->type,
        };
    }

    public function getFileIconAttribute(): string
    {
        if ($this->isLink()) {
            return 'fa-link';
        }

        $mime = $this->file_mime ?? '';

        if (str_starts_with($mime, 'image/')) {
            return 'fa-file-image';
        }

        if ($mime === 'application/pdf') {
            return 'fa-file-pdf';
        }

        if (str_contains($mime, 'word') || str_contains($mime, 'document')) {
            return 'fa-file-word';
        }

        if (str_contains($mime, 'sheet') || str_contains($mime, 'excel')) {
            return 'fa-file-excel';
        }

        if (str_contains($mime, 'zip') || str_contains($mime, 'archive')) {
            return 'fa-file-archive';
        }

        return 'fa-file';
    }

    public function getFormattedFileSizeAttribute(): ?string
    {
        if (! $this->file_size) {
            return null;
        }

        $units = ['بايت', 'ك.ب', 'م.ب', 'ج.ب'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, $unit > 0 ? 1 : 0) . ' ' . $units[$unit];
    }

    public function getFileUrlAttribute(): ?string
    {
        if (! $this->file_path) {
            return null;
        }

        return Storage::disk('public')->url($this->file_path);
    }

    public function getShowUrlAttribute(): ?string
    {
        if (! $this->isGlobal() || ! $this->course) {
            return null;
        }

        return route('courses.resources.show', [$this->course->slug, $this->slug]);
    }

    public static function generateUniqueSlug(int $courseId, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'resource';
        $slug = $base;
        $counter = 1;

        while (static::query()
            ->where('course_id', $courseId)
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
