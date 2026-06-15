@php
    $typeLabels = [
        'points' => 'النقاط',
        'xp' => 'الخبرة',
        'lessons' => 'الدروس',
        'quizzes' => 'الاختبارات',
        'streak' => 'السلسلة',
    ];
    $typeIcons = [
        'points' => 'ri-coin-line',
        'xp' => 'ri-flashlight-line',
        'lessons' => 'ri-book-open-line',
        'quizzes' => 'ri-question-answer-line',
        'streak' => 'ri-fire-line',
    ];
    $type = $competition->type ?? 'points';
    $variant = $variant ?? ($state === 'completed' ? 'completed' : 'active');
    $participation = $competition->my_participation ?? null;
    $target = (int) ($competition->target_value ?? 0);
    $current = (int) ($participation->current_value ?? 0);
    $progress = $target > 0 ? min(100, round(($current / $target) * 100)) : 0;
    $rank = (int) ($participation->rank ?? 0);
    $participantsCount = $competition->participants?->count() ?? 0;
    $delay = ($index ?? 0) * 45;
    $endsAt = $competition->ends_at;
    $isWinner = (bool) ($participation->is_winner ?? false);
@endphp

<div class="col-xl-4 col-lg-6 competition-grid-item" style="--competition-delay: {{ $delay }}ms">
    <article class="gamification-competition-widget gamification-competition-widget--{{ $type }} gamification-competition-widget--{{ $variant }} {{ $isWinner ? 'is-winner' : '' }}">
        <span class="gamification-competition-widget__glow" aria-hidden="true"></span>
        <span class="gamification-competition-widget__shine" aria-hidden="true"></span>

        <span class="gamification-competition-widget__type">
            <i class="{{ $typeIcons[$type] ?? 'ri-trophy-line' }}"></i>
            {{ $typeLabels[$type] ?? $type }}
        </span>

        @if($isWinner)
            <span class="gamification-competition-widget__badge is-winner"><i class="ri-trophy-fill"></i> فائز</span>
        @endif

        <div class="gamification-competition-widget__icon-wrap">
            <span class="gamification-competition-widget__icon"><i class="ri-sword-line"></i></span>
        </div>

        <h6 class="gamification-competition-widget__title">{{ $competition->name }}</h6>

        <div class="gamification-competition-widget__meta">
            <span><i class="ri-group-line"></i> {{ $participantsCount }} مشارك</span>
            @if($rank > 0)
                <span><i class="ri-medal-line"></i> ترتيبك #{{ $rank }}</span>
            @endif
        </div>

        @if($state !== 'completed' && $endsAt)
            <p class="gamification-competition-widget__countdown">
                <i class="ri-time-line"></i>
                ينتهي {{ $endsAt->diffForHumans() }}
            </p>
        @elseif($state === 'completed' && $competition->completed_at)
            <p class="gamification-competition-widget__countdown">
                <i class="ri-checkbox-circle-line"></i>
                اكتمل {{ $competition->completed_at->diffForHumans() }}
            </p>
        @endif

        @if($target > 0)
            <div class="gamification-competition-widget__progress">
                <div class="gamification-competition-widget__progress-meta">
                    <span>{{ number_format($current) }} / {{ number_format($target) }}</span>
                    <span>{{ $progress }}%</span>
                </div>
                <div class="gamification-competition-widget__progress-track">
                    <div class="gamification-competition-widget__progress-bar" style="width: {{ max($progress > 0 ? 6 : 0, $progress) }}%"></div>
                </div>
            </div>
        @endif

        <div class="gamification-competition-widget__footer">
            @if($state === 'completed')
                <span class="gamification-competition-widget__status is-done">
                    <i class="ri-flag-line"></i> منتهية
                </span>
            @else
                <span class="gamification-competition-widget__status is-running">
                    <i class="ri-flashlight-line"></i> جارية
                </span>
            @endif
        </div>
    </article>
</div>
