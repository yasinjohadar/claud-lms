@php
    $kpiCards = [
        [
            'variant' => 'blue',
            'icon' => 'fe-clipboard',
            'label' => 'إجمالي الاختبارات',
            'value' => $stats['total_quizzes'] ?? 0,
            'sub' => 'اختبارات + وحدات أسئلة',
        ],
        [
            'variant' => 'green',
            'icon' => 'fe-check-circle',
            'label' => 'المحاولات المكتملة',
            'value' => $stats['completed_attempts'] ?? 0,
            'sub' => 'من أصل ' . ($stats['total_attempts'] ?? 0),
        ],
        [
            'variant' => 'cyan',
            'icon' => 'fe-trending-up',
            'label' => 'متوسط الدرجات',
            'value' => number_format($stats['average_score'] ?? 0, 1),
            'suffix' => '%',
            'sub' => 'عبر كل المحاولات',
            'noCountup' => true,
        ],
        [
            'variant' => 'orange',
            'icon' => 'fe-users',
            'label' => 'عدد الطلاب',
            'value' => $stats['total_students'] ?? 0,
            'sub' => 'مسجلون في كورسات',
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
