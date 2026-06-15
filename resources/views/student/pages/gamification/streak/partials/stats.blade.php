@php
    $currentStreak = (int) ($streakInfo['current_streak'] ?? 0);
    $longestStreak = (int) ($streakInfo['longest_streak'] ?? 0);
    $isActive = $currentStreak > 0;
    $multiplier = $streakInfo['current_multiplier'] ?? 1.0;
@endphp

<div class="row g-3 mb-4">
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'orange',
        'icon' => 'ri-fire-line',
        'label' => 'السلسلة الحالية',
        'value' => number_format($currentStreak),
        'hint' => $isActive ? 'نشطة — واصل اليوم!' : 'غير نشطة — ابدأ اليوم',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'purple',
        'icon' => 'ri-trophy-line',
        'label' => 'أطول سلسلة',
        'value' => number_format($longestStreak),
        'hint' => 'أفضل رقم حققته',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'cyan',
        'icon' => 'ri-calendar-check-line',
        'label' => 'أيام نشطة',
        'value' => number_format($monthlyStats['active_days'] ?? 0),
        'hint' => 'هذا الشهر',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'green',
        'icon' => 'ri-percent-line',
        'label' => 'مضاعف النقاط',
        'value' => '×' . rtrim(rtrim(number_format($multiplier, 1), '0'), '.'),
        'hint' => $isActive ? 'مفعّل على سلسلتك' : 'يبدأ من 3 أيام',
    ])
</div>
