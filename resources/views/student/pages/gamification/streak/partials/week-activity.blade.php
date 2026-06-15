@php
    $activeDays = ($streakInfo['last_7_days'] ?? collect())->keyBy(function ($day) {
        return \Carbon\Carbon::parse($day->date)->format('Y-m-d');
    });
    $weekDays = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
@endphp

<div class="gamification-streak-week mb-4">
    <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <h6 class="mb-0 fw-semibold fs-13">
            <i class="ri-calendar-2-line text-primary me-1"></i>
            آخر 7 أيام
        </h6>
        @if(!empty($streakInfo['is_active_today']))
            <span class="badge bg-success-transparent text-success">نشط اليوم</span>
        @else
            <span class="badge bg-secondary-transparent text-muted">لم تسجّل نشاطاً اليوم</span>
        @endif
    </div>
    <div class="gamification-streak-week__grid">
        @for ($i = 6; $i >= 0; $i--)
            @php
                $date = now()->subDays($i);
                $dateKey = $date->format('Y-m-d');
                $isActive = $activeDays->has($dateKey);
                $isToday = $date->isToday();
            @endphp
            <div class="gamification-streak-week__day {{ $isActive ? 'is-active' : '' }} {{ $isToday ? 'is-today' : '' }}" title="{{ $date->translatedFormat('l j F') }}">
                <span class="gamification-streak-week__dot">
                    @if($isActive)
                        <i class="ri-fire-fill"></i>
                    @else
                        <i class="ri-subtract-line"></i>
                    @endif
                </span>
                <span class="gamification-streak-week__label">{{ $weekDays[$date->dayOfWeek] ?? $date->translatedFormat('D') }}</span>
                <span class="gamification-streak-week__num">{{ $date->format('j') }}</span>
            </div>
        @endfor
    </div>
</div>
