<div class="modal fade" id="sectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="sectionForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="sectionModalTitle">إضافة قسم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="section_id" id="sectionId">
                    <div class="mb-0">
                        <label class="form-label fw-semibold" for="sectionTitle">عنوان القسم</label>
                        <input type="text" class="form-control" id="sectionTitle" name="title" required placeholder="مثال: مقدمة الدورة">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="sectionSubmitBtn">حفظ القسم</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="lessonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="lessonForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="lessonModalTitle">إضافة درس</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="lesson_id" id="lessonId">
                    <input type="hidden" name="section_id" id="lessonSectionId">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="lessonTitle">عنوان الدرس</label>
                        <input type="text" class="form-control" id="lessonTitle" name="title" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="lessonProvider">مصدر الفيديو</label>
                            <select class="form-select" id="lessonProvider" name="video_provider" required>
                                <option value="youtube">YouTube</option>
                                <option value="vimeo">Vimeo</option>
                                <option value="bunny_stream">Bunny Stream</option>
                                <option value="bunny_cdn">Bunny CDN</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="lessonDuration">المدة</label>
                            <input type="text" class="form-control" id="lessonDuration" name="duration" placeholder="12:30" dir="ltr">
                            <small class="text-muted fs-11">دقائق:ثوانٍ</small>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold" for="lessonReference">مرجع الفيديو</label>
                        <input type="text" class="form-control" id="lessonReference" name="video_reference" required dir="ltr">
                        <small class="text-muted fs-11 d-block mt-1" id="lessonReferenceHint">
                            رابط YouTube أو معرّف الفيديو (11 حرفاً)
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="lessonSubmitBtn">حفظ الدرس</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="resourceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="resourceForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="resourceModalTitle">إضافة مورد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="resource_id" id="resourceId">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold" for="resourceTitle">عنوان المورد</label>
                            <input type="text" class="form-control" id="resourceTitle" name="title" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="resourceType">النوع</label>
                            <select class="form-select" id="resourceType" name="type" required>
                                <option value="link">رابط</option>
                                <option value="file">ملف</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="resourceSection">الربط</label>
                        <select class="form-select" id="resourceSection" name="course_section_id">
                            <option value="global">مورد عام (صفحة مستقلة)</option>
                            @foreach($course->sections as $section)
                                <option value="{{ $section->id }}">قسم: {{ $section->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" id="resourceLinkField">
                        <label class="form-label fw-semibold" for="resourceUrl">الرابط</label>
                        <input type="url" class="form-control" id="resourceUrl" name="url" dir="ltr" placeholder="https://">
                    </div>
                    <div class="mb-3 d-none" id="resourceFileField">
                        <label class="form-label fw-semibold" for="resourceFile">الملف</label>
                        <input type="file" class="form-control" id="resourceFile" name="file">
                        <small class="text-muted fs-11 d-block mt-1" id="resourceCurrentFile"></small>
                    </div>
                    <div class="mb-3" id="resourceDescriptionField">
                        <label class="form-label fw-semibold" for="resourceDescription">الوصف (للموارد العامة)</label>
                        <textarea class="form-control" id="resourceDescription" name="description" rows="3" placeholder="وصف اختياري يظهر في صفحة المورد"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="resourcePublished" name="is_published" value="1" checked>
                        <label class="form-check-label" for="resourcePublished">منشور</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="resourceSubmitBtn">حفظ المورد</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.curriculum-builder { padding: 0.5rem; }
.curriculum-accordion .curriculum-section-card {
    border: 1px solid var(--default-border, #e9edf4);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 0.75rem;
}
.curriculum-accordion .curriculum-section-card:last-child { margin-bottom: 0; }
.curriculum-section-card__header {
    padding: 1rem 1.25rem;
    background: rgba(var(--primary-rgb, 13, 110, 253), 0.04);
    border-bottom: 1px solid var(--default-border, #e9edf4);
    cursor: pointer;
    user-select: none;
    transition: background 0.2s ease;
}
.curriculum-section-card__header:hover {
    background: rgba(var(--primary-rgb, 13, 110, 253), 0.07);
}
.curriculum-section-card:has(.collapse:not(.show)) .curriculum-section-card__header {
    border-bottom: 0;
}
.curriculum-section-chevron {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.75rem;
    height: 1.75rem;
    color: var(--primary, #0d6efd);
    flex-shrink: 0;
    pointer-events: none;
}
.curriculum-section-chevron i {
    transition: transform 0.2s ease;
}
.curriculum-section-card__header[aria-expanded="true"] .curriculum-section-chevron i {
    transform: rotate(180deg);
}
.curriculum-section-card__summary {
    min-width: 0;
    pointer-events: none;
}
.curriculum-section-card__actions,
.curriculum-drag-handle {
    cursor: default;
}
.curriculum-drag-handle {
    cursor: grab;
}
.curriculum-section-card__body { padding: 0; }
.curriculum-lesson-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.85rem 1.25rem;
    border-bottom: 1px solid var(--default-border, #e9edf4);
}
.curriculum-lesson-row:last-child { border-bottom: 0; }
.curriculum-lesson-row__info { flex: 1; min-width: 0; }
.curriculum-drag-handle { cursor: grab; font-size: 1.1rem; }
.curriculum-resource-row--inline {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.25rem;
    border-bottom: 1px solid var(--default-border, #e9edf4);
    background: rgba(var(--primary-rgb, 13, 110, 253), 0.02);
}
.curriculum-resource-row--inline:last-child { border-bottom: 0; }
.curriculum-resource-row__info { flex: 1; min-width: 0; }
.curriculum-section-resources {
    margin: 0;
    border-top: 1px solid var(--default-border, #e9edf4);
    background: rgba(var(--primary-rgb, 13, 110, 253), 0.02);
}
.curriculum-section-resources__title {
    padding: 0.65rem 1.25rem 0;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-muted, #6c757d);
}
.row-avatar--sm {
    width: 2rem;
    height: 2rem;
    font-size: 0.85rem;
}
.curriculum-resources-builder .curriculum-drag-handle { opacity: 0.45; }
.curriculum-resources-builder .curriculum-drag-handle:hover { opacity: 1; }
</style>
