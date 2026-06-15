@php
    $key = $method['key'] ?? 'default';
    $category = $method['category'] ?? 'learning';
    $variants = [
        'learning' => 'learning',
        'gamification' => 'achievement',
        'social' => 'social',
        'referral' => 'referral',
        'streak' => 'streak',
    ];
    $variant = $variants[$category] ?? 'default';

    $methodUrls = [
        'lesson_completion' => route('student.courses.index'),
        'video_watch' => route('student.courses.index'),
        'quiz_completion' => route('student.quizzes.index'),
        'perfect_score' => route('student.quizzes.index'),
        'assignment_submission' => route('student.courses.index'),
        'course_completion' => route('student.courses.index'),
        'daily_login' => route('gamification.streak.index'),
        'comment_post' => route('gamification.social.index'),
        'comment_like' => route('gamification.social.index'),
        'course_share' => route('student.courses.index'),
    ];
    $url = $methodUrls[$key] ?? null;

    $icon = $method['icon'] ?? 'ri-star-line';
    $points = (int) ($method['points'] ?? 0);
    $xp = (int) ($method['xp'] ?? 0);
    $delay = ($index ?? 0) * 45;

    $categoryLabels = [
        'learning' => 'تعلم',
        'gamification' => 'تلعيب',
        'social' => 'اجتماعي',
        'referral' => 'إحالة',
        'streak' => 'سلسلة',
    ];
@endphp

<div class="col-md-6 points-earn-grid-item" style="--points-earn-delay: {{ $delay }}ms">
    @if($url)
        <a href="{{ $url }}" class="gamification-points-earn-widget gamification-points-earn-widget--{{ $variant }}">
    @else
        <article class="gamification-points-earn-widget gamification-points-earn-widget--{{ $variant }}">
    @endif
        <span class="gamification-points-earn-widget__glow" aria-hidden="true"></span>
        <span class="gamification-points-earn-widget__shine" aria-hidden="true"></span>

        <span class="gamification-points-earn-widget__tag">{{ $categoryLabels[$category] ?? $category }}</span>

        <div class="gamification-points-earn-widget__icon-wrap">
            <span class="gamification-points-earn-widget__icon">
                <i class="{{ $icon }}"></i>
            </span>
        </div>

        <h6 class="gamification-points-earn-widget__title">{{ $method['title'] }}</h6>

        @if(!empty($method['description']))
            <p class="gamification-points-earn-widget__desc">{{ Str::limit($method['description'], 90) }}</p>
        @endif

        @if(!empty($method['extra']))
            <p class="gamification-points-earn-widget__extra">{{ Str::limit($method['extra'], 72) }}</p>
        @endif

        <div class="gamification-points-earn-widget__rewards">
            <span class="gamification-points-earn-widget__points">+{{ number_format($points) }} نقطة</span>
            @if($xp > 0)
                <span class="gamification-points-earn-widget__xp">+{{ number_format($xp) }} XP</span>
            @endif
        </div>

        @if($url)
            <span class="gamification-points-earn-widget__cta">
                <i class="ri-arrow-left-line"></i> ابدأ الآن
            </span>
        @endif
    @if($url)
        </a>
    @else
        </article>
    @endif
</div>
