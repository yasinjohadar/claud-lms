@php
    $totalQuestions = $stats['total_questions'] ?? $pool->questions->count();
    $activeQuestions = $pool->questions->where('is_active', true)->count();
    $totalPoints = (int) ($stats['total_points'] ?? $pool->questions->sum('default_grade'));
    $quizzesUsed = $pool->quizzes?->count() ?? 0;

    $kpiCards = [
        [
            'variant' => 'blue',
            'icon' => 'fe-help-circle',
            'label' => 'إجمالي الأسئلة',
            'value' => $totalQuestions,
            'sub' => $activeQuestions . ' نشط',
        ],
        [
            'variant' => 'green',
            'icon' => 'fe-check-circle',
            'label' => 'الأسئلة النشطة',
            'value' => $activeQuestions,
            'sub' => $totalQuestions > 0 ? number_format(($activeQuestions / $totalQuestions) * 100, 0) . '% من المجموعة' : '—',
            'noCountup' => $totalQuestions === 0,
        ],
        [
            'variant' => 'orange',
            'icon' => 'fe-star',
            'label' => 'إجمالي الدرجات',
            'value' => $totalPoints,
            'sub' => 'متوسط ' . number_format($stats['average_points'] ?? ($pool->questions->avg('default_grade') ?? 0), 1),
        ],
        [
            'variant' => 'cyan',
            'icon' => 'fe-clipboard',
            'label' => 'الاختبارات المستخدمة',
            'value' => $quizzesUsed,
            'sub' => $quizzesUsed > 0 ? 'مرتبطة بهذه المجموعة' : 'لم تُستخدم بعد',
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
                            @empty($card['noCountup']) data-countup="{{ $card['value'] }}" @endempty>
                            @if(!empty($card['noCountup']))
                                {{ $card['value'] }}
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
