@extends('student.layouts.lesson')

@section('page-title')
    {{ $lesson->title }}
@endsection

@section('styles')
    @include('student.pages.lessons.partials.page-styles')
@endsection

@section('content')
<div class="student-lesson-viewer">
    <header class="student-lesson-topbar">
        <a href="{{ route('student.courses.show', $course->slug) }}" class="student-lesson-topbar__back">
            <i class="ri-arrow-right-line"></i>
            العودة للكورس
        </a>
        <h1 class="student-lesson-topbar__title">{{ $course->title }}</h1>
        <span class="student-lesson-topbar__progress">
            {{ $completedLessons }}/{{ $totalLessons }} درس · {{ $enrollment->progress_percent ?? 0 }}%
        </span>
    </header>

    <div class="student-lesson-body">
        <main class="student-lesson-main">
            <div class="student-lesson-player">
                <div class="student-lesson-player__inner">
                    @if($lesson->isDirectVideo() && $lesson->video_reference)
                        <video src="{{ $lesson->video_reference }}" controls controlsList="nodownload" playsinline>
                            متصفحك لا يدعم تشغيل الفيديو.
                        </video>
                    @elseif($lesson->embed_url)
                        <iframe src="{{ $lesson->embed_url }}"
                                title="{{ $lesson->title }}"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen"
                                allowfullscreen></iframe>
                    @else
                        <div class="student-lesson-player__placeholder">
                            <i class="ri-video-off-line"></i>
                            <span>لا يوجد فيديو لهذا الدرس</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="student-lesson-meta">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <h2 class="h5 fw-bold mb-1">{{ $lesson->title }}</h2>
                        <p class="text-muted fs-13 mb-0">
                            {{ $lesson->section?->title }}
                            @if($lesson->formatted_duration)
                                · {{ $lesson->formatted_duration }}
                            @endif
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @if(($currentProgress?->status ?? '') !== 'completed')
                            <button type="button" class="btn btn-success btn-sm rounded-pill" id="mark-lesson-complete"
                                    data-progress-url="{{ $progressUrl }}">
                                <i class="ri-check-line me-1"></i>وضع علامة مكتمل
                            </button>
                        @else
                            <span class="badge bg-success-transparent text-success rounded-pill px-3 py-2">
                                <i class="ri-check-double-line me-1"></i>مكتمل
                            </span>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center gap-2 pt-2 border-top">
                    @if($prevLesson)
                        <a href="{{ route('student.lessons.show', $prevLesson->id) }}" class="btn btn-light border btn-sm rounded-pill">
                            <i class="ri-arrow-right-line me-1"></i>الدرس السابق
                        </a>
                    @else
                        <span></span>
                    @endif
                    @if($nextLesson)
                        <a href="{{ route('student.lessons.show', $nextLesson->id) }}" class="btn btn-primary btn-sm rounded-pill">
                            الدرس التالي
                            <i class="ri-arrow-left-line ms-1"></i>
                        </a>
                    @elseif(($currentProgress?->status ?? '') !== 'completed')
                        <button type="button" class="btn btn-primary btn-sm rounded-pill" id="mark-complete-and-finish"
                                data-progress-url="{{ $progressUrl }}">
                            إنهاء الكورس
                            <i class="ri-check-line ms-1"></i>
                        </button>
                    @endif
                </div>
            </div>
        </main>

        <aside class="student-lesson-sidebar" id="lessonSidebar">
            <div class="student-lesson-sidebar__header">
                <h3 class="fs-14 fw-bold mb-1">محتوى الكورس</h3>
                <p class="text-muted fs-12 mb-0">{{ $sections->count() }} أقسام · {{ $totalLessons }} درس</p>
            </div>
            <div class="student-lesson-sidebar__content">
                @include('student.pages.lessons.partials.sidebar')
            </div>
        </aside>
    </div>

    <button type="button" class="btn btn-primary student-lesson-sidebar-toggle" id="toggleLessonSidebar">
        <i class="ri-list-check me-1"></i>المحتوى
    </button>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var toggleBtn = document.getElementById('toggleLessonSidebar');
    var sidebar = document.getElementById('lessonSidebar');

    toggleBtn?.addEventListener('click', function () {
        sidebar?.classList.toggle('is-open');
    });

    function saveProgress(url, markCompleted) {
        var token = document.querySelector('meta[name="csrf-token"]')?.content;
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify({ mark_completed: markCompleted, watched_seconds: 1 }),
        }).then(function (res) { return res.json(); });
    }

    function bindCompleteButton(id, reload) {
        var btn = document.getElementById(id);
        if (!btn) return;
        btn.addEventListener('click', function () {
            btn.disabled = true;
            saveProgress(btn.dataset.progressUrl, true)
                .then(function (data) {
                    if (data.success) {
                        if (reload) location.reload();
                        else window.location.href = @json($nextLesson ? route('student.lessons.show', $nextLesson->id) : route('student.courses.show', $course->slug));
                    } else {
                        alert(data.message || 'تعذّر حفظ التقدم');
                        btn.disabled = false;
                    }
                })
                .catch(function () {
                    alert('حدث خطأ أثناء حفظ التقدم');
                    btn.disabled = false;
                });
        });
    }

    bindCompleteButton('mark-lesson-complete', true);
    bindCompleteButton('mark-complete-and-finish', false);
});
</script>
@endsection
