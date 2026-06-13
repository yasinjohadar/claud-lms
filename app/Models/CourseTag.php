<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class CourseTag extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'is_active',
        'courses_count',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'courses_count' => 'integer',
        'order' => 'integer',
    ];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_course_tag', 'course_tag_id', 'course_id')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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
        $this->courses_count = $this->courses()->where('status', 'published')->count();
        $this->save();
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (CourseTag $tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }
}
