@if($heroSlides->isNotEmpty())
<section class="hero-section position-relative">
    <div class="swiper hero-swiper"
         id="heroSwiper"
         data-autoplay="{{ $heroSettings->autoplay_enabled ? '1' : '0' }}"
         data-delay="{{ $heroSettings->autoplay_delay_ms }}"
         data-speed="{{ $heroSettings->transition_speed_ms }}"
         data-loop="{{ $heroSettings->loop ? '1' : '0' }}"
         data-pause-hover="{{ $heroSettings->pause_on_hover ? '1' : '0' }}"
         data-effect="{{ $heroSettings->effect }}"
         data-show-nav="{{ $heroSettings->show_navigation ? '1' : '0' }}"
         data-show-pagination="{{ $heroSettings->show_pagination ? '1' : '0' }}"
         data-show-progress="{{ $heroSettings->show_progress_bar ? '1' : '0' }}"
         data-labels="{{ $heroSlides->pluck('pagination_label')->filter()->values()->toJson() }}">
        <div class="swiper-wrapper">
            @foreach($heroSlides as $slide)
                @include('frontend.partials.hero-slide', ['slide' => $slide])
            @endforeach
        </div>

        @if($heroSettings->show_navigation || $heroSettings->show_pagination)
        <div class="hero-swiper-nav">
            @if($heroSettings->show_navigation)
            <button class="hero-nav-btn hero-prev" aria-label="الشريحة السابقة">
                <i class="fas fa-chevron-right"></i>
            </button>
            @endif
            @if($heroSettings->show_pagination)
            <div class="swiper-pagination hero-pagination"></div>
            @endif
            @if($heroSettings->show_navigation)
            <button class="hero-nav-btn hero-next" aria-label="الشريحة التالية">
                <i class="fas fa-chevron-left"></i>
            </button>
            @endif
        </div>
        @endif

        @if($heroSettings->show_progress_bar)
        <div class="hero-autoplay-progress"><span class="hero-progress-fill"></span></div>
        @endif
    </div>
</section>
<div class="home-hero-bridge" aria-hidden="true"></div>
@endif
