@php
    $modalId = $modalId ?? 'enrollmentGrantModal';
    $formAction = $formAction ?? route('admin.enrollments.store');
    $presetStudentId = $presetStudentId ?? null;
    $presetStudentLabel = $presetStudentLabel ?? null;
    $presetCourseId = $presetCourseId ?? null;
    $presetCourseLabel = $presetCourseLabel ?? null;
    $lockStudent = ! empty($lockStudent);
    $lockCourse = ! empty($lockCourse);
@endphp

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true"
     data-enrollment-grant-modal
     data-search-students-url="{{ route('admin.enrollments.search-students') }}"
     data-search-courses-url="{{ route('admin.enrollments.search-courses') }}">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ $formAction }}" id="{{ $modalId }}Form">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="{{ $modalId }}Label">
                        <i class="ri-user-add-line text-primary me-2"></i>
                        {{ $title ?? 'تسجيل طالب في كورس' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body pt-3">
                    @if(!empty($subtitle))
                        <p class="text-muted fs-13 mb-3">{{ $subtitle }}</p>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="{{ $modalId }}_student_id" class="form-label">الطالب <span class="text-danger">*</span></label>
                            <select name="student_id" id="{{ $modalId }}_student_id" class="form-select enrollment-grant-student-select"
                                    required {{ $lockStudent ? 'disabled' : '' }}
                                    data-preset-id="{{ $presetStudentId }}"
                                    data-preset-text="{{ $presetStudentLabel }}">
                                @if($presetStudentId && $presetStudentLabel)
                                    <option value="{{ $presetStudentId }}" selected>{{ $presetStudentLabel }}</option>
                                @endif
                            </select>
                            @if($lockStudent && $presetStudentId)
                                <input type="hidden" name="student_id" value="{{ $presetStudentId }}">
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="{{ $modalId }}_course_id" class="form-label">الكورس <span class="text-danger">*</span></label>
                            <select name="course_id" id="{{ $modalId }}_course_id" class="form-select enrollment-grant-course-select"
                                    required {{ $lockCourse ? 'disabled' : '' }}
                                    data-preset-id="{{ $presetCourseId }}"
                                    data-preset-text="{{ $presetCourseLabel }}">
                                @if($presetCourseId && $presetCourseLabel)
                                    <option value="{{ $presetCourseId }}" selected>{{ $presetCourseLabel }}</option>
                                @endif
                            </select>
                            @if($lockCourse && $presetCourseId)
                                <input type="hidden" name="course_id" value="{{ $presetCourseId }}">
                            @endif
                        </div>
                        <div class="col-12">
                            <label for="{{ $modalId }}_source" class="form-label">مصدر التسجيل</label>
                            <select name="source" id="{{ $modalId }}_source" class="form-select">
                                <option value="admin_grant">منح إداري</option>
                                <option value="free">مجاني</option>
                                <option value="promo">عرض ترويجي</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-light border mt-3 mb-0 fs-13 enrollment-grant-preview d-none" role="status">
                        <i class="ri-information-line text-primary me-1"></i>
                        <span class="enrollment-grant-preview__text"></span>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary btn-wave">
                        <i class="ri-check-line me-1"></i> تأكيد التسجيل
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
