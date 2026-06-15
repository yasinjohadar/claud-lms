@php
    $rareEarned = ($stats['by_rarity']['rare'] ?? 0)
        + ($stats['by_rarity']['epic'] ?? 0)
        + ($stats['by_rarity']['legendary'] ?? 0)
        + ($stats['by_rarity']['mythic'] ?? 0);
@endphp

<div class="row g-3 mb-4">
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'green',
        'icon' => 'ri-medal-line',
        'label' => 'شارات مكتسبة',
        'value' => number_format($stats['total_earned'] ?? 0),
        'hint' => 'في مجموعتك',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'purple',
        'icon' => 'ri-stack-line',
        'label' => 'إجمالي الشارات',
        'value' => number_format($stats['total_available'] ?? 0),
        'hint' => 'متاحة في المنصة',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'orange',
        'icon' => 'ri-pie-chart-line',
        'label' => 'نسبة الإكمال',
        'value' => round($stats['completion_rate'] ?? 0, 1) . '%',
        'hint' => 'من الإجمالي',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'cyan',
        'icon' => 'ri-vip-crown-line',
        'label' => 'شارات نادرة+',
        'value' => number_format($rareEarned),
        'hint' => 'نادرة وما فوق',
    ])
</div>
