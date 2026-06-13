<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TeamMember extends Model
{
    public const AVATAR_TYPES = ['user', 'icon', 'upload'];

    public const TEAM_GROUPS = ['instructor', 'admin', 'management'];

    public const SOCIAL_PLATFORMS = [
        'linkedin' => ['label' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in'],
        'youtube' => ['label' => 'YouTube', 'icon' => 'fab fa-youtube'],
        'github' => ['label' => 'GitHub', 'icon' => 'fab fa-github'],
        'twitter' => ['label' => 'Twitter / X', 'icon' => 'fab fa-twitter'],
        'behance' => ['label' => 'Behance', 'icon' => 'fab fa-behance'],
        'dribbble' => ['label' => 'Dribbble', 'icon' => 'fab fa-dribbble'],
        'facebook' => ['label' => 'Facebook', 'icon' => 'fab fa-facebook-f'],
        'instagram' => ['label' => 'Instagram', 'icon' => 'fab fa-instagram'],
    ];

    protected $fillable = [
        'user_id',
        'name',
        'role_title',
        'bio',
        'avatar_type',
        'avatar_icon',
        'avatar_path',
        'accent_color',
        'rating',
        'courses_count',
        'social_links',
        'team_group',
        'show_on_home',
        'show_on_page',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'rating' => 'float',
        'courses_count' => 'integer',
        'social_links' => 'array',
        'show_on_home' => 'boolean',
        'show_on_page' => 'boolean',
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeForHome(Builder $query): Builder
    {
        return $query->published()->where('show_on_home', true);
    }

    public function scopeForTeamPage(Builder $query): Builder
    {
        return $query->published()->where('show_on_page', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function isLinkedToUser(): bool
    {
        return $this->user_id !== null;
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->name) {
            return $this->name;
        }

        return $this->user?->name ?? 'عضو الفريق';
    }

    public function getTeamGroupLabelAttribute(): string
    {
        return match ($this->team_group) {
            'instructor' => 'مدربون',
            'admin' => 'فريق إداري',
            'management' => 'إدارة',
            default => $this->team_group,
        };
    }

    public function getDisplayCoursesCountAttribute(): ?int
    {
        if ($this->courses_count !== null) {
            return $this->courses_count;
        }

        if ($this->user_id) {
            return Course::query()
                ->where('instructor_id', $this->user_id)
                ->where('status', 'published')
                ->count();
        }

        return null;
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar_type === 'upload' && $this->avatar_path) {
            return Storage::disk('public')->url($this->avatar_path);
        }

        if ($this->avatar_type === 'user' && $this->user?->photo) {
            return asset('storage/' . $this->user->photo);
        }

        return null;
    }

    public function getResolvedSocialLinksAttribute(): array
    {
        $links = $this->social_links ?? [];

        return collect($links)
            ->filter(fn ($link) => ! empty($link['platform']) && ! empty($link['url']))
            ->map(function ($link) {
                $platform = $link['platform'];
                $meta = self::SOCIAL_PLATFORMS[$platform] ?? null;

                return [
                    'platform' => $platform,
                    'url' => $link['url'],
                    'label' => $meta['label'] ?? $platform,
                    'icon' => $meta['icon'] ?? 'fas fa-link',
                ];
            })
            ->values()
            ->all();
    }

    public function getStarStateAttribute(): array
    {
        $rating = max(0, min(5, (float) ($this->rating ?? 0)));
        $full = (int) floor($rating);
        $hasHalf = ($rating - $full) >= 0.25 && ($rating - $full) < 0.75;
        if (($rating - $full) >= 0.75) {
            $full++;
            $hasHalf = false;
        }
        $empty = 5 - $full - ($hasHalf ? 1 : 0);

        return compact('full', 'hasHalf', 'empty');
    }
}
