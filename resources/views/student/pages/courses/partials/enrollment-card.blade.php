@php
    $course = $enrollment->course;
    $progress = (int) ($enrollment->progress_percent ?? 0);
    $statusClass = match ($enrollment->status) {
        'completed' => 'bg-primary-transparent text-primary',
        'active' => 'bg-success-transparent text-success',
        'pending' => 'bg-warning-transparent text-warning',
        default => 'bg-secondary-transparent text-secondary',
    };
    $progressBarClass = $progress >= 100 ? 'bg-primary' : ($progress > 0 ? 'bg-success' : 'bg-secondary');
@endphp

<div class="col-md-6 col-xl-4 col-xxl-3">
    <article class="student-enrollment-card">
        <div class="student-enrollment-card__thumb">
            @if($course?->slug)
                <a href="{{ route('student.courses.show', $course->slug) }}" class="d-block h-100 w-100">
            @endif
            @if($course?->thumbnail_url)
                <img src="{{ $course->thumbnail_url }}" alt="{{ $course->thumbnail_alt ?? $course->title }}">
            @else
                <i class="ri-graduation-cap-line student-enrollment-card__thumb-icon"></i>
            @endif
            @if($course?->slug)
                </a>
            @endif
            <span class="badge rounded-pill student-enrollment-card__status {{ $statusClass }}">
                {{ $enrollment->status_label }}
            </span>
        </div>

        <div class="student-enrollment-card__body">
            @if($course?->category)
                <div class="student-enrollment-card__category">{{ $course->category->name }}</div>
            @endif

            <h3 class="student-enrollment-card__title">
                @if($course?->slug)
                    <a href="{{ route('student.courses.show', $course->slug) }}">{{ $course->title }}</a>
                @else
                    {{ $course?->title ?? 'كورس محذوف' }}
                @endif
            </h3>

            <div class="student-enrollment-card__meta">
                @if($course?->instructor)
                    <span><i class="ri-user-star-line me-1"></i>{{ $course->instructor->name }}</span>
                @endif
                @if($enrollment->enrolled_at)
                    <span class="mx-1">·</span>
                    <span><i class="ri-calendar-line me-1"></i>{{ $enrollment->enrolled_at->format('Y-m-d') }}</span>
                @endif
            </div>

            <div class="student-enrollment-card__progress-label">
                <span class="text-muted">التقدم</span>
                <span class="fw-semibold">{{ $progress }}%</span>
            </div>
            <div class="progress rounded-pill mb-0" style="height: 7px;">
                <div class="progress-bar {{ $progressBarClass }} rounded-pill" style="width: {{ max($progress, $progress > 0 ? 6 : 0) }}%"></div>
            </div>

            <div class="student-enrollment-card__footer">
                <small class="text-muted">
                    @if($enrollment->status === 'completed' && $enrollment->completed_at)
                        أُتمم {{ $enrollment->completed_at->diffForHumans() }}
                    @else
                        {{ $enrollment->source_label }}
                    @endif
                </small>
                @if($course?->slug)
                    <a href="{{ route('student.courses.show', $course->slug) }}" class="btn btn-sm btn-primary-light rounded-pill">
                        {{ $progress > 0 && $progress < 100 ? 'متابعة' : ($progress >= 100 ? 'مراجعة' : 'ابدأ') }}
                        <i class="ri-arrow-left-line ms-1"></i>
                    </a>
                @endif
            </div>
        </div>
    </article>
</div>
