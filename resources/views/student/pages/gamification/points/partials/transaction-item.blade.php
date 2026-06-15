@php
    $isEarn = ($transaction->points ?? 0) >= 0;
    $source = $transaction->source ?? null;
    $icon = app(\App\Services\Gamification\PointEarningCatalog::class)->getSourceIcon($source ?? 'bonus');
    $label = $transaction->description ?? ($source
        ? app(\App\Services\Gamification\PointEarningCatalog::class)->getSourceLabel($source)
        : 'معاملة نقاط');
@endphp

<div class="gamification-points-tx-item {{ $isEarn ? 'is-earn' : 'is-spend' }}">
    <span class="gamification-points-tx-item__icon" aria-hidden="true">
        <i class="{{ $icon }}"></i>
    </span>
    <div class="gamification-points-tx-item__body">
        <strong class="gamification-points-tx-item__title">{{ $label }}</strong>
        <span class="gamification-points-tx-item__date">{{ $transaction->created_at->diffForHumans() }}</span>
    </div>
    <span class="gamification-points-tx-item__amount">
        {{ $isEarn ? '+' : '' }}{{ number_format($transaction->points) }}
        <small>نقطة</small>
    </span>
</div>
