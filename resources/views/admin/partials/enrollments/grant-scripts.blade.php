<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
(function () {
    if (window.AdminEnrollmentGrant) {
        return;
    }

    function initSelect($el, url, placeholder, presetId, presetText) {
        if (typeof jQuery === 'undefined' || !$el.length || $el.hasClass('select2-hidden-accessible')) {
            return;
        }

        $el.select2({
            theme: 'bootstrap-5',
            width: '100%',
            dir: 'rtl',
            placeholder: placeholder,
            allowClear: !$el.prop('disabled'),
            minimumInputLength: 0,
            dropdownParent: $el.closest('.modal'),
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { search: params.term || '', term: params.term || '' };
                },
                processResults: function (data) {
                    return { results: data.results || [] };
                },
            },
        });

        if (presetId && presetText) {
            const option = new Option(presetText, presetId, true, true);
            $el.append(option).trigger('change');
        }
    }

    function updatePreview(modal) {
        const preview = modal.querySelector('.enrollment-grant-preview');
        const previewText = modal.querySelector('.enrollment-grant-preview__text');
        if (!preview || !previewText || typeof jQuery === 'undefined') {
            return;
        }

        const studentSelect = modal.querySelector('.enrollment-grant-student-select');
        const courseSelect = modal.querySelector('.enrollment-grant-course-select');
        const $student = jQuery(studentSelect);
        const $course = jQuery(courseSelect);
        const studentText = $student.find('option:selected').text().trim();
        const courseText = $course.find('option:selected').text().trim();
        const studentId = $student.val();
        const courseId = $course.val();

        if (studentId && courseId && studentText && courseText) {
            previewText.textContent = 'سيتم تسجيل «' + studentText + '» في كورس «' + courseText + '» بحالة نشطة.';
            preview.classList.remove('d-none');
        } else {
            preview.classList.add('d-none');
        }
    }

    function initModal(modal) {
        if (!modal || modal.dataset.enrollmentGrantInitialized === '1' || typeof jQuery === 'undefined') {
            return;
        }

        const studentsUrl = modal.dataset.searchStudentsUrl;
        const coursesUrl = modal.dataset.searchCoursesUrl;
        const studentSelect = modal.querySelector('.enrollment-grant-student-select');
        const courseSelect = modal.querySelector('.enrollment-grant-course-select');

        if (!studentSelect || !courseSelect) {
            return;
        }

        const $student = jQuery(studentSelect);
        const $course = jQuery(courseSelect);

        if (!$student.prop('disabled')) {
            initSelect(
                $student,
                studentsUrl,
                'ابحث عن طالب بالاسم أو البريد أو الرمز',
                studentSelect.dataset.presetId,
                studentSelect.dataset.presetText
            );
        }

        if (!$course.prop('disabled')) {
            initSelect(
                $course,
                coursesUrl,
                'ابحث عن كورس بالعنوان',
                courseSelect.dataset.presetId,
                courseSelect.dataset.presetText
            );
        }

        $student.on('change select2:select select2:clear', function () { updatePreview(modal); });
        $course.on('change select2:select select2:clear', function () { updatePreview(modal); });

        modal.addEventListener('shown.bs.modal', function () {
            updatePreview(modal);
        });

        modal.dataset.enrollmentGrantInitialized = '1';
    }

    function openGrantModal(options) {
        options = options || {};
        const modalId = options.modalId || 'enrollmentGrantModal';
        const modalEl = document.getElementById(modalId) || document.querySelector('[data-enrollment-grant-modal]');

        if (!modalEl) {
            console.error('Enrollment grant modal not found:', modalId);
            return;
        }

        const studentSelect = modalEl.querySelector('.enrollment-grant-student-select');
        const courseSelect = modalEl.querySelector('.enrollment-grant-course-select');

        if (options.studentId && studentSelect && !studentSelect.disabled && typeof jQuery !== 'undefined') {
            const $student = jQuery(studentSelect);
            if (options.studentLabel) {
                $student.empty();
                $student.append(new Option(options.studentLabel, options.studentId, true, true)).trigger('change');
            } else {
                $student.val(options.studentId).trigger('change');
            }
        }

        if (options.courseId && courseSelect && !courseSelect.disabled && typeof jQuery !== 'undefined') {
            const $course = jQuery(courseSelect);
            if (options.courseLabel) {
                $course.empty();
                $course.append(new Option(options.courseLabel, options.courseId, true, true)).trigger('change');
            } else {
                $course.val(options.courseId).trigger('change');
            }
        }

        if (options.formAction) {
            const form = modalEl.querySelector('form');
            if (form) {
                form.action = options.formAction;
            }
        }

        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        modalEl.addEventListener('shown.bs.modal', function onShown() {
            modalEl.removeEventListener('shown.bs.modal', onShown);
            initModal(modalEl);
            updatePreview(modalEl);
        });
        modalInstance.show();
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-open-enrollment-grant]');
        if (!btn) {
            return;
        }
        e.preventDefault();
        openGrantModal({
            modalId: btn.dataset.modalId || 'enrollmentGrantModal',
            studentId: btn.dataset.studentId || null,
            studentLabel: btn.dataset.studentLabel || null,
            courseId: btn.dataset.courseId || null,
            courseLabel: btn.dataset.courseLabel || null,
            formAction: btn.dataset.formAction || null,
        });
    });

    function boot() {
        document.querySelectorAll('[data-enrollment-grant-modal]').forEach(initModal);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }

    window.AdminEnrollmentGrant = {
        initModal: initModal,
        open: openGrantModal,
    };
})();
</script>
