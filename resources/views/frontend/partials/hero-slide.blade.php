@php
    $presenter = \App\Support\HeroSlidePresenter::make($slide);
@endphp
<div class="swiper-slide {{ $presenter->slideClasses() }}"
     style="{{ $presenter->slideStyle() }}"
     @if($slide->aria_label) aria-label="{{ $slide->aria_label }}" @endif>
    @if($presenter->showThemeBackground())
        <div class="hero-slide-bg"></div>
    @else
        <div class="hero-slide-bg hero-slide-bg--custom" style="{{ $presenter->backgroundStyle() }}"></div>
        @if($overlay = $presenter->imageOverlayStyle())
            <div class="hero-slide-overlay" style="{{ $overlay }}"></div>
        @endif
    @endif

    @if($presenter->showDecorativeShapes())
    <div class="hero-bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    @endif

    <div class="container hero-slide-inner">
        <div class="{{ $presenter->rowClass() }}">
            @if($presenter->showContent())
            <div class="{{ $presenter->contentColumnClass() }}">
                @if($slide->badge_text)
                    <span class="hero-badge">
                        @if($slide->badge_icon)<i class="{{ $slide->badge_icon }} me-2"></i>@endif
                        {{ $slide->badge_text }}
                    </span>
                @endif

                <h1 class="hero-title display-3 fw-bolder mb-4 lh-base">
                    @if($slide->heading_mode === 'typing')
                        @if($slide->heading_prefix){{ $slide->heading_prefix }} <br>@endif
                        <span class="hero-accent-text typing-text typing-container" data-text='{{ $presenter->typingPhrasesJson() }}'></span><span class="typing-cursor">|</span>
                    @else
                        @if($slide->heading_prefix){{ $slide->heading_prefix }} @endif
                        @if($slide->heading_highlight)<span class="hero-accent-text">{{ $slide->heading_highlight }}</span>@endif
                    @endif
                </h1>

                @if($slide->description)
                    <p class="hero-desc lead mb-4 pe-lg-5">{{ $slide->description }}</p>
                @endif

                @if($slide->buttons)
                <div class="hero-actions d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                    @foreach($slide->buttons as $button)
                        @php
                            $btnClass = ($button['style'] ?? 'primary') === 'glass' ? 'btn-hero-glass' : 'btn-hero-primary';
                            $url = $button['url'] ?? '#';
                            if (!str_starts_with($url, 'http') && !str_starts_with($url, '/')) {
                                $url = '/' . ltrim($url, '/');
                            }
                        @endphp
                        <a href="{{ $url }}" class="btn {{ $btnClass }}"
                           @if($button['open_in_new_tab'] ?? false) target="_blank" rel="noopener" @endif>
                            <span>{{ $button['label'] }}</span>
                            @if(!empty($button['icon'])) <i class="{{ $button['icon'] }}"></i> @endif
                        </a>
                    @endforeach
                </div>
                @endif
            </div>
            @endif

            @if($presenter->showVisual())
            <div class="{{ $presenter->visualColumnClass() }}">
                @include('frontend.partials.hero-visuals.' . $slide->visual_type, ['slide' => $slide])
            </div>
            @endif
        </div>
    </div>
</div>
