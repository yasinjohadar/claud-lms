<div class="row g-3 mb-4">
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'purple',
        'icon' => 'ri-group-line',
        'label' => 'الأصدقاء',
        'value' => number_format($stats['total_friends'] ?? 0),
        'hint' => 'صداقات مقبولة',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'orange',
        'icon' => 'ri-mail-unread-line',
        'label' => 'طلبات واردة',
        'value' => number_format($stats['pending_requests'] ?? 0),
        'hint' => 'بانتظار موافقتك',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'cyan',
        'icon' => 'ri-send-plane-line',
        'label' => 'طلبات مرسلة',
        'value' => number_format($stats['sent_requests'] ?? 0),
        'hint' => 'في انتظار الرد',
    ])
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'green',
        'icon' => 'ri-user-search-line',
        'label' => 'اقتراحات',
        'value' => number_format(count($suggestions ?? [])),
        'hint' => 'طلاب قد تعرفهم',
    ])
</div>
