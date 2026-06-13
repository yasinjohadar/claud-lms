@extends('frontend.layouts.master')

@section('title', 'موارد الكورس — ' . $course->title)

@section('body_class', 'course-resources-page')

@section('content')
    @include('frontend.pages.course-resources.partials.hero', ['course' => $course, 'resources' => $resources])

    <main class="container course-resources-main py-4 py-md-5">
        <div class="row g-4 g-lg-5">
            <div class="col-lg-8">
                @if($resources->isEmpty())
                    <div class="course-resources-empty glass-panel p-5 text-center section-fade-up">
                        <i class="fas fa-folder-open fs-1 text-accent mb-3 d-block"></i>
                        <h2 class="fw-bold mb-2">لا توجد موارد عامة حالياً</h2>
                        <p class="text-secondary mb-4">لم يُضف أي مورد عام لهذا الكورس بعد. الموارد العامة تُعرض هنا بشكل مستقل عن منهج الدروس.</p>
                        <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-accent">العودة لصفحة الكورس</a>
                    </div>
                @else
                    <div class="course-resources-intro section-fade-up mb-4">
                        <h2 class="course-resources-section-title">جميع الموارد العامة</h2>
                        <p class="text-secondary mb-0">اختر مورداً لفتح صفحته المستقلة مع الوصف الكامل وخيارات التحميل أو فتح الرابط.</p>
                    </div>

                    <div class="row g-4">
                        @foreach($resources as $resource)
                            <div class="col-md-6 section-fade-up">
                                <article class="course-resource-card h-100">
                                    <div class="course-resource-card__icon">
                                        <i class="fas {{ $resource->file_icon }}"></i>
                                    </div>
                                    <h3 class="course-resource-card__title">
                                        <a href="{{ route('courses.resources.show', [$course->slug, $resource->slug]) }}" class="stretched-link text-decoration-none">
                                            {{ $resource->title }}
                                        </a>
                                    </h3>
                                    <p class="course-resource-card__meta text-secondary mb-0">
                                        {{ $resource->type_label }}
                                        @if($resource->isFile() && $resource->formatted_file_size)
                                            &bull; {{ $resource->formatted_file_size }}
                                        @endif
                                    </p>
                                    @if($resource->description)
                                        <p class="course-resource-card__excerpt text-secondary mt-2 mb-3">{{ Str::limit(strip_tags($resource->description), 120) }}</p>
                                    @endif
                                    <div class="course-resource-card__footer">
                                        <a href="{{ route('courses.resources.show', [$course->slug, $resource->slug]) }}" class="course-resource-card__cta">
                                            عرض الصفحة <i class="fas fa-arrow-left ms-1"></i>
                                        </a>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="course-resource-sidebar-sticky position-sticky">
                    @include('frontend.pages.course-resources.partials.sidebar', ['course' => $course, 'resources' => $resources])
                </div>
            </div>
        </div>
    </main>
@endsection
