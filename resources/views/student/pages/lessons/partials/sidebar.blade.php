@php
    use App\Models\QuestionModule;
    use App\Models\Quiz;
@endphp

@foreach($sections as $section)
    @php $sectionId = 'lesson-section-' . $section->id; @endphp
    <div class="student-lesson-syllabus-section">
        <button class="student-lesson-syllabus-section__toggle" type="button"
                data-bs-toggle="collapse" data-bs-target="#{{ $sectionId }}"
                aria-expanded="{{ $section->lessons->contains('id', $lesson->id) ? 'true' : 'false' }}">
            <span>{{ $section->title }}</span>
            <i class="ri-arrow-down-s-line"></i>
        </button>
        <div class="collapse {{ $section->lessons->contains('id', $lesson->id) ? 'show' : '' }}" id="{{ $sectionId }}">
            @foreach($section->lessons as $sectionLesson)
                @php
                    $isActive = $sectionLesson->id === $lesson->id;
                    $isDone = ($lessonProgress->get($sectionLesson->id)?->status ?? '') === 'completed';
                @endphp
                <a href="{{ route('student.lessons.show', $sectionLesson->id) }}"
                   class="student-lesson-syllabus-item {{ $isActive ? 'is-active' : '' }} {{ $isDone ? 'is-done' : '' }}">
                    <span class="student-lesson-syllabus-item__icon">
                        <i class="ri-{{ $isDone ? 'check-line' : ($isActive ? 'play-circle-fill' : 'play-circle-line') }}"></i>
                    </span>
                    <span class="student-lesson-syllabus-item__title">{{ $sectionLesson->title }}</span>
                    @if($sectionLesson->formatted_duration)
                        <span class="student-lesson-syllabus-item__duration">{{ $sectionLesson->formatted_duration }}</span>
                    @endif
                </a>
            @endforeach

            @foreach($section->modules as $module)
                @php
                    $moduleUrl = null;
                    if ($module->modulable_type === Quiz::class || str_ends_with($module->modulable_type ?? '', 'Quiz')) {
                        $moduleUrl = route('student.quizzes.show', $module->modulable_id);
                    } elseif ($module->modulable_type === QuestionModule::class || str_ends_with($module->modulable_type ?? '', 'QuestionModule')) {
                        $moduleUrl = route('student.question-module.start', $module->modulable_id);
                    }
                @endphp
                @if($moduleUrl)
                    <a href="{{ $moduleUrl }}" class="student-lesson-syllabus-item">
                        <span class="student-lesson-syllabus-item__icon"><i class="ri-puzzle-line"></i></span>
                        <span class="student-lesson-syllabus-item__title">{{ $module->title }}</span>
                        <span class="student-lesson-syllabus-item__duration">{{ $module->module_type === 'quiz' ? 'اختبار' : 'تدريب' }}</span>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
@endforeach
