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

<style>
.curriculum-builder .curriculum-section-card {
    border: 1px solid var(--default-border, #e9edf4);
    border-radius: 12px;
    overflow: hidden;
}
.curriculum-section-card__header {
    padding: 1rem 1.25rem;
    background: rgba(var(--primary-rgb, 13, 110, 253), 0.04);
    border-bottom: 1px solid var(--default-border, #e9edf4);
}
.curriculum-section-card__body { padding: 0.75rem 1rem; }
.curriculum-lesson-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.65rem 0.5rem;
    border-bottom: 1px solid var(--default-border, #e9edf4);
}
.curriculum-lesson-row:last-child { border-bottom: 0; }
.curriculum-lesson-row__info { flex: 1; min-width: 0; }
.curriculum-lesson-row__actions { display: flex; gap: 0.35rem; }
.curriculum-drag-handle { cursor: grab; }
.curriculum-lesson-row__icon { color: var(--primary, #0d6efd); }
</style>
