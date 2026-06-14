<div class="card-header border-0 {{ !empty($headerActions) ? 'd-flex justify-content-between align-items-center gap-2 flex-wrap' : '' }}">
    <h4 class="card-title mb-0 d-flex align-items-center gap-2 flex-grow-1">
        <span class="qb-type-card__icon qb-type-card__icon--{{ $color ?? 'primary' }}">
            <i class="{{ $icon ?? 'ri-file-list-line' }}"></i>
        </span>
        <span>
            {{ $title }}
            @if(!empty($subtitle))
                <small class="text-muted d-block fs-12 fw-normal mt-1">{{ $subtitle }}</small>
            @endif
        </span>
    </h4>
    @if(!empty($headerActions))
        <div class="flex-shrink-0">{!! $headerActions !!}</div>
    @endif
</div>
