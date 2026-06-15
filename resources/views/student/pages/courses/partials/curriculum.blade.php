@php
    use App\Models\QuestionModule;
    use App\Models\Quiz;
@endphp

@if($course->sections->isNotEmpty())
    <div class="accordion form-accordion student-curriculum-accordion" id="courseCurriculum">
        @foreach($course->sections as $index => $section)
            @php $collapseId = 'section-' . $section->id; @endphp
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold"
                            type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
                        <span class="badge badge-soft-primary me-2">{{ $index + 1 }}</span>
                        {{ $section->title }}
                        <span class="ms-auto me-2 text-muted fs-12 fw-normal">
                            {{ $section->lessons->count() }} دروس
                            @if($section->modules->count())
                                · {{ $section->modules->count() }} أنشطة
                            @endif
                        </span>
                    </button>
                </h2>
                <div id="{{ $collapseId }}" class="accordion-collapse collapse"
                     data-bs-parent="#courseCurriculum">
                    <div class="accordion-body p-0">
                        @foreach($section->lessons as $lesson)
                            @php
                                $progress = $lessonProgress->get($lesson->id);
                                $isDone = $progress?->status === 'completed';
                            @endphp
                            <a href="{{ route('student.lessons.show', $lesson->id) }}" class="student-curriculum-item">
                                <span class="student-curriculum-item__icon {{ $isDone ? 'student-curriculum-item__icon--done' : 'student-curriculum-item__icon--video' }}">
                                    <i class="ri-{{ $isDone ? 'check-line' : 'play-circle-line' }}"></i>
                                </span>
                                <span class="student-curriculum-item__title">{{ $lesson->title }}</span>
                                <span class="student-curriculum-item__meta">
                                    {{ $lesson->formatted_duration ?? $lesson->provider_label }}
                                </span>
                            </a>
                        @endforeach

                        @foreach($section->modules as $module)
                            @php
                                $moduleUrl = null;
                                $iconClass = 'student-curriculum-item__icon--module';
                                $icon = 'ri-puzzle-line';

                                if ($module->modulable_type === Quiz::class || str_ends_with($module->modulable_type ?? '', 'Quiz')) {
                                    $moduleUrl = route('student.quizzes.show', $module->modulable_id);
                                    $iconClass = 'student-curriculum-item__icon--quiz';
                                    $icon = 'ri-questionnaire-line';
                                } elseif ($module->modulable_type === QuestionModule::class || str_ends_with($module->modulable_type ?? '', 'QuestionModule')) {
                                    $moduleUrl = route('student.question-module.start', $module->modulable_id);
                                }
                            @endphp
                            @if($moduleUrl)
                                <a href="{{ $moduleUrl }}" class="student-curriculum-item">
                                    <span class="student-curriculum-item__icon {{ $iconClass }}">
                                        <i class="{{ $icon }}"></i>
                                    </span>
                                    <span class="student-curriculum-item__title">{{ $module->title }}</span>
                                    <span class="student-curriculum-item__meta">
                                        {{ $module->module_type === 'quiz' ? 'اختبار' : 'تدريب' }}
                                    </span>
                                </a>
                            @endif
                        @endforeach

                        @foreach($section->resources as $resource)
                            @php
                                $resourceUrl = $resource->isLink()
                                    ? $resource->url
                                    : route('courses.resource-file.download', [$course->slug, $resource->id]);
                            @endphp
                            <a href="{{ $resourceUrl }}" class="student-curriculum-item"
                               @if($resource->isLink()) target="_blank" rel="noopener" @endif>
                                <span class="student-curriculum-item__icon student-curriculum-item__icon--file">
                                    <i class="ri-{{ $resource->isLink() ? 'link' : 'file-download-line' }}"></i>
                                </span>
                                <span class="student-curriculum-item__title">{{ $resource->title }}</span>
                                <span class="student-curriculum-item__meta">{{ $resource->type_label }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-4 text-muted">
        <i class="ri-book-open-line fs-2 d-block mb-2"></i>
        لا يوجد منهج منشور لهذا الكورس بعد.
    </div>
@endif
