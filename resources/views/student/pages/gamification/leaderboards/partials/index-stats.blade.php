@php
    $statCards = [
        ['variant' => 'purple', 'icon' => 'ri-trophy-line', 'label' => 'اللوحات المتاحة', 'value' => number_format($indexStats['total_boards'] ?? 0), 'hint' => 'لوحات نشطة'],
        ['variant' => 'green', 'icon' => 'ri-user-star-line', 'label' => 'لوحات دخلتها', 'value' => number_format($indexStats['ranked_boards'] ?? 0), 'hint' => 'لديك ترتيب فيها'],
        ['variant' => 'orange', 'icon' => 'ri-medal-line', 'label' => 'أفضل ترتيب', 'value' => isset($indexStats['best_rank']) ? '#' . $indexStats['best_rank'] : '—', 'hint' => 'أعلى مركز حققته'],
        ['variant' => 'cyan', 'icon' => 'ri-group-line', 'label' => 'إجمالي المشاركين', 'value' => number_format($indexStats['total_participants'] ?? 0), 'hint' => 'عبر كل اللوحات'],
    ];
@endphp

<div class="row g-3 mb-4">
    @foreach ($statCards as $card)
        @include('admin.partials.ui.stat-card-gradient', $card)
    @endforeach
</div>
