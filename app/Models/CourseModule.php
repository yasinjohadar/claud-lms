<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CourseModule extends Model
{
    public const TYPES = ['quiz', 'question_module'];

    protected $fillable = [
        'course_id',
        'section_id',
        'module_type',
        'modulable_type',
        'modulable_id',
        'title',
        'description',
        'sort_order',
        'is_visible',
        'is_required',
        'is_graded',
        'max_score',
        'completion_type',
        'time_limit',
        'available_from',
        'available_until',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_visible' => 'boolean',
        'is_required' => 'boolean',
        'is_graded' => 'boolean',
        'max_score' => 'decimal:2',
        'time_limit' => 'integer',
        'available_from' => 'datetime',
        'available_until' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    public function modulable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
