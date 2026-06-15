@php
    $currentStreak = (int) ($streakInfo['current_streak'] ?? 0);
    $longestStreak = (int) ($streakInfo['longest_streak'] ?? 0);
    $nextMilestone = $streakInfo['next_milestone'] ?? null;
    $rewards = $streakRewards ?? [];
@endphp

@if(count($rewards) > 0)
    <div class="gamification-streak-roadmap">
        @foreach($rewards as $days => $reward)
            @php
                $days = (int) $days;
                $isAchieved = $longestStreak >= $days;
                $isNext = $nextMilestone && (int) ($nextMilestone['days'] ?? 0) === $days;
                $progress = $isAchieved ? 100 : ($isNext && $days > 0 ? min(100, round(($currentStreak / $days) * 100)) : 0);
            @endphp
            <div class="gamification-streak-milestone {{ $isAchieved ? 'is-achieved' : '' }} {{ $isNext ? 'is-next' : '' }}">
                <div class="gamification-streak-milestone__marker">
                    @if($isAchieved)
                        <i class="ri-checkbox-circle-fill"></i>
                    @elseif($isNext)
                        <i class="ri-fire-fill"></i>
                    @else
                        <i class="ri-lock-line"></i>
                    @endif
                </div>
                <div class="gamification-streak-milestone__body">
                    <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                        <div>
                            <strong class="gamification-streak-milestone__days">{{ $days }} يوم</strong>
                            @if(!empty($reward['description']))
                                <p class="gamification-streak-milestone__desc mb-0">{{ $reward['description'] }}</p>
                            @endif
                        </div>
                        <span class="badge {{ $isAchieved ? 'bg-success-transparent text-success' : 'bg-warning-transparent text-warning' }}">
                            +{{ number_format($reward['points'] ?? 0) }} نقطة
                        </span>
                    </div>
                    @if($isNext)
                        <div class="gamification-streak-milestone__progress-track">
                            <div class="gamification-streak-milestone__progress-bar" style="width: {{ max(4, $progress) }}%"></div>
                        </div>
                        <small class="text-muted fs-11">{{ $currentStreak }} / {{ $days }} يوم</small>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-4">
        <div class="empty-state-icon mx-auto mb-3"><i class="ri-gift-line"></i></div>
        <p class="text-muted mb-0">لا توجد مكافآت محددة</p>
    </div>
@endif
