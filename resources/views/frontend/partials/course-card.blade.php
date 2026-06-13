@php
    $showCart = $showCart ?? true;
    $categoryColor = $course->category?->color ?? '#059669';
    $icon = $course->icon ? (str_starts_with($course->icon, 'fa') ? $course->icon : 'fa-' . $course->icon) : ($course->category?->icon ?? 'fa-book');
    if (! str_contains($icon, 'fa-')) {
        $icon = 'fas ' . $icon;
    } elseif (! str_contains($icon, 'fas ') && ! str_contains($icon, 'fab ') && ! str_contains($icon, 'far ')) {
        $icon = 'fas ' . $icon;
    }
@endphp
<article class="course-card" style="--course-color: {{ $categoryColor }};" data-course-id="{{ $course->id }}">
    <div class="course-card-thumb">
        @if($course->badge)
            <span class="course-card-badge">{{ $course->badge }}</span>
        @endif
        <div class="course-card-icon-wrap">
            <i class="{{ $icon }}"></i>
        </div>
        <div class="course-card-overlay">
            <a href="{{ route('courses.show', $course->slug) }}" class="course-card-preview-btn">
                <i class="fas fa-play-circle"></i> عرض التفاصيل
            </a>
        </div>
    </div>
    <div class="course-card-body">
        <div class="course-card-top">
            <span class="course-card-cat">{{ $course->category?->name }}</span>
            <span class="course-card-rating en-text"><i class="fas fa-star"></i> {{ number_format($course->rating_avg, 1) }}</span>
        </div>
        <h3 class="course-card-title">
            <a href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
        </h3>
        <div class="course-card-meta">
            <span><i class="fas fa-user-tie"></i> {{ $course->instructor?->name }}</span>
            <span><i class="fas fa-users"></i> <span class="en-text">{{ number_format($course->students_count) }}</span> طالب</span>
        </div>
        <div class="course-card-footer">
            <div class="course-card-prices en-text">
                <span class="course-card-price">{{ $course->formatted_price }}</span>
                @if($course->formatted_compare_price)
                    <span class="course-card-old-price">{{ $course->formatted_compare_price }}</span>
                @endif
            </div>
            @if($showCart)
                <button type="button" class="course-card-cart"
                    data-course-id="{{ $course->id }}"
                    data-course-title="{{ $course->title }}"
                    data-course-price="{{ $course->price }}"
                    data-course-slug="{{ $course->slug }}"
                    data-course-thumbnail="{{ $course->thumbnail_url }}"
                    aria-label="أضف للسلة">
                    <i class="fas fa-cart-plus"></i>
                    <span>أضف للسلة</span>
                </button>
            @endif
        </div>
    </div>
</article>
