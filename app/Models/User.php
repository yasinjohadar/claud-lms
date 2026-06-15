<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Symfony\Component\HttpFoundation\Session\Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
      use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'status',
        'is_active',
        'photo',
        'created_by',
        'last_login_at',
        'last_login_ip',
        'last_login_user_agent',
        'referral_code',
        'referred_by_user_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

     public function sessions()
    {
        return $this->hasMany(\App\Models\Session::class, 'user_id');
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student') && $this->student !== null;
    }

    public function scopeStudents(Builder $query): Builder
    {
        return $query->role('student');
    }

    public function enrollments(): HasManyThrough
    {
        return $this->hasManyThrough(
            CourseEnrollment::class,
            Student::class,
            'user_id',
            'student_id',
            'id',
            'id'
        );
    }

    /**
     * إحصائيات التحفيز (جدول user_stats — يستخدمه GamificationService).
     */
    public function stats(): HasOne
    {
        return $this->hasOne(UserStat::class);
    }

    /**
     * إحصائيات التحفيز (جدول gamification_user_stats — بوابة الطالب).
     */
    public function gamificationStats(): HasOne
    {
        return $this->hasOne(Gamification\UserStat::class);
    }

    public function pointsTransactions(): HasMany
    {
        return $this->hasMany(PointsTransaction::class);
    }

    public function gamificationPointTransactions(): HasMany
    {
        return $this->hasMany(Gamification\PointTransaction::class);
    }

    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Gamification\Badge::class, 'gamification_user_badges', 'user_id', 'badge_id')
            ->withPivot('awarded_at')
            ->withTimestamps();
    }

    public function legacyBadges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot([
                'awarded_at',
                'reason',
                'related_type',
                'related_id',
                'progress',
                'is_seen',
                'is_featured',
                'points_awarded',
                'metadata',
            ])
            ->withTimestamps();
    }

    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function userChallenges(): HasMany
    {
        return $this->hasMany(UserChallenge::class);
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Gamification\Achievement::class, 'gamification_user_achievements', 'user_id', 'achievement_id')
            ->withPivot(['unlocked_at', 'status', 'completed_at'])
            ->withTimestamps();
    }

    public function legacyAchievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot([
                'current_value',
                'target_value',
                'progress_percentage',
                'status',
                'started_at',
                'completed_at',
                'claimed_at',
                'related_type',
                'related_id',
                'progress_data',
                'points_claimed',
                'xp_claimed',
                'is_notified',
            ])
            ->withTimestamps();
    }

    public function gamificationNotifications(): HasMany
    {
        return $this->hasMany(GamificationNotification::class);
    }
}