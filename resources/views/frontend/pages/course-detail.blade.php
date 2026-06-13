@extends('frontend.layouts.master')

@section('title', $course->meta_title ?: ($course->title . ' - إديوماتيك'))

@section('body_class', 'course-detail-page')

@php
    $categoryColor = $course->category?->color ?? '#059669';
    $icon = $course->icon ? (str_starts_with($course->icon, 'fa') ? $course->icon : 'fa-' . $course->icon) : ($course->category?->icon ?? 'fa-book');
    if (! str_contains($icon, 'fa-')) {
        $icon = 'fas ' . $icon;
    } elseif (! str_contains($icon, 'fas ') && ! str_contains($icon, 'fab ') && ! str_contains($icon, 'far ')) {
        $icon = 'fas ' . $icon;
    }
    $discount = ($course->compare_at_price && $course->compare_at_price > $course->price)
        ? round((1 - $course->price / $course->compare_at_price) * 100)
        : 0;
    $sections = $course->sections;
    $moduleCount = $sections->count();
    $globalResources = $course->globalResources;
@endphp

@section('content')
    <header class="course-detail-hero">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb course-detail-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses') }}">الكورسات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $course->title }}</li>
                </ol>
            </nav>
        </div>
    </header>

    <main class="container course-detail-main py-4">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="section-fade-up course-detail-intro">
                    <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
                        @if($course->category)
                            <span class="badge bg-glass text-accent px-3 py-2 rounded-pill">{{ $course->category->name }}</span>
                        @endif
                        @if($course->badge)
                            <span class="badge bg-danger px-3 py-2 rounded-pill">{{ $course->badge }}</span>
                        @endif
                        <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $course->level_label }}</span>
                    </div>
                    <h1 class="fw-bold mb-4 lh-base">{{ $course->title }}</h1>
                    <p class="text-secondary fs-5 mb-4 lh-lg">{{ $course->excerpt }}</p>

                    <div class="d-flex flex-wrap gap-4 align-items-center text-secondary small mb-5 course-detail-meta">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-tie text-accent fs-5 me-2 ms-1"></i>
                            <strong>{{ $course->instructor?->name }}</strong>
                        </div>
                        <div class="d-flex align-items-center en-text">
                            <span class="text-warning me-1 ms-1"><i class="fas fa-star"></i> {{ number_format($course->rating_avg, 1) }}</span>
                            <span>({{ number_format($course->rating_count) }} تقييم)</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users text-accent fs-5 me-2 mt-1 ms-1"></i>
                            <span class="en-text meta-value fw-bold me-1">{{ number_format($course->students_count) }}</span> طالب
                        </div>
                        @if($course->published_at)
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-alt text-accent fs-5 me-2 mt-1 ms-1"></i>
                            آخر تحديث: <span class="meta-value ms-1 fw-bold en-text">{{ $course->published_at->format('m/Y') }}</span>
                        </div>
                        @endif
                        <div class="d-flex align-items-center">
                            <i class="fas fa-globe text-accent fs-5 me-2 mt-1 ms-1"></i>
                            اللغة: <span class="meta-value ms-1 fw-bold">{{ $course->language === 'ar' ? 'العربية' : $course->language }}</span>
                        </div>
                    </div>

                    @if($course->tags->isNotEmpty())
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            @foreach($course->tags as $tag)
                                <a href="{{ route('courses', ['tags' => [$tag->slug]]) }}" class="badge bg-dark text-secondary text-decoration-none">{{ $tag->name }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if($globalResources->isNotEmpty())
                <div class="course-global-resources-banner section-fade-up mb-4">
                    <div class="course-global-resources-banner__icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div class="course-global-resources-banner__content">
                        <h3 class="course-global-resources-banner__title">موارد الكورس العامة</h3>
                        <p class="course-global-resources-banner__text mb-0">
                            <span class="en-text">{{ $globalResources->count() }}</span> مورد إضافي متاح في صفحات مستقلة — غير مرتبط بأقسام المنهاج.
                        </p>
                    </div>
                    <a href="{{ route('courses.resources', $course->slug) }}" class="btn btn-accent course-global-resources-banner__btn">
                        عرض الموارد <i class="fas fa-arrow-left ms-2"></i>
                    </a>
                </div>
                @endif

                <div class="course-detail-tabs-card section-fade-up">
                    <ul class="nav nav-tabs course-tabs border-0 flex-nowrap overflow-auto" id="courseTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active w-100 text-nowrap" id="content-tab" data-bs-toggle="tab" data-bs-target="#content" type="button" role="tab">محتوى الكورس</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link w-100 text-nowrap" id="requirements-tab" data-bs-toggle="tab" data-bs-target="#requirements" type="button" role="tab">المتطلبات</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link w-100 text-nowrap" id="instructor-tab" data-bs-toggle="tab" data-bs-target="#instructor" type="button" role="tab">المدرب</button>
                        </li>
                        @if($globalResources->isNotEmpty())
                        <li class="nav-item" role="presentation">
                            <a class="nav-link w-100 text-nowrap course-tab-external" href="{{ route('courses.resources', $course->slug) }}">
                                الموارد <span class="en-text">({{ $globalResources->count() }})</span>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item" role="presentation">
                            <button class="nav-link w-100 text-nowrap" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">التقييمات</button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="courseTabContent">
                    <div class="tab-pane fade show active section-fade-up" id="content" role="tabpanel">
                        @if($course->what_you_learn)
                            <h4 class="fw-bold text-white mb-3">ماذا ستتعلم</h4>
                            <ul class="list-unstyled text-secondary lh-lg mb-5">
                                @foreach($course->what_you_learn as $item)
                                    <li class="mb-2 d-flex align-items-start gap-3">
                                        <i class="fas fa-check-circle text-accent mt-1"></i>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if($course->description)
                            <div class="text-secondary lh-lg mb-5">{!! $course->description !!}</div>
                        @endif

                        @if($sections->isNotEmpty())
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="fw-bold m-0 text-white">منهج الدورة</h4>
                                <span class="text-secondary small">
                                    <span class="en-text">{{ $moduleCount }}</span> قسم &bull;
                                    <span class="en-text">{{ $course->lessons_count }}</span> درس &bull;
                                    <span class="en-text">{{ $course->duration_hours }}</span> ساعة
                                </span>
                            </div>

                            <div class="course-curriculum accordion" id="courseAccordion">
                                @foreach($sections as $index => $section)
                                    @php
                                        $collapseId = 'module-' . $section->id;
                                        $isFirst = $index === 0;
                                    @endphp
                                    <div class="accordion-item curriculum-module">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button {{ $isFirst ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="{{ $isFirst ? 'true' : 'false' }}">
                                                <span class="curriculum-module-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                                <span class="curriculum-module-title">{{ $section->title }}</span>
                                                <span class="curriculum-module-meta en-text">{{ $section->lessons->count() }} دروس</span>
                                            </button>
                                        </h2>
                                        <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}" data-bs-parent="#courseAccordion">
                                            <div class="accordion-body p-0">
                                                <ul class="curriculum-lessons">
                                                    @foreach($section->lessons as $lesson)
                                                        <li class="curriculum-lesson">
                                                            <span class="curriculum-lesson-icon is-video">
                                                                <i class="fas fa-play"></i>
                                                            </span>
                                                            <span class="curriculum-lesson-title">{{ $lesson->title }}</span>
                                                            <span class="curriculum-lesson-duration en-text">
                                                                @if($lesson->formatted_duration)
                                                                    {{ $lesson->formatted_duration }}
                                                                @else
                                                                    {{ $lesson->provider_label }}
                                                                @endif
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                    @foreach($section->resources as $resource)
                                                        <li class="curriculum-lesson curriculum-resource">
                                                            @if($resource->isLink())
                                                                <a href="{{ $resource->url }}" target="_blank" rel="noopener noreferrer" class="curriculum-resource-link d-flex align-items-center gap-3 flex-grow-1 text-decoration-none">
                                                                    <span class="curriculum-lesson-icon is-file">
                                                                        <i class="fas fa-link"></i>
                                                                    </span>
                                                                    <span class="curriculum-lesson-title">{{ $resource->title }}</span>
                                                                    <span class="curriculum-lesson-duration">{{ $resource->type_label }}</span>
                                                                </a>
                                                            @else
                                                                <a href="{{ route('courses.resource-file.download', [$course->slug, $resource->id]) }}" class="curriculum-resource-link d-flex align-items-center gap-3 flex-grow-1 text-decoration-none">
                                                                    <span class="curriculum-lesson-icon is-file">
                                                                        <i class="fas {{ $resource->file_icon }}"></i>
                                                                    </span>
                                                                    <span class="curriculum-lesson-title">{{ $resource->title }}</span>
                                                                    <span class="curriculum-lesson-duration en-text">
                                                                        {{ $resource->formatted_file_size ?? $resource->type_label }}
                                                                    </span>
                                                                </a>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="tab-pane fade" id="requirements" role="tabpanel">
                        <h4 class="fw-bold text-white mb-4">متطلبات الكورس</h4>
                        <ul class="list-unstyled text-secondary lh-lg mb-5">
                            @forelse($course->requirements ?? [] as $req)
                                <li class="mb-3 d-flex align-items-start gap-3">
                                    <i class="fas fa-check-circle text-accent mt-1"></i>
                                    <span>{{ $req }}</span>
                                </li>
                            @empty
                                <li class="text-secondary">لا توجد متطلبات محددة.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="tab-pane fade" id="instructor" role="tabpanel">
                        <div class="glass-panel p-4 p-md-5">
                            <div class="row align-items-center mb-4">
                                <div class="col-auto">
                                    <div class="rounded-circle overflow-hidden bg-gradient-opacity border border-3 border-accent d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                        <i class="fas fa-user-tie fs-1 text-white"></i>
                                    </div>
                                </div>
                                <div class="col mt-3 mt-md-0">
                                    <h3 class="fw-bold text-white mb-1">{{ $course->instructor?->name }}</h3>
                                    <p class="text-accent mb-3">مدرب معتمد</p>
                                </div>
                            </div>
                            <p class="text-secondary lh-lg mb-0">مدرب متخصص في مجال {{ $course->category?->name }} مع خبرة واسعة في التعليم الإلكتروني.</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <div class="text-center py-5 text-secondary">
                            <i class="fas fa-star fs-1 text-warning mb-3 d-block"></i>
                            <h4 class="text-white en-text">{{ number_format($course->rating_avg, 1) }}</h4>
                            <p>متوسط تقييم الكورس من {{ number_format($course->rating_count) }} تقييم</p>
                            <p class="small">نظام المراجعات التفاعلي سيُضاف في مرحلة لاحقة.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <aside class="course-purchase-card position-sticky section-fade-up">
                    <div class="course-purchase-preview">
                        <div class="course-purchase-preview-bg" style="--course-color: {{ $categoryColor }};">
                            <i class="{{ $icon }}"></i>
                        </div>
                        <button type="button" class="course-purchase-play" aria-label="معاينة الكورس">
                            <i class="fas fa-play"></i>
                        </button>
                        <span class="course-purchase-preview-label">معاينة مجانية</span>
                    </div>

                    <div class="course-purchase-body">
                        <div class="course-purchase-pricing">
                            <div class="course-purchase-prices">
                                <span class="course-purchase-price en-text">{{ $course->formatted_price }}</span>
                                @if($course->formatted_compare_price)
                                    <span class="course-purchase-old en-text">{{ $course->formatted_compare_price }}</span>
                                @endif
                            </div>
                            @if($discount > 0)
                                <span class="course-purchase-discount">خصم {{ $discount }}%</span>
                            @endif
                        </div>

                        <div class="course-purchase-actions">
                            <button type="button" class="course-purchase-btn-primary course-card-cart"
                                data-course-id="{{ $course->id }}"
                                data-course-title="{{ $course->title }}"
                                data-course-price="{{ $course->price }}"
                                data-course-slug="{{ $course->slug }}">
                                <i class="fas fa-cart-plus"></i> أضف إلى السلة
                            </button>
                            <a href="{{ route('checkout') }}" class="course-purchase-btn-secondary">اشترِ الآن</a>
                        </div>

                        @include('frontend.pages.course-detail.partials.resources-sidebar')

                        <p class="course-purchase-guarantee"><i class="fas fa-shield-alt"></i> ضمان استرداد الأموال خلال 30 يوماً</p>

                        <h3 class="course-purchase-includes-title">هذا الكورس يتضمن:</h3>
                        <ul class="course-purchase-includes">
                            <li><i class="fas fa-video"></i> <span class="en-text">{{ $course->duration_hours }}</span> ساعة من الفيديو حسب الطلب</li>
                            <li><i class="fas fa-list"></i> <span class="en-text">{{ $course->lessons_count }}</span> درس</li>
                            <li><i class="fas fa-infinity"></i> وصول مدى الحياة</li>
                            <li><i class="fas fa-mobile-alt"></i> الوصول من الجوال والكمبيوتر</li>
                            <li><i class="fas fa-certificate"></i> شهادة إتمام معتمدة</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>

        @if($relatedCourses->isNotEmpty())
        <section class="course-detail-related section-fade-up">
            <div class="course-detail-related-header">
                <div>
                    <span class="course-detail-related-eyebrow">اقتراحات لك</span>
                    <h3 class="course-detail-related-title">كورسات قد تعجبك أيضاً</h3>
                </div>
                <a href="{{ route('courses') }}" class="course-detail-related-link">عرض كل الكورسات <i class="fas fa-arrow-left"></i></a>
            </div>
            <div class="row g-4" id="related-courses-container">
                @foreach($relatedCourses as $related)
                    @include('frontend.partials.course-card-grid', ['course' => $related])
                @endforeach
            </div>
        </section>
        @endif
    </main>
@endsection

@push('scripts')
<script>
document.querySelectorAll('#related-courses-container .course-card-cart, .course-purchase-btn-primary.course-card-cart').forEach(btn => {
    btn.addEventListener('click', () => { if (typeof addToCart === 'function') addToCart(btn); });
});
</script>
@endpush
