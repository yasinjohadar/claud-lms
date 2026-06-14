@php
    $avgMinutes = $stats['average_time'] ? number_format($stats['average_time'] / 60, 1) : '0';
    $kpiCards = [
        [
            'variant' => 'blue',
            'icon' => 'fe-clipboard',
            'label' => 'إجمالي المحاولات',
            'value' => $stats['total_attempts'] ?? 0,
            'sub' => ($stats['completed_attempts'] ?? 0) . ' مكتملة',
        ],
        [
            'variant' => 'green',
            'icon' => 'fe-trending-up',
            'label' => 'متوسط الدرجات',
            'value' => number_format($stats['average_score'] ?? 0, 1),
            'suffix' => '%',
            'sub' => 'أعلى: ' . number_format($stats['highest_score'] ?? 0, 1) . '%',
            'noCountup' => true,
        ],
        [
            'variant' => 'cyan',
            'icon' => 'fe-percent',
            'label' => 'معدل النجاح',
            'value' => number_format($stats['pass_rate'] ?? 0, 1),
            'suffix' => '%',
            'sub' => 'أقل: ' . number_format($stats['lowest_score'] ?? 0, 1) . '%',
            'noCountup' => true,
        ],
        [
            'variant' => 'orange',
            'icon' => 'fe-clock',
            'label' => 'متوسط الوقت',
            'value' => $avgMinutes,
            'suffix' => ' د',
            'sub' => ($stats['in_progress'] ?? 0) . ' قيد التنفيذ',
            'noCountup' => true,
        ],
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
                        <h3 class="admin-stats-card__value mb-1"
                            @empty($card['noCountup']) data-countup="{{ $card['value'] }}" data-suffix="{{ $card['suffix'] ?? '' }}" @endempty>
                            @if(!empty($card['noCountup']))
                                {{ $card['value'] }}{{ $card['suffix'] ?? '' }}
                            @else
                                0
                            @endif
                        </h3>
                        <p class="admin-stats-card__sub mb-0">{{ $card['sub'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
