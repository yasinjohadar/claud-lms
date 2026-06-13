@extends('frontend.layouts.master')

@section('title', $lesson->title . ' - ' . $course->title)

@section('content')
<div class="lesson-viewer-container">
    <div class="lesson-viewer-wrapper">
        <aside class="lesson-sidebar glass-panel">
            <div class="sidebar-header">
                <h5 class="fw-bold text-white mb-2">{{ $course->title }}</h5>
                <p class="text-secondary small mb-0">
                    {{ $sections->count() }} أقسام • {{ $totalLessons }} درس
                    @if($totalDurationHours) • {{ $totalDurationHours }} ساعة @endif
                </p>
            </div>

            <div class="syllabus-content">
                @foreach($sections as $section)
                    @php $sectionId = 'section-' . $section->id; @endphp
                    <div class="course-section">
                        <div class="section-header {{ $loop->first ? '' : 'collapsed' }}"
                             data-bs-toggle="collapse" data-bs-target="#{{ $sectionId }}"
                             aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                            <div class="section-info">
                                <h6 class="section-title mb-0">{{ $section->title }}</h6>
                                <span class="section-meta">{{ $section->lessons->count() }} دروس</span>
                            </div>
                            <i class="fas fa-chevron-down section-icon"></i>
                        </div>
                        <div class="collapse {{ $loop->first ? 'show' : '' }}" id="{{ $sectionId }}">
                            <ul class="lessons-list">
                                @foreach($section->lessons as $sectionLesson)
                                    @php
                                        $isActive = $sectionLesson->id === $lesson->id;
                                        $isCompleted = $completedLessonIds->contains($sectionLesson->id);
                                        $itemClass = $isActive ? 'active' : ($isCompleted ? 'completed' : '');
                                    @endphp
                                    <li class="lesson-item {{ $itemClass }}">
                                        <a href="{{ route('lessons.show', $sectionLesson->id) }}" class="lesson-link">
                                            <div class="lesson-status">
                                                @if($isCompleted)
                                                    <i class="fas fa-check-circle"></i>
                                                @elseif($isActive)
                                                    <i class="fas fa-play-circle"></i>
                                                @else
                                                    <i class="fas fa-circle"></i>
                                                @endif
                                            </div>
                                            <div class="lesson-info">
                                                <span class="lesson-title">{{ $sectionLesson->title }}</span>
                                                @if($sectionLesson->formatted_duration)
                                                    <div class="lesson-meta">
                                                        <i class="fas fa-play-circle"></i>
                                                        <span class="duration">{{ $sectionLesson->formatted_duration }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </aside>

        <main class="lesson-main">
            <div class="video-player-box glass-panel">
                <div class="video-wrapper">
                    @if($lesson->video_provider && $lesson->video_reference)
                        <div class="ratio ratio-16x9">
                            @if($lesson->video_provider === 'youtube')
                                <iframe src="https://www.youtube.com/embed/{{ $lesson->video_reference }}"
                                        title="{{ $lesson->title }}" allowfullscreen></iframe>
                            @elseif($lesson->video_provider === 'vimeo')
                                <iframe src="https://player.vimeo.com/video/{{ $lesson->video_reference }}"
                                        title="{{ $lesson->title }}" allowfullscreen></iframe>
                            @else
                                <div class="video-placeholder d-flex align-items-center justify-content-center h-100">
                                    <span class="text-white">{{ $lesson->provider_label }}: {{ $lesson->video_reference }}</span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="video-placeholder">
                            <i class="fas fa-play-circle"></i>
                            <span>لا يوجد فيديو لهذا الدرس</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="lesson-details glass-panel mt-3 p-4">
                <h2 class="text-white h4 mb-2">{{ $lesson->title }}</h2>
                <p class="text-secondary mb-3">
                    <a href="{{ route('courses.show', $course->slug) }}" class="text-info text-decoration-none">{{ $course->title }}</a>
                </p>
                @if($progressUrl)
                    <button type="button" class="btn btn-sm btn-success" id="mark-lesson-complete"
                            data-progress-url="{{ $progressUrl }}">
                        <i class="fas fa-check me-1"></i> وضع علامة مكتمل
                    </button>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection

@if($progressUrl ?? false)
@push('scripts')
<script>
document.getElementById('mark-lesson-complete')?.addEventListener('click', async function () {
    const url = this.dataset.progressUrl;
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify({ mark_completed: true, watched_seconds: 1 }),
        });
        const data = await res.json();
        if (data.success) {
            alert('تم حفظ التقدم: ' + (data.course_progress_percent ?? 0) + '%');
            location.reload();
        } else {
            alert(data.message || 'تعذّر حفظ التقدم');
        }
    } catch (e) {
        alert('حدث خطأ أثناء حفظ التقدم');
    }
});
</script>
@endpush
@endif
