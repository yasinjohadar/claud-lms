<div class="row g-3 mb-4">
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'purple',
        'icon' => 'ri-clipboard-line',
        'label' => 'إجمالي الاختبارات',
        'value' => number_format($stats['total'] ?? 0),
        'hint' => 'في كورساتك النشطة',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'green',
        'icon' => 'ri-play-circle-line',
        'label' => 'متاح للبدء',
        'value' => number_format($stats['can_attempt'] ?? 0),
        'hint' => 'يمكنك محاولتها الآن',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'cyan',
        'icon' => 'ri-check-double-line',
        'label' => 'بدأتها',
        'value' => number_format($stats['attempted'] ?? 0),
        'hint' => 'اختبارات جرّبتها',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'orange',
        'icon' => 'ri-filter-3-line',
        'label' => 'نتائج التصفية',
        'value' => number_format($stats['filtered'] ?? 0),
        'hint' => 'حسب الفلاتر الحالية',
    ])
</div>
