<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseEnrollment extends Model
{
    public const STATUSES = ['pending', 'active', 'completed', 'expired', 'cancelled', 'refunded'];

    public const SOURCES = ['purchase', 'admin_grant', 'free', 'promo'];

    protected $fillable = [
        'student_id',
        'course_id',
        'status',
        'source',
        'enrolled_at',
        'expires_at',
        'completed_at',
        'progress_percent',
        'order_id',
        'granted_by',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'expires_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress_percent' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function grantedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class, 'enrollment_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'قيد الانتظار',
            'active' => 'نشط',
            'completed' => 'مكتمل',
            'expired' => 'منتهي',
            'cancelled' => 'ملغى',
            'refunded' => 'مسترد',
            default => $this->status,
        };
    }

    public function getSourceLabelAttribute(): string
    {
        return match ($this->source) {
            'purchase' => 'شراء',
            'admin_grant' => 'منح إداري',
            'free' => 'مجاني',
            'promo' => 'عرض ترويجي',
            default => $this->source,
        };
    }
}
