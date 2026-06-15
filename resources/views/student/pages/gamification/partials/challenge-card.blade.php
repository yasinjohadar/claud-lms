@php
    $progress = (int) ($userChallenge->progress_percentage ?? $challenge->user_progress ?? 0);
    $current = (int) ($userChallenge->current_value ?? 0);
    $target = (int) ($userChallenge->target_value ?? $challenge->target_value ?? 1);
    $status = $userChallenge->status ?? ($challenge->user_status ?? 'not_started');
    $type = $challenge->type ?? 'daily';
    $icon = $challenge->icon ?? '🎯';
    $points = (int) ($challenge->points_reward ?? 0);
    $xp = (int) ($challenge->xp_reward ?? 0);

    $isCompleted = $status === 'completed';
    $isInProgress = in_array($status, ['active', 'in_progress'], true);
    $canAccept = ($showActions ?? false) && $status === 'not_started' && !$isCompleted;

    $typeLabels = [
        'daily' => 'يومي',
        'weekly' => 'أسبوعي',
        'monthly' => 'شهري',
        'special' => 'خاص',
    ];

    $stateClass = $isCompleted ? 'is-completed' : ($isInProgress ? 'is-active' : 'is-locked');
    $delay = ($index ?? 0) * 45;
    $showUrl = isset($challenge->id) ? route('gamification.challenges.show', $challenge) : null;
@endphp

<div class="col-lg-4 col-md-6 challenge-grid-item" style="--challenge-delay: {{ $delay }}ms">
    <article class="gamification-challenge-widget gamification-challenge-widget--{{ $type }} {{ $stateClass }}"
        @if($showUrl && !$canAccept) onclick="window.location='{{ $showUrl }}'" style="cursor:pointer;" @endif>
        <span class="gamification-challenge-widget__glow" aria-hidden="true"></span>
        <span class="gamification-challenge-widget__shine" aria-hidden="true"></span>

        <span class="gamification-challenge-widget__type">{{ $typeLabels[$type] ?? $type }}</span>

        <div class="gamification-challenge-widget__icon-wrap">
            <span class="gamification-challenge-widget__icon">{{ $icon }}</span>
            @if($isCompleted)
                <span class="gamification-challenge-widget__earned-mark" aria-hidden="true">
                    <i class="ri-checkbox-circle-fill"></i>
                </span>
            @endif
        </div>

        <h6 class="gamification-challenge-widget__title">{{ $challenge->name }}</h6>

        @if($challenge->description)
            <p class="gamification-challenge-widget__desc">{{ Str::limit($challenge->description, 95) }}</p>
        @endif

        <span class="gamification-challenge-widget__points">+{{ number_format($points) }} نقطة</span>
        @if($xp > 0)
            <span class="gamification-challenge-widget__xp">+{{ number_format($xp) }} XP</span>
        @endif

        <div class="gamification-challenge-widget__progress">
            <div class="gamification-challenge-widget__progress-meta">
                <span>{{ $current }} / {{ $target }}</span>
                <span>{{ $progress }}%</span>
            </div>
            <div class="gamification-challenge-widget__progress-track">
                <div class="gamification-challenge-widget__progress-bar" style="width: {{ max($isInProgress || $isCompleted ? 6 : 0, min(100, $progress)) }}%"></div>
            </div>
        </div>

        <div class="gamification-challenge-widget__footer">
            @if($isCompleted)
                <span class="gamification-challenge-widget__status is-done">
                    <i class="ri-checkbox-circle-line"></i>مكتمل
                </span>
            @elseif($canAccept)
                <button type="button" class="gamification-challenge-widget__accept btn-wave"
                        data-challenge-accept data-challenge-id="{{ $challenge->id }}">
                    <i class="ri-flashlight-line me-1"></i>قبول التحدي
                </button>
            @elseif($isInProgress)
                <span class="gamification-challenge-widget__status is-running">
                    <i class="ri-loader-4-line"></i>قيد التنفيذ
                </span>
            @else
                <span class="gamification-challenge-widget__status">
                    <i class="ri-lock-line"></i>غير مبدوء
                </span>
            @endif
        </div>
    </article>
</div>
