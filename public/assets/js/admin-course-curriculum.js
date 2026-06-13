(function (window) {
    'use strict';

    var providerHints = {
        youtube: 'رابط YouTube أو معرّف الفيديو (11 حرفاً)',
        vimeo: 'رابط Vimeo أو رقم الفيديو',
        bunny_stream: 'library_id/video_id أو رابط embed من Bunny Stream',
        bunny_cdn: 'رابط مباشر لملف الفيديو على Bunny CDN'
    };

    function csrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : '';
    }

    function toast(message, type) {
        if (window.adminUiToast) {
            window.adminUiToast(message, type || 'success');
        }
    }

    function request(url, method, body) {
        var options = {
            method: method,
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        if (body) {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(body);
        }

        return fetch(url, options).then(function (response) {
            return response.json().then(function (data) {
                if (!response.ok) {
                    var error = new Error(data.message || 'حدث خطأ');
                    error.data = data;
                    throw error;
                }
                return data;
            });
        });
    }

    function updateStats(stats) {
        if (!stats) return;
        var sectionsEl = document.getElementById('curriculumSectionsCount');
        var lessonsEl = document.getElementById('curriculumLessonsCount');
        var durationEl = document.getElementById('curriculumDurationHours');
        if (sectionsEl && stats.sections_count !== undefined) sectionsEl.textContent = stats.sections_count;
        if (lessonsEl && stats.lessons_count !== undefined) lessonsEl.textContent = stats.lessons_count;
        if (durationEl && stats.duration_hours !== undefined) {
            durationEl.innerHTML = stats.duration_hours + ' <span class="fs-14 fw-normal text-muted">ساعة</span>';
        }
    }

    function replaceHtml(html) {
        var target = document.getElementById('curriculumSectionsTarget');
        if (target && html) {
            target.innerHTML = html;
            window.AdminCourseCurriculum.initSortable();
        }
    }

    window.AdminCourseCurriculum = {
        config: null,

        init: function (config) {
            this.config = config;
            this.bindSectionModal();
            this.bindLessonModal();
            this.bindDeletes();
            this.initSortable();
        },

        initSortable: function () {
            var self = this;
            if (typeof Sortable === 'undefined') return;

            var sectionsList = document.getElementById('curriculumSectionsList');
            if (sectionsList && !sectionsList.dataset.sortableInit) {
                sectionsList.dataset.sortableInit = '1';
                Sortable.create(sectionsList, {
                    handle: '.curriculum-section-card .curriculum-drag-handle',
                    animation: 150,
                    onEnd: function () {
                        var ids = Array.from(sectionsList.querySelectorAll('[data-section-id]'))
                            .map(function (el) { return parseInt(el.dataset.sectionId, 10); });
                        self.reorder({ sections: ids });
                    }
                });
            }

            document.querySelectorAll('.curriculum-lessons-builder').forEach(function (list) {
                if (list.dataset.sortableInit) return;
                list.dataset.sortableInit = '1';
                var sectionId = list.dataset.sectionId;
                Sortable.create(list, {
                    handle: '.curriculum-lesson-row .curriculum-drag-handle',
                    animation: 150,
                    onEnd: function () {
                        var ids = Array.from(list.querySelectorAll('[data-lesson-id]'))
                            .map(function (el) { return parseInt(el.dataset.lessonId, 10); });
                        self.reorder({ section_id: parseInt(sectionId, 10), lessons: ids });
                    }
                });
            });
        },

        reorder: function (payload) {
            var self = this;
            request(this.config.routes.reorder, 'POST', payload).then(function (data) {
                replaceHtml(data.html);
                updateStats(data.stats);
                toast(data.message);
            }).catch(function (err) {
                toast(err.message || 'تعذر تحديث الترتيب', 'error');
            });
        },

        bindSectionModal: function () {
            var self = this;
            var modal = document.getElementById('sectionModal');
            var form = document.getElementById('sectionForm');
            if (!modal || !form) return;

            modal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var mode = button ? button.getAttribute('data-section-mode') : 'create';
                var titleEl = document.getElementById('sectionModalTitle');
                var idEl = document.getElementById('sectionId');
                var nameEl = document.getElementById('sectionTitle');

                if (mode === 'edit' && button) {
                    titleEl.textContent = 'تعديل القسم';
                    idEl.value = button.getAttribute('data-section-id') || '';
                    nameEl.value = button.getAttribute('data-section-title') || '';
                } else {
                    titleEl.textContent = 'إضافة قسم';
                    idEl.value = '';
                    nameEl.value = '';
                }
            });

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                var sectionId = document.getElementById('sectionId').value;
                var title = document.getElementById('sectionTitle').value.trim();
                var url = sectionId
                    ? self.config.routes.sectionsUpdate + '/' + sectionId
                    : self.config.routes.sectionsStore;
                var method = sectionId ? 'PUT' : 'POST';

                request(url, method, { title: title }).then(function (data) {
                    replaceHtml(data.html);
                    updateStats(data.stats);
                    toast(data.message);
                    bootstrap.Modal.getInstance(modal).hide();
                }).catch(function (err) {
                    var msg = err.data && err.data.errors
                        ? Object.values(err.data.errors).flat().join(' ')
                        : (err.message || 'حدث خطأ');
                    toast(msg, 'error');
                });
            });
        },

        bindLessonModal: function () {
            var self = this;
            var modal = document.getElementById('lessonModal');
            var form = document.getElementById('lessonForm');
            var providerSelect = document.getElementById('lessonProvider');
            var hintEl = document.getElementById('lessonReferenceHint');
            if (!modal || !form) return;

            function updateHint() {
                if (hintEl && providerSelect) {
                    hintEl.textContent = providerHints[providerSelect.value] || '';
                }
            }

            if (providerSelect) {
                providerSelect.addEventListener('change', updateHint);
            }

            modal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var mode = button ? button.getAttribute('data-lesson-mode') : 'create';
                var titleEl = document.getElementById('lessonModalTitle');
                document.getElementById('lessonId').value = mode === 'edit' && button ? (button.getAttribute('data-lesson-id') || '') : '';
                document.getElementById('lessonSectionId').value = button ? (button.getAttribute('data-section-id') || '') : '';
                document.getElementById('lessonTitle').value = mode === 'edit' && button ? (button.getAttribute('data-lesson-title') || '') : '';
                document.getElementById('lessonProvider').value = mode === 'edit' && button ? (button.getAttribute('data-lesson-provider') || 'youtube') : 'youtube';
                document.getElementById('lessonReference').value = mode === 'edit' && button ? (button.getAttribute('data-lesson-reference') || '') : '';
                document.getElementById('lessonDuration').value = mode === 'edit' && button ? (button.getAttribute('data-lesson-duration') || '') : '';
                titleEl.textContent = mode === 'edit' ? 'تعديل الدرس' : 'إضافة درس';
                updateHint();
            });

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                var lessonId = document.getElementById('lessonId').value;
                var sectionId = document.getElementById('lessonSectionId').value;
                var payload = {
                    title: document.getElementById('lessonTitle').value.trim(),
                    video_provider: document.getElementById('lessonProvider').value,
                    video_reference: document.getElementById('lessonReference').value.trim(),
                    duration: document.getElementById('lessonDuration').value.trim()
                };
                var url = lessonId
                    ? self.config.routes.lessonsUpdate + '/' + lessonId
                    : self.config.routes.lessonsStore + '/' + sectionId + '/lessons';
                var method = lessonId ? 'PUT' : 'POST';

                request(url, method, payload).then(function (data) {
                    replaceHtml(data.html);
                    updateStats(data.stats);
                    toast(data.message);
                    bootstrap.Modal.getInstance(modal).hide();
                }).catch(function (err) {
                    var msg = err.data && err.data.errors
                        ? Object.values(err.data.errors).flat().join(' ')
                        : (err.message || 'حدث خطأ');
                    toast(msg, 'error');
                });
            });
        },

        bindDeletes: function () {
            var self = this;
            document.addEventListener('click', function (event) {
                var sectionBtn = event.target.closest('[data-section-delete]');
                var lessonBtn = event.target.closest('[data-lesson-delete]');

                if (sectionBtn) {
                    var sectionId = sectionBtn.getAttribute('data-section-delete');
                    var sectionTitle = sectionBtn.getAttribute('data-section-title') || '';
                    if (!confirm('حذف القسم «' + sectionTitle + '» وجميع دروسه؟')) return;

                    request(self.config.routes.sectionsDestroy + '/' + sectionId, 'DELETE').then(function (data) {
                        replaceHtml(data.html);
                        updateStats(data.stats);
                        toast(data.message);
                    }).catch(function (err) {
                        toast(err.message || 'تعذر الحذف', 'error');
                    });
                }

                if (lessonBtn) {
                    var lessonId = lessonBtn.getAttribute('data-lesson-delete');
                    var lessonTitle = lessonBtn.getAttribute('data-lesson-title') || '';
                    if (!confirm('حذف الدرس «' + lessonTitle + '»؟')) return;

                    request(self.config.routes.lessonsDestroy + '/' + lessonId, 'DELETE').then(function (data) {
                        replaceHtml(data.html);
                        updateStats(data.stats);
                        toast(data.message);
                    }).catch(function (err) {
                        toast(err.message || 'تعذر الحذف', 'error');
                    });
                }
            });
        }
    };
})(window);
