@php
    $currentTier = request('tier', 'all');
    $currentStatus = request('status', 'all');

    $tierFilters = [
        ['key' => 'all', 'label' => 'كل المستويات', 'params' => array_filter(['status' => request('status') !== 'all' ? request('status') : null])],
        ['key' => 'bronze', 'label' => 'برونزي', 'params' => array_filter(['tier' => 'bronze', 'status' => request('status') !== 'all' ? request('status') : null])],
        ['key' => 'silver', 'label' => 'فضي', 'params' => array_filter(['tier' => 'silver', 'status' => request('status') !== 'all' ? request('status') : null])],
        ['key' => 'gold', 'label' => 'ذهبي', 'params' => array_filter(['tier' => 'gold', 'status' => request('status') !== 'all' ? request('status') : null])],
        ['key' => 'platinum', 'label' => 'بلاتيني', 'params' => array_filter(['tier' => 'platinum', 'status' => request('status') !== 'all' ? request('status') : null])],
        ['key' => 'diamond', 'label' => 'ماسي', 'params' => array_filter(['tier' => 'diamond', 'status' => request('status') !== 'all' ? request('status') : null])],
    ];
@endphp

<div class="filter-panel mb-4">
    <div class="filter-panel__title">تصفية الإنجازات</div>
    <div class="filter-panel__subtitle">اختر مستوى الإنجاز أو حالته لعرض النتائج المناسبة</div>

    <div class="mb-3">
        <label class="form-label fs-12 text-muted mb-2">المستوى</label>
        <div class="d-flex flex-wrap gap-2">
            @foreach ($tierFilters as $filter)
                <a href="{{ route('gamification.achievements.index', $filter['params']) }}"
                   class="btn btn-sm {{ ($currentTier === $filter['key'] || ($filter['key'] === 'all' && !request('tier'))) ? 'btn-primary' : 'btn-light border' }} btn-wave">
                    {{ $filter['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    <div>
        <label class="form-label fs-12 text-muted mb-2">الحالة</label>
        <div class="d-flex flex-wrap gap-2" id="achievementStatusFilters">
            @foreach ([
                ['key' => 'all', 'label' => 'الكل'],
                ['key' => 'completed', 'label' => 'مكتمل'],
                ['key' => 'in_progress', 'label' => 'قيد التقدم'],
                ['key' => 'not_started', 'label' => 'لم يبدأ'],
            ] as $filter)
                <button type="button"
                    class="btn btn-sm {{ $currentStatus === $filter['key'] || ($filter['key'] === 'all' && !request('status')) ? 'btn-primary' : 'btn-light border' }} btn-wave"
                    data-status-filter="{{ $filter['key'] }}">
                    {{ $filter['label'] }}
                </button>
            @endforeach
        </div>
    </div>
</div>
