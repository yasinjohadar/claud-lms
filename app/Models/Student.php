<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    public const STATUSES = ['active', 'suspended', 'inactive', 'graduated'];

    protected $fillable = [
        'user_id',
        'student_code',
        'gender',
        'date_of_birth',
        'nationality',
        'country',
        'city',
        'address',
        'education_level',
        'university',
        'major',
        'occupation',
        'company',
        'emergency_contact_name',
        'emergency_contact_phone',
        'preferred_language',
        'timezone',
        'bio',
        'learning_goals',
        'status',
        'onboarding_completed_at',
        'admin_notes',
        'metadata',
        'created_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'onboarding_completed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function activeEnrollments(): HasMany
    {
        return $this->enrollments()->where('status', 'active');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->user?->name ?? 'طالب';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'نشط',
            'suspended' => 'موقوف',
            'inactive' => 'غير نشط',
            'graduated' => 'متخرج',
            default => $this->status,
        };
    }

    public static function generateStudentCode(): string
    {
        $year = now()->format('Y');
        $last = static::query()
            ->where('student_code', 'like', "STU-{$year}-%")
            ->orderByDesc('id')
            ->value('student_code');

        $sequence = 1;
        if ($last && preg_match('/STU-\d{4}-(\d+)/', $last, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return sprintf('STU-%s-%04d', $year, $sequence);
    }
}
