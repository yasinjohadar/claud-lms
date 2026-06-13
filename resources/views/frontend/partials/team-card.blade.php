@props(['member', 'showCourses' => false, 'slide' => false])

@php
    $stars = $member->star_state;
@endphp

@if($slide)
    <div class="swiper-slide">
@endif

<article class="team-card {{ $showCourses ? 'h-100' : '' }}" style="--team-color: {{ $member->accent_color }};">
    <div class="team-card-avatar">
        @if($member->avatar_url)
            <img src="{{ $member->avatar_url }}" alt="{{ $member->display_name }}" class="team-card-avatar-img">
        @else
            <span class="team-card-avatar-inner"><i class="{{ $member->avatar_icon ?: 'fas fa-user' }}"></i></span>
        @endif
    </div>
    <h3 class="team-card-name">{{ $member->display_name }}</h3>
    <p class="team-card-role en-text">{{ $member->role_title }}</p>

    @if($member->rating)
        <div class="team-card-rating en-text" aria-label="تقييم {{ $member->rating }} من 5">
            <span class="team-rating-stars">
                @for($i = 0; $i < $stars['full']; $i++)
                    <i class="fas fa-star"></i>
                @endfor
                @if($stars['hasHalf'])
                    <i class="fas fa-star-half-alt"></i>
                @endif
                @for($i = 0; $i < $stars['empty']; $i++)
                    <i class="far fa-star"></i>
                @endfor
            </span>
            <span class="team-rating-value">{{ number_format($member->rating, 1) }}</span>
        </div>
    @endif

    @if($showCourses && $member->display_courses_count)
        <p class="team-card-courses en-text">
            <i class="fas fa-play-circle"></i>
            {{ $member->display_courses_count }} {{ $member->display_courses_count === 1 ? 'كورس' : 'كورسات' }}
        </p>
    @endif

    @if($member->bio)
        <p class="team-card-bio">{{ $member->bio }}</p>
    @endif

    @if(count($member->resolved_social_links))
        <div class="team-card-social">
            @foreach($member->resolved_social_links as $social)
                <a href="{{ $social['url'] }}" class="team-social-btn" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['label'] }}">
                    <i class="{{ $social['icon'] }}"></i>
                </a>
            @endforeach
        </div>
    @endif
</article>

@if($slide)
    </div>
@endif
