@php
    $publishedCount = $questionModules->where('is_published', true)->count();
    $visibleCount = $questionModules->where('is_visible', true)->count();
    $totalQuestions = $questionModules->sum(fn ($m) => $m->questions->count());

    $kpiCards = [
        ['variant' => 'blue', 'icon' => 'fe-grid', 'label' => 'إجمالي الوحدات', 'value' => $questionModules->total(), 'sub' => 'وحدات أسئلة'],
        ['variant' => 'green', 'icon' => 'fe-check-circle', 'label' => 'الوحدات المنشورة', 'value' => $publishedCount, 'sub' => 'في الصفحة الحالية'],
        ['variant' => 'cyan', 'icon' => 'fe-eye', 'label' => 'الوحدات المرئية', 'value' => $visibleCount, 'sub' => 'ظاهرة للطلاب'],
        ['variant' => 'orange', 'icon' => 'fe-help-circle', 'label' => 'إجمالي الأسئلة', 'value' => $totalQuestions, 'sub' => 'في الصفحة الحالية'],
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
