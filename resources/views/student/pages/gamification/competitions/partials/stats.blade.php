<div class="row g-3 mb-4">
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'purple',
        'icon' => 'ri-sword-line',
        'label' => 'شاركت في',
        'value' => number_format($stats['total_participated'] ?? 0),
        'hint' => 'إجمالي المسابقات',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'green',
        'icon' => 'ri-trophy-line',
        'label' => 'فوز',
        'value' => number_format($stats['total_won'] ?? 0),
        'hint' => 'مسابقات ربحتها',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'orange',
        'icon' => 'ri-flashlight-line',
        'label' => 'نشطة الآن',
        'value' => number_format($stats['active_competitions'] ?? 0),
        'hint' => 'مسابقات جارية',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'cyan',
        'icon' => 'ri-percent-line',
        'label' => 'نسبة الفوز',
        'value' => ($stats['win_rate'] ?? 0) . '%',
        'hint' => 'من إجمالي مشاركاتك',
    ])
</div>
