<div class="row g-3 mb-4">
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'purple',
        'icon' => 'ri-rss-line',
        'label' => 'منشوراتي',
        'value' => number_format($stats['total_activities'] ?? 0),
        'hint' => 'أنشطة شاركتها',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'pink',
        'icon' => 'ri-thumb-up-line',
        'label' => 'إعجابات',
        'value' => number_format($stats['total_likes_received'] ?? 0),
        'hint' => 'وصلت لمنشوراتك',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'cyan',
        'icon' => 'ri-chat-3-line',
        'label' => 'تعليقات',
        'value' => number_format($stats['total_comments_received'] ?? 0),
        'hint' => 'على أنشطتك',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'green',
        'icon' => 'ri-fire-line',
        'label' => 'في الخلاصة',
        'value' => number_format($activities->count()),
        'hint' => 'آخر الأنشطة',
    ])
</div>
