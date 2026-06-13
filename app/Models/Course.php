<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'description',
        'course_category_id',
        'instructor_id',
        'level',
        'price',
        'compare_at_price',
        'currency',
        'badge',
        'thumbnail',
        'thumbnail_alt',
        'icon',
        'rating_avg',
        'rating_count',
        'students_count',
        'lessons_count',
        'duration_hours',
        'language',
        'what_you_learn',
        'requirements',
        'status',
        'published_at',
        'is_featured',
        'order',
        'views_count',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'rating_avg' => 'decimal:2',
        'rating_count' => 'integer',
        'students_count' => 'integer',
        'lessons_count' => 'integer',
        'duration_hours' => 'integer',
        'what_you_learn' => 'array',
        'requirements' => 'array',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'order' => 'integer',
        'views_count' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(CourseTag::class, 'course_course_tag', 'course_id', 'course_tag_id')
            ->withTimestamps();
    }

    public function sections(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseSection::class)->orderBy('sort_order');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSearch($query, ?string $keyword)
    {
        if (empty($keyword)) {
            return $query;
        }

        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
                ->orWhere('excerpt', 'like', "%{$keyword}%")
                ->orWhereHas('instructor', fn ($iq) => $iq->where('name', 'like', "%{$keyword}%"))
                ->orWhereHas('tags', fn ($tq) => $tq->where('name', 'like', "%{$keyword}%"));
        });
    }

    public function getThumbnailUrlAttribute(): string
    {
        return \course_image_url($this->thumbnail);
    }

    public function getUrlAttribute(): string
    {
        return route('courses.show', $this->slug);
    }

    public function getFormattedPriceAttribute(): string
    {
        $symbol = $this->currency === 'USD' ? '$' : $this->currency . ' ';

        return $symbol . number_format((float) $this->price, $this->price == floor($this->price) ? 0 : 2);
    }

    public function getFormattedComparePriceAttribute(): ?string
    {
        if (! $this->compare_at_price) {
            return null;
        }

        $symbol = $this->currency === 'USD' ? '$' : $this->currency . ' ';

        return $symbol . number_format((float) $this->compare_at_price, $this->compare_at_price == floor($this->compare_at_price) ? 0 : 2);
    }

    public function getLevelLabelAttribute(): string
    {
        return match ($this->level) {
            'beginner' => 'مبتدئ',
            'intermediate' => 'متوسط',
            'advanced' => 'متقدم',
            default => $this->level,
        };
    }

    public function setTitleAttribute($value): void
    {
        $this->attributes['title'] = $value;

        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Course $course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }
}
