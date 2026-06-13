@php
    use App\Services\VideoReferenceService;
    $videoService = app(VideoReferenceService::class);
@endphp

@if($course->sections->isEmpty())
    <div class="empty-state py-5">
        <div class="empty-state-icon"><i class="ri-book-open-line"></i></div>
        <h5 class="fw-bold mb-2">لا توجد أقسام بعد</h5>
        <p class="text-muted mb-3">ابدأ بإضافة أول قسم ثم أضف الدروس مع روابط الفيديو.</p>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#sectionModal" data-section-mode="create">
            <i class="ri-add-line me-1"></i> إضافة قسم
        </button>
    </div>
@else
    <div id="curriculumSectionsList" class="curriculum-builder">
        @foreach($course->sections as $section)
            <div class="curriculum-section-card mb-3" data-section-id="{{ $section->id }}">
                <div class="curriculum-section-card__header d-flex align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-2 flex-grow-1">
                        <span class="curriculum-drag-handle text-muted" title="سحب لإعادة الترتيب"><i class="ri-drag-move-2-line"></i></span>
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $section->title }}</h6>
                            <span class="text-muted fs-12">{{ $section->lessons->count() }} درس</span>
                        </div>
                    </div>
                    <div class="d-flex gap-1">
                        <button type="button" class="btn btn-light border btn-sm"
                                data-bs-toggle="modal" data-bs-target="#sectionModal"
                                data-section-mode="edit"
                                data-section-id="{{ $section->id }}"
                                data-section-title="{{ $section->title }}">
                            <i class="ri-pencil-line"></i>
                        </button>
                        <button type="button" class="btn btn-light border btn-sm text-danger"
                                data-section-delete="{{ $section->id }}"
                                data-section-title="{{ $section->title }}">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn-sm"
                                data-bs-toggle="modal" data-bs-target="#lessonModal"
                                data-lesson-mode="create"
                                data-section-id="{{ $section->id }}">
                            <i class="ri-add-line me-1"></i> درس
                        </button>
                    </div>
                </div>
                <div class="curriculum-section-card__body">
                    @if($section->lessons->isEmpty())
                        <p class="text-muted fs-13 mb-0 py-2">لا توجد دروس في هذا القسم.</p>
                    @else
                        <ul class="curriculum-lessons-builder list-unstyled mb-0" data-section-id="{{ $section->id }}">
                            @foreach($section->lessons as $lesson)
                                <li class="curriculum-lesson-row" data-lesson-id="{{ $lesson->id }}">
                                    <span class="curriculum-drag-handle text-muted"><i class="ri-drag-move-2-line"></i></span>
                                    <span class="curriculum-lesson-row__icon"><i class="ri-play-circle-line"></i></span>
                                    <div class="curriculum-lesson-row__info">
                                        <span class="fw-semibold">{{ $lesson->title }}</span>
                                        <span class="text-muted fs-12 d-block">
                                            {{ $lesson->provider_label }}
                                            &bull;
                                            <span dir="ltr">{{ $videoService->displayReference($lesson->video_provider, $lesson->video_reference) }}</span>
                                            @if($lesson->formatted_duration)
                                                &bull; {{ $lesson->formatted_duration }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="curriculum-lesson-row__actions">
                                        <button type="button" class="btn btn-light border btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#lessonModal"
                                                data-lesson-mode="edit"
                                                data-lesson-id="{{ $lesson->id }}"
                                                data-section-id="{{ $section->id }}"
                                                data-lesson-title="{{ $lesson->title }}"
                                                data-lesson-provider="{{ $lesson->video_provider }}"
                                                data-lesson-reference="{{ $lesson->video_provider === 'bunny_stream' ? $videoService->displayReference($lesson->video_provider, $lesson->video_reference) : $lesson->video_reference }}"
                                                data-lesson-duration="{{ $lesson->formatted_duration }}">
                                            <i class="ri-pencil-line"></i>
                                        </button>
                                        <button type="button" class="btn btn-light border btn-sm text-danger"
                                                data-lesson-delete="{{ $lesson->id }}"
                                                data-lesson-title="{{ $lesson->title }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
