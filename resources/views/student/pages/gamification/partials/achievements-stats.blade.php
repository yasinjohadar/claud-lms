@php
    $statCards = [
        ['variant' => 'green', 'icon' => 'ri-checkbox-circle-line', 'label' => 'إنجازات مكتملة', 'value' => number_format($stats['completed'] ?? 0), 'hint' => 'أنجزتها بنجاح'],
        ['variant' => 'purple', 'icon' => 'ri-stack-line', 'label' => 'إجمالي الإنجازات', 'value' => number_format($stats['total_available'] ?? 0), 'hint' => 'متاحة في المنصة'],
        ['variant' => 'orange', 'icon' => 'ri-pie-chart-line', 'label' => 'نسبة الإكمال', 'value' => round($stats['completion_rate'] ?? 0, 1) . '%', 'hint' => 'من الإجمالي'],
        ['variant' => 'cyan', 'icon' => 'ri-loader-4-line', 'label' => 'قيد التقدم', 'value' => number_format($stats['in_progress'] ?? 0), 'hint' => 'تعمل عليها الآن'],
    ];
@endphp

<div class="row g-3 mb-4">
    @foreach ($statCards as $card)
        @include('admin.partials.ui.stat-card-gradient', $card)
    @endforeach
</div>
