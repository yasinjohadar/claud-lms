<div class="student-course-stats">
    <div class="student-course-stat student-course-stat--purple">
        <i class="ri-layout-grid-line"></i>
        <span class="student-course-stat__value">{{ $stats['total_sections'] }}</span>
        <span class="student-course-stat__label">أقسام</span>
    </div>
    <div class="student-course-stat student-course-stat--green">
        <i class="ri-play-circle-line"></i>
        <span class="student-course-stat__value">{{ $stats['total_lessons'] }}</span>
        <span class="student-course-stat__label">دروس · {{ $stats['completed_lessons'] }} مكتمل</span>
    </div>
    <div class="student-course-stat student-course-stat--cyan">
        <i class="ri-percent-line"></i>
        <span class="student-course-stat__value">{{ $stats['progress'] }}%</span>
        <span class="student-course-stat__label">إنجاز</span>
    </div>
    @if($stats['duration_hours'])
        <div class="student-course-stat student-course-stat--orange">
            <i class="ri-time-line"></i>
            <span class="student-course-stat__value">{{ $stats['duration_hours'] }}</span>
            <span class="student-course-stat__label">ساعة</span>
        </div>
    @endif
</div>
