<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonProgress extends Model
{
    public const STATUSES = ['not_started', 'in_progress', 'completed'];

    protected $table = 'lesson_progress';

    protected $fillable = [
        'student_id',
        'enrollment_id',
        'course_lesson_id',
        'status',
        'watched_seconds',
        'last_position_seconds',
        'completed_at',
    ];

    protected $casts = [
        'watched_seconds' => 'integer',
        'last_position_seconds' => 'integer',
        'completed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(CourseEnrollment::class, 'enrollment_id');
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CourseLesson::class, 'course_lesson_id');
    }
}
