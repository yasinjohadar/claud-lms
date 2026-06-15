@extends('student.layouts.master')

@section('page-title')
    {{ $course->title }}
@stop

@section('styles')
    @include('student.pages.courses.partials.page-styles')
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'كورساتي', 'url' => route('student.courses.index')],
                ['label' => Str::limit($course->title, 40)],
            ],
            'title' => $course->title,
            'subtitle' => $course->excerpt ?: 'تفاصيل الكورس والمنهج وتقدّمك',
            'actions' => $nextLesson
                ? '<a href="' . route('student.lessons.show', $nextLesson->id) . '" class="btn btn-primary btn-wave rounded-pill"><i class="ri-play-circle-line me-1"></i>' . ($stats['progress'] > 0 ? 'متابعة التعلم' : 'ابدأ الآن') . '</a>'
                : null,
        ])

        <div class="card custom-card mb-3">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <div class="student-course-thumb">
                            @if($course->thumbnail_url)
                                <img src="{{ $course->thumbnail_url }}" alt="{{ $course->thumbnail_alt ?? $course->title }}">
                            @else
                                <i class="ri-graduation-cap-line"></i>
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            @if($course->category)
                                <span class="badge badge-soft-primary">{{ $course->category->name }}</span>
                            @endif
                            <span class="badge badge-soft-secondary">{{ $course->level_label }}</span>
                            <span class="badge badge-soft-success">{{ $enrollment->status_label }}</span>
                        </div>
                        <div class="d-flex flex-wrap gap-3 text-muted fs-13 mb-3">
                            @if($course->instructor)
                                <span><i class="ri-user-star-line me-1"></i>{{ $course->instructor->name }}</span>
                            @endif
                            <span><i class="ri-book-open-line me-1"></i>{{ $stats['total_lessons'] }} درس</span>
                            @if($stats['duration_hours'])
                                <span><i class="ri-time-line me-1"></i>{{ $stats['duration_hours'] }} ساعة</span>
                            @endif
                            <span><i class="ri-star-line me-1"></i>{{ number_format($course->rating_avg, 1) }}</span>
                        </div>
                        <div style="max-width: 320px;">
                            <div class="d-flex justify-content-between fs-12 mb-1">
                                <span class="text-muted">تقدمك</span>
                                <span class="fw-bold">{{ $stats['progress'] }}%</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 6px;">
                                <div class="progress-bar bg-success rounded-pill" style="width: {{ max($stats['progress'], $stats['progress'] > 0 ? 4 : 0) }}%"></div>
                            </div>
                            <small class="text-muted">{{ $stats['completed_lessons'] }} / {{ $stats['total_lessons'] }} درس مكتمل</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-8 order-lg-1">
                @include('student.pages.courses.partials.stats-strip')

                <div class="card custom-card data-table-card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold fs-16">محتوى الكورس</span>
                            <span class="table-count-badge">{{ $stats['total_lessons'] }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @include('student.pages.courses.partials.curriculum')
                    </div>
                </div>

                @if($course->what_you_learn && count($course->what_you_learn))
                    <div class="card custom-card mb-3">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-0"><i class="ri-lightbulb-line text-warning me-2"></i>ماذا ستتعلم</h5>
                        </div>
                        <div class="card-body pt-3">
                            <ul class="list-unstyled mb-0 row g-2">
                                @foreach($course->what_you_learn as $item)
                                    <li class="col-md-6 d-flex align-items-start gap-2 fs-13">
                                        <i class="ri-check-line text-success mt-1"></i>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if($course->description)
                    <div class="card custom-card mb-3">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-0"><i class="ri-file-text-line me-2"></i>عن الكورس</h5>
                        </div>
                        <div class="card-body pt-3 text-muted lh-lg">
                            {!! $course->description !!}
                        </div>
                    </div>
                @endif

                @if($course->requirements && count($course->requirements))
                    <div class="card custom-card mb-3">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-0"><i class="ri-checkbox-circle-line me-2"></i>المتطلبات</h5>
                        </div>
                        <div class="card-body pt-3">
                            <ul class="list-unstyled mb-0">
                                @foreach($course->requirements as $req)
                                    <li class="d-flex align-items-start gap-2 mb-2 fs-13 text-muted">
                                        <i class="ri-arrow-left-s-line text-primary mt-1"></i>{{ $req }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4 order-lg-2">
                <div class="card custom-card mb-3">
                    <div class="card-header border-0 pb-0">
                        <h5 class="card-title mb-0">معلومات التسجيل</h5>
                    </div>
                    <div class="card-body pt-3 fs-13">
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">تاريخ التسجيل</span>
                                <span class="fw-semibold">{{ $enrollment->enrolled_at?->format('Y-m-d') ?? '—' }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">مصدر التسجيل</span>
                                <span class="fw-semibold">{{ $enrollment->source_label }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">الحالة</span>
                                <span class="badge badge-soft-success">{{ $enrollment->status_label }}</span>
                            </li>
                            @if($enrollment->completed_at)
                                <li class="d-flex justify-content-between py-2">
                                    <span class="text-muted">تاريخ الإكمال</span>
                                    <span class="fw-semibold">{{ $enrollment->completed_at->format('Y-m-d') }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                @if($course->globalResources->isNotEmpty())
                    <div class="card custom-card data-table-card mb-3">
                        <div class="card-header d-flex align-items-center gap-2">
                            <span class="fw-bold fs-16">موارد عامة</span>
                            <span class="table-count-badge">{{ $course->globalResources->count() }}</span>
                        </div>
                        <div class="card-body p-0">
                            @foreach($course->globalResources as $resource)
                                @php
                                    $resourceUrl = $resource->isLink()
                                        ? $resource->url
                                        : route('courses.resource-file.download', [$course->slug, $resource->id]);
                                @endphp
                                <a href="{{ $resourceUrl }}" class="student-curriculum-item"
                                   @if($resource->isLink()) target="_blank" rel="noopener" @endif>
                                    <span class="student-curriculum-item__icon student-curriculum-item__icon--file">
                                        <i class="ri-file-line"></i>
                                    </span>
                                    <span class="student-curriculum-item__title">{{ $resource->title }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($course->tags->isNotEmpty())
                    <div class="card custom-card">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-0">الوسوم</h5>
                        </div>
                        <div class="card-body pt-3">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($course->tags as $tag)
                                    <span class="badge badge-soft-secondary">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@stop
