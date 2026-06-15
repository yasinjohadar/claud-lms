@php
    $currentRarity = request('rarity', 'all');
    $filters = [
        ['key' => 'all', 'label' => 'الكل', 'params' => array_filter(['type' => request('type')])],
        ['key' => 'common', 'label' => 'عادية', 'params' => array_filter(['rarity' => 'common', 'type' => request('type')])],
        ['key' => 'rare', 'label' => 'نادرة', 'params' => array_filter(['rarity' => 'rare', 'type' => request('type')])],
        ['key' => 'epic', 'label' => 'ملحمية', 'params' => array_filter(['rarity' => 'epic', 'type' => request('type')])],
        ['key' => 'legendary', 'label' => 'أسطورية', 'params' => array_filter(['rarity' => 'legendary', 'type' => request('type')])],
    ];
@endphp

<div class="filter-panel mb-4">
    <div class="filter-panel__title">تصفية الشارات</div>
    <div class="filter-panel__subtitle">اختر مستوى الندرة لعرض الشارات المناسبة</div>
    <div class="d-flex flex-wrap gap-2">
        @foreach ($filters as $filter)
            <a href="{{ route('gamification.badges.index', $filter['params']) }}"
               class="btn btn-sm {{ $currentRarity === $filter['key'] || ($filter['key'] === 'all' && !request('rarity')) ? 'btn-primary' : 'btn-light border' }} btn-wave">
                {{ $filter['label'] }}
            </a>
        @endforeach
    </div>
</div>
