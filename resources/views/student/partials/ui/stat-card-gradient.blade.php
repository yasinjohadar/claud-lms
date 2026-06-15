<div class="{{ $col ?? 'col-sm-6 col-xl-3' }}">
    <div class="stat-card-gradient stat-card-gradient--{{ $variant ?? 'purple' }}">
        <div class="stat-card-gradient__icon"><i class="{{ $icon }}"></i></div>
        <div class="stat-card-gradient__body">
            <div class="stat-card-gradient__label">{{ $label }}</div>
            <div class="stat-card-gradient__value"@if(!empty($valueId)) id="{{ $valueId }}"@endif>{{ $value }}</div>
            @if(!empty($hint))
                <div class="stat-card-gradient__hint">{{ $hint }}</div>
            @endif
        </div>
    </div>
</div>
