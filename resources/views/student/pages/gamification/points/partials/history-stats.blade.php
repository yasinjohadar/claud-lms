@php
    $statCards = [
        ['variant' => 'purple', 'icon' => 'ri-file-list-3-line', 'label' => 'إجمالي المعاملات', 'value' => number_format($stats['total_transactions'] ?? 0), 'hint' => 'كل السجل'],
        ['variant' => 'green', 'icon' => 'ri-arrow-up-circle-line', 'label' => 'إجمالي المكتسب', 'value' => '+' . number_format($stats['total_earned'] ?? 0), 'hint' => 'نقاط مكتسبة'],
        ['variant' => 'orange', 'icon' => 'ri-arrow-down-circle-line', 'label' => 'إجمالي المستهلك', 'value' => '-' . number_format($stats['total_spent'] ?? 0), 'hint' => 'نقاط مصروفة'],
        ['variant' => 'cyan', 'icon' => 'ri-calendar-check-line', 'label' => 'هذا الشهر', 'value' => '+' . number_format($stats['this_month_earned'] ?? 0), 'hint' => now()->translatedFormat('F Y')],
    ];
@endphp

<div class="row g-3 mb-4">
    @foreach ($statCards as $card)
        @include('admin.partials.ui.stat-card-gradient', $card)
    @endforeach
</div>
