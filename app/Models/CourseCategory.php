<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CourseCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'image',
        'parent_id',
        'is_active',
        'is_featured',
        'order',
        'courses_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer',
        'courses_count' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(CourseCategory::class, 'parent_id')->orderBy('order');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'course_category_id');
    }

    public function publishedCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'course_category_id')->published();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value;

        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function updateCoursesCount(): void
    {
        $this->courses_count = $this->publishedCourses()->count();
        $this->save();
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (CourseCategory $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
