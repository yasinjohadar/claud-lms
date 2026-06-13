@extends('frontend.layouts.master')

@section('title', 'الكورسات - إديوماتيك')

@section('body_class', 'courses-page')

@section('content')
<!-- Courses Page Hero -->
    <section class="courses-page-hero section-fade-up">
        <div class="container courses-page-hero-inner">
            <span class="courses-page-hero-eyebrow"><i class="fas fa-graduation-cap"></i> مكتبة التعلم</span>
            <h1 class="courses-page-hero-title">تصفح جميع الكورسات</h1>
            <p class="courses-page-hero-desc">اكتشف مئات الكورسات في مجالات التقنية والتصميم والأعمال من أفضل المدربين العرب.</p>

            <div class="courses-page-hero-stats">
                <div class="courses-hero-stat">
                    <strong class="en-text">{{ $stats['total'] }}+</strong>
                    <span>كورس متاح</span>
                </div>
                <div class="courses-hero-stat">
                    <strong class="en-text">{{ $stats['categories'] }}</strong>
                    <span>مجالات</span>
                </div>
                <div class="courses-hero-stat">
                    <strong class="en-text">{{ $stats['avg_rating'] }}</strong>
                    <span>متوسط التقييم</span>
                </div>
            </div>

            <nav class="courses-page-breadcrumb" aria-label="breadcrumb">
                <a href="{{ route('home') }}">الرئيسية</a>
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
                <span>الكورسات</span>
            </nav>
        </div>
    </section>

    <div class="courses-page-body">
    <main class="container courses-page-main">
        <button class="courses-mobile-filter-btn d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#coursesFilters" aria-expanded="false" aria-controls="coursesFilters">
            <i class="fas fa-sliders-h"></i>
            <span>تصفية وترتيب</span>
            <i class="fas fa-chevron-down courses-mobile-filter-chevron"></i>
        </button>

        <div class="row g-4 courses-page-layout">
            <aside class="col-lg-3">
                <div class="collapse d-lg-block" id="coursesFilters">
                <div class="courses-filter-panel sticky-top section-fade-up">
                    <div class="courses-filter-header">
                        <div class="courses-filter-header-text">
                            <span class="courses-filter-icon"><i class="fas fa-filter"></i></span>
                            <h2 class="courses-filter-title">تصفية النتائج</h2>
                        </div>
                        <button type="button" class="courses-filter-reset" id="reset-filters">
                            <i class="fas fa-undo-alt"></i> إعادة ضبط
                        </button>
                    </div>

                    @include('frontend.partials.courses-filters', [
                        'categories' => $categories,
                        'tags' => $tags,
                        'maxPrice' => $maxPrice,
                    ])
                </div>
                </div>
            </aside>

            <div class="col-lg-9">
                <div class="courses-toolbar section-fade-up">
                    <div class="courses-toolbar-results">
                        <span class="courses-toolbar-label">النتائج</span>
                        <span class="courses-toolbar-count">عرض <strong id="courses-count" class="en-text">{{ $courses->total() }}</strong> كورس</span>
                    </div>
                    <div class="courses-toolbar-actions">
                        <div class="courses-sort-wrap">
                            <label class="visually-hidden" for="sort-select">ترتيب حسب</label>
                            <i class="fas fa-sort-amount-down courses-sort-icon" aria-hidden="true"></i>
                            <select id="sort-select">
                                <option value="popular" {{ ($filters['sort'] ?? 'popular') === 'popular' ? 'selected' : '' }}>الأكثر شعبية</option>
                                <option value="newest" {{ ($filters['sort'] ?? '') === 'newest' ? 'selected' : '' }}>الأحدث</option>
                                <option value="price-asc" {{ ($filters['sort'] ?? '') === 'price-asc' ? 'selected' : '' }}>السعر: الأقل أولاً</option>
                                <option value="price-desc" {{ ($filters['sort'] ?? '') === 'price-desc' ? 'selected' : '' }}>السعر: الأعلى أولاً</option>
                                <option value="rating" {{ ($filters['sort'] ?? '') === 'rating' ? 'selected' : '' }}>الأعلى تقييماً</option>
                            </select>
                        </div>
                        <div class="courses-view-toggle" role="group" aria-label="طريقة العرض">
                            <button type="button" class="courses-view-btn active toggle-view" data-view="grid" aria-label="عرض شبكي"><i class="fas fa-th-large"></i></button>
                            <button type="button" class="courses-view-btn toggle-view" data-view="list" aria-label="عرض قائمة"><i class="fas fa-list"></i></button>
                        </div>
                    </div>
                </div>

                <div class="row g-4 section-fade-up" id="all-courses-container">
                    @forelse($courses as $course)
                        @include('frontend.partials.course-card-grid', ['course' => $course])
                    @empty
                        <div class="col-12">
                            <div class="courses-empty">
                                <div class="courses-empty-icon"><i class="fas fa-search"></i></div>
                                <h5>لم يتم العثور على كورسات</h5>
                                <p class="text-secondary">حاول تغيير معايير البحث أو التصفية</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div id="courses-pagination-wrap">
                    @include('frontend.partials.courses-pagination', ['courses' => $courses])
                </div>
            </div>
        </div>
    </main>
    </div>
@endsection

@push('scripts')
<script>window.COURSES_SEARCH_URL = @json(route('courses.search'));</script>
<script src="{{ $fa }}/js/courses.js"></script>
@endpush
