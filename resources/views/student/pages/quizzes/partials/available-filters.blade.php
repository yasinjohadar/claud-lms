@php
    $currentType = request('quiz_type', '');
    $typeFilters = [
        ['key' => '', 'label' => 'الكل', 'icon' => 'ri-apps-line'],
        ['key' => 'practice', 'label' => 'تدريبي', 'icon' => 'ri-edit-line'],
        ['key' => 'graded', 'label' => 'مُقيّم', 'icon' => 'ri-award-line'],
        ['key' => 'final_exam', 'label' => 'نهائي', 'icon' => 'ri-flag-line'],
    ];
    $courseParams = request()->only('course_id');
@endphp

<div class="card custom-card mb-4">
    <div class="card-header border-0 pb-0">
        <h5 class="card-title mb-1">
            <i class="ri-filter-3-line text-primary me-1"></i>
            تصفية الاختبارات
        </h5>
        <p class="text-muted fs-12 mb-0">اختر الكورس أو نوع الاختبار</p>
    </div>
    <div class="card-body pt-3">
        <form method="GET" action="{{ route('student.quizzes.index') }}" id="quizFilterForm">
            @if(request('course_id'))
                <input type="hidden" name="course_id" value="{{ request('course_id') }}">
            @endif

            <div class="student-quiz-filter-chips mb-3">
                @foreach($typeFilters as $filter)
                    @php
                        $params = array_filter(array_merge($courseParams, ['quiz_type' => $filter['key'] ?: null]));
                    @endphp
                    <a href="{{ route('student.quizzes.index', $params) }}"
                       class="student-quiz-filter-chip {{ $currentType === $filter['key'] ? 'is-active' : '' }}">
                        <i class="{{ $filter['icon'] }}"></i>
                        <span>{{ $filter['label'] }}</span>
                    </a>
                @endforeach
            </div>

            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label fs-12 fw-semibold">الكورس</label>
                    <select name="course_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">جميع الكورسات</option>
                        @foreach($courses ?? [] as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    @if(request()->hasAny(['course_id', 'quiz_type']))
                        <a href="{{ route('student.quizzes.index') }}" class="btn btn-sm btn-light border btn-wave w-100">
                            <i class="ri-refresh-line me-1"></i>إعادة تعيين
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
