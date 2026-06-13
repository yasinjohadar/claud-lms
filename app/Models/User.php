<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
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
}