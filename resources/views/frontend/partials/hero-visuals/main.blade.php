<div class="hero-visual-card">
    <div class="hero-visual-main">
        <i class="{{ $slide->visual_icon ?: 'fas fa-laptop-code' }}"></i>
    </div>
    @php $cards = $slide->visual_extras['float_cards'] ?? []; @endphp
    @foreach($cards as $i => $card)
        <div class="hero-float-card {{ $i === 0 ? 'hero-float-top' : 'hero-float-bottom' }}">
            @if(!empty($card['icon']))<i class="{{ $card['icon'] }}"></i>@endif
            <div>
                @if(!empty($card['value']))
                    <strong class="en-text {{ !empty($card['counter']) ? 'counter' : '' }}" @if(!empty($card['counter'])) data-target="{{ preg_replace('/[^0-9]/', '', $card['value']) }}" @endif>{{ $card['value'] }}</strong>
                @endif
                @if(!empty($card['title']))<small>{{ $card['title'] }}</small>@endif
            </div>
        </div>
    @endforeach
</div>
