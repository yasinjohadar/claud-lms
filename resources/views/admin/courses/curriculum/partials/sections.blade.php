@php
    use App\Services\VideoReferenceService;
    $videoService = app(VideoReferenceService::class);
@endphp

@if($course->sections->isEmpty())
    <div class="empty-state py-5">
        <div class="empty-state-icon"><i class="ri-book-open-line"></i></div>
        <h5 class="fw-bold mb-2">لا توجد أقسام بعد</h5>
        <p class="text-muted mb-3">ابدأ بإضافة أول قسم ثم أضف الدروس مع روابط الفيديو.</p>
        <button type="button" class="btn btn-primary btn-sm btn-wave" data-bs-toggle="modal" data-bs-target="#sectionModal" data-section-mode="create">
            <i class="ri-add-line me-1"></i> إضافة قسم
        </button>
    </div>
@else
    <div id="curriculumSectionsList" class="curriculum-builder curriculum-accordion">
        @foreach($course->sections as $section)
            <div class="curriculum-section-card" data-section-id="{{ $section->id }}">
                <div class="curriculum-section-card__header d-flex align-items-center justify-content-between gap-2"
                     data-section-accordion-trigger
                     data-collapse-target="#curriculumSectionBody{{ $section->id }}"
                     role="button"
                     tabindex="0"
                     aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                     aria-controls="curriculumSectionBody{{ $section->id }}">
                    <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0">
                        <span class="curriculum-drag-handle text-muted" data-accordion-ignore title="سحب لإعادة الترتيب">
                            <i class="ri-drag-move-2-line"></i>
                        </span>
                        <span class="curriculum-section-chevron" aria-hidden="true">
                            <i class="ri-arrow-down-s-line"></i>
                        </span>
                        <div class="curriculum-section-card__summary">
                            <h6 class="mb-0 fw-bold">{{ $section->title }}</h6>
                            <span class="text-muted fs-12">{{ $section->lessons->count() }} درس</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap curriculum-section-card__actions" data-accordion-ignore>
                        <div class="action-btn-group">
                            <button type="button" class="action-btn action-btn--edit"
                                    data-bs-toggle="modal" data-bs-target="#sectionModal"
                                    data-section-mode="edit"
                                    data-section-id="{{ $section->id }}"
                                    data-section-title="{{ $section->title }}"
                                    title="تعديل القسم">
                                <i class="ri-pencil-line"></i>
                            </button>
                            <button type="button" class="action-btn action-btn--delete"
                                    data-section-delete="{{ $section->id }}"
                                    data-section-title="{{ $section->title }}"
                                    title="حذف القسم">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm btn-wave"
                                data-bs-toggle="modal" data-bs-target="#lessonModal"
                                data-lesson-mode="create"
                                data-section-id="{{ $section->id }}">
                            <i class="ri-add-line me-1"></i> درس
                        </button>
                        <button type="button" class="btn btn-light border btn-sm btn-wave"
                                data-bs-toggle="modal" data-bs-target="#resourceModal"
                                data-resource-mode="create"
                                data-resource-scope="section"
                                data-resource-section-id="{{ $section->id }}">
                            <i class="ri-attachment-line me-1"></i> مورد
                        </button>
                    </div>
                </div>
                <div class="collapse {{ $loop->first ? 'show' : '' }}" id="curriculumSectionBody{{ $section->id }}">
                    <div class="curriculum-section-card__body">
                    @if($section->lessons->isEmpty())
                        <p class="text-muted fs-13 mb-0 py-3 px-3">لا توجد دروس في هذا القسم.</p>
                    @else
                        <ul class="curriculum-lessons-builder list-unstyled mb-0" data-section-id="{{ $section->id }}">
                            @foreach($section->lessons as $lesson)
                                <li class="curriculum-lesson-row" data-lesson-id="{{ $lesson->id }}">
                                    <span class="curriculum-drag-handle text-muted"><i class="ri-drag-move-2-line"></i></span>
                                    <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}">
                                        <i class="ri-play-circle-line"></i>
                                    </span>
                                    <div class="curriculum-lesson-row__info">
                                        <span class="fw-bold">{{ $lesson->title }}</span>
                                        <span class="text-muted fs-12 d-block">
                                            <span class="badge-soft badge-soft-primary me-1">{{ $lesson->provider_label }}</span>
                                            <span dir="ltr" class="en-text">{{ $videoService->displayReference($lesson->video_provider, $lesson->video_reference) }}</span>
                                            @if($lesson->formatted_duration)
                                                &bull; <span class="en-text">{{ $lesson->formatted_duration }}</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="action-btn-group">
                                        <button type="button" class="action-btn action-btn--edit"
                                                data-bs-toggle="modal" data-bs-target="#lessonModal"
                                                data-lesson-mode="edit"
                                                data-lesson-id="{{ $lesson->id }}"
                                                data-section-id="{{ $section->id }}"
                                                data-lesson-title="{{ $lesson->title }}"
                                                data-lesson-provider="{{ $lesson->video_provider }}"
                                                data-lesson-reference="{{ $lesson->video_provider === 'bunny_stream' ? $videoService->displayReference($lesson->video_provider, $lesson->video_reference) : $lesson->video_reference }}"
                                                data-lesson-duration="{{ $lesson->formatted_duration }}"
                                                title="تعديل">
                                            <i class="ri-pencil-line"></i>
                                        </button>
                                        <button type="button" class="action-btn action-btn--delete"
                                                data-lesson-delete="{{ $lesson->id }}"
                                                data-lesson-title="{{ $lesson->title }}"
                                                title="حذف">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if($section->resources->isNotEmpty())
                        <div class="curriculum-section-resources">
                            <div class="curriculum-section-resources__title">
                                <i class="ri-attachment-line me-1"></i> موارد القسم
                            </div>
                            <ul class="curriculum-resources-builder list-unstyled mb-0" data-section-id="{{ $section->id }}" data-resource-scope="section">
                                @foreach($section->resources as $resource)
                                    <li class="curriculum-resource-row curriculum-resource-row--inline" data-resource-id="{{ $resource->id }}">
                                        <span class="curriculum-drag-handle text-muted"><i class="ri-drag-move-2-line"></i></span>
                                        <span class="row-avatar row-avatar--sm {{ $loop->even ? 'row-avatar--alt' : '' }}">
                                            <i class="{{ $resource->isLink() ? 'ri-link' : 'ri-file-line' }}"></i>
                                        </span>
                                        <div class="curriculum-resource-row__info">
                                            <span class="fw-semibold">{{ $resource->title }}</span>
                                            <span class="text-muted fs-12 d-block">
                                                <span class="badge-soft {{ $resource->isLink() ? 'badge-soft-info' : 'badge-soft-warning' }} me-1">{{ $resource->type_label }}</span>
                                                @if($resource->isLink())
                                                    <span dir="ltr">{{ Str::limit($resource->url, 50) }}</span>
                                                @elseif($resource->file_original_name)
                                                    {{ $resource->file_original_name }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="action-btn-group">
                                            <button type="button" class="action-btn action-btn--edit"
                                                    data-bs-toggle="modal" data-bs-target="#resourceModal"
                                                    data-resource-mode="edit"
                                                    data-resource-scope="section"
                                                    data-resource-id="{{ $resource->id }}"
                                                    data-resource-title="{{ $resource->title }}"
                                                    data-resource-type="{{ $resource->type }}"
                                                    data-resource-url="{{ $resource->url }}"
                                                    data-resource-description="{{ $resource->description }}"
                                                    data-resource-section-id="{{ $section->id }}"
                                                    data-resource-file-name="{{ $resource->file_original_name }}"
                                                    data-resource-published="{{ $resource->is_published ? '1' : '0' }}"
                                                    title="تعديل">
                                                <i class="ri-pencil-line"></i>
                                            </button>
                                            <button type="button" class="action-btn action-btn--delete"
                                                    data-resource-delete="{{ $resource->id }}"
                                                    data-resource-title="{{ $resource->title }}"
                                                    title="حذف">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
