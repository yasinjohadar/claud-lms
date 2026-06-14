@php
    $kpiCards = [
        ['variant' => 'orange', 'icon' => 'fe-alert-circle', 'label' => 'بانتظار التصحيح', 'value' => $stats['pending_grading'] ?? 0, 'sub' => 'تحتاج مراجعة'],
        ['variant' => 'purple', 'icon' => 'fe-layers', 'label' => 'مُصحح جزئياً', 'value' => $stats['partially_graded'] ?? 0, 'sub' => 'بانتظار إكمال'],
        ['variant' => 'green', 'icon' => 'fe-check-circle', 'label' => 'مُصحح اليوم', 'value' => $stats['fully_graded'] ?? 0, 'sub' => 'مكتمل بالكامل'],
        ['variant' => 'blue', 'icon' => 'fe-file-text', 'label' => 'إجمالي المحاولات', 'value' => $attempts->total(), 'sub' => 'محاولات مُسلمة'],
    ];
@endphp

<div class="row g-3 dashboard-fade-in exam-page-animate mb-0">
    @foreach ($kpiCards as $index => $card)
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 dashboard-stagger-item exam-page-animate" style="--stagger-delay: {{ $index * 70 }}ms">
            <div class="card admin-stats-card admin-stats-card--{{ $card['variant'] }}">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="admin-stats-card__icon-wrap">
                        <i class="fe {{ $card['icon'] }} admin-stats-card__icon"></i>
                    </div>
                    <div class="admin-stats-card__content flex-fill min-w-0">
                        <p class="admin-stats-card__label mb-1">{{ $card['label'] }}</p>
                        <h3 class="admin-stats-card__value mb-1" data-countup="{{ $card['value'] }}">0</h3>
                        <p class="admin-stats-card__sub mb-0">{{ $card['sub'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
