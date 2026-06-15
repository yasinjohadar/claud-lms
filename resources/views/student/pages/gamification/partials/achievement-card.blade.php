@php
    $achievement = $userAchievement->achievement;
@endphp
@if(!$achievement)
    {{-- سجل يشير لإنجاز محذوف أو غير متاح --}}
@else
@php
    $isLocked = $isLocked ?? false;
    $isCompleted = $isCompleted ?? in_array($userAchievement->status, ['completed', 'claimed'], true);
    $progress = (float) ($userAchievement->progress_percentage ?? 0);
    $current = (int) ($userAchievement->current_value ?? 0);
    $target = (int) ($achievement->target_value ?? 1);
    $points = (int) ($achievement->points_reward ?? 0);
    $tier = $achievement->tier ?? 'bronze';
    $icon = $achievement->icon ?? '🏆';
    $isNearComplete = !$isCompleted && $progress >= 70;

    $statusKey = $isCompleted ? 'completed' : ($isLocked || $progress <= 0 ? 'not_started' : 'in_progress');

    $tierLabels = [
        'bronze' => 'برونزي',
        'silver' => 'فضي',
        'gold' => 'ذهبي',
        'platinum' => 'بلاتيني',
        'diamond' => 'ماسي',
    ];

    $requirementText = \App\Support\Gamification\AchievementCriteriaMapper::formatForDisplay(
        $achievement->criteria,
        $achievement->target_value
    );

    $completedAt = $userAchievement->completed_at
        ? $userAchievement->completed_at->format('Y/m/d')
        : '';

    $stateClass = $isCompleted ? 'is-completed' : ($isLocked || $progress <= 0 ? 'is-locked' : 'is-active');
    $delay = ($index ?? 0) * 45;
@endphp

<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 achievement-grid-item"
     style="--achievement-delay: {{ $delay }}ms"
     data-achievement-status="{{ $statusKey }}"
     data-achievement-tier="{{ $tier }}">
    <article class="gamification-achievement-widget gamification-achievement-widget--{{ $tier }} {{ $stateClass }} {{ $isNearComplete ? 'is-near-complete' : '' }}"
        role="button"
        tabindex="0"
        data-achievement-open
        data-name="{{ $achievement->name }}"
        data-description="{{ $achievement->description ?? '' }}"
        data-tier="{{ $tierLabels[$tier] ?? $tier }}"
        data-tier-key="{{ $tier }}"
        data-icon="{{ $icon }}"
        data-progress="{{ $progress }}"
        data-current="{{ $current }}"
        data-target="{{ $target }}"
        data-points="{{ $points }}"
        data-status="{{ $statusKey }}"
        data-requirement="{{ $requirementText }}"
        data-completed-at="{{ $completedAt }}"
        data-show-url="{{ route('gamification.achievements.show', $achievement) }}"
        data-claim-url="{{ $userAchievement->status === 'completed' && $points > 0 ? route('gamification.achievements.claim', $userAchievement) : '' }}">
        <span class="gamification-achievement-widget__glow" aria-hidden="true"></span>
        <span class="gamification-achievement-widget__shine" aria-hidden="true"></span>

        <span class="gamification-achievement-widget__tier">{{ $tierLabels[$tier] ?? $tier }}</span>

        <div class="gamification-achievement-widget__icon-wrap">
            <span class="gamification-achievement-widget__icon">{{ $icon }}</span>
            @if($isCompleted)
                <span class="gamification-achievement-widget__earned-mark" aria-hidden="true">
                    <i class="ri-checkbox-circle-fill"></i>
                </span>
            @elseif($isNearComplete)
                <span class="gamification-achievement-widget__pulse" aria-hidden="true"></span>
            @endif
        </div>

        <h6 class="gamification-achievement-widget__title">{{ $achievement->name }}</h6>

        @if($achievement->description)
            <p class="gamification-achievement-widget__desc">{{ Str::limit($achievement->description, 88) }}</p>
        @endif

        <p class="gamification-achievement-widget__requirement">
            <i class="ri-flag-line"></i>{{ $requirementText }}
        </p>

        @if($isCompleted)
            <div class="gamification-achievement-widget__footer">
                @if($points > 0)
                    <span class="gamification-achievement-widget__points">+{{ number_format($points) }} نقطة</span>
                @endif
                @if($completedAt)
                    <span class="gamification-achievement-widget__status is-done">
                        <i class="ri-calendar-line"></i>{{ $completedAt }}
                    </span>
                @else
                    <span class="gamification-achievement-widget__status is-done">
                        <i class="ri-checkbox-circle-line"></i>مكتمل
                    </span>
                @endif
            </div>
        @else
            <div class="gamification-achievement-widget__progress">
                <div class="gamification-achievement-widget__progress-meta">
                    <span>التقدم</span>
                    <span>{{ number_format($progress, 0) }}%</span>
                </div>
                <div class="gamification-achievement-widget__progress-track">
                    <div class="gamification-achievement-widget__progress-bar" style="width: {{ max(6, min(100, $progress)) }}%"></div>
                </div>
                <div class="gamification-achievement-widget__progress-foot">
                    <span>{{ $current }} / {{ $target }}</span>
                    @if($points > 0)
                        <span>{{ number_format($points) }} نقطة</span>
                    @endif
                </div>
            </div>
        @endif

        <span class="gamification-achievement-widget__hint">
            <i class="ri-external-link-line"></i>اضغط للتفاصيل
        </span>
    </article>
</div>
@endif
