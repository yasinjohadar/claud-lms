@php
    $gems = $userStats->available_gems ?? ($userStats->additional_stats['gems'] ?? 0);
@endphp

<div class="row g-3 mb-4">
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'purple',
        'icon' => 'ri-star-line',
        'label' => 'النقاط',
        'value' => number_format($dashboard['points']['total'] ?? 0),
        'hint' => 'إجمالي نقاطك',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'green',
        'icon' => 'ri-bar-chart-grouped-line',
        'label' => 'المستوى',
        'value' => $levelInfo['current_level'] ?? 1,
        'hint' => number_format($levelInfo['total_xp'] ?? 0) . ' XP',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'cyan',
        'icon' => 'ri-vip-diamond-line',
        'label' => 'الجواهر',
        'value' => number_format($gems),
        'hint' => 'رصيد الجواهر',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'orange',
        'icon' => 'ri-fire-line',
        'label' => 'السلسلة',
        'value' => number_format($streakInfo['current_streak'] ?? 0),
        'hint' => 'أيام متتالية',
    ])
</div>
