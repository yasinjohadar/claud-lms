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

    function requestFormData(url, method, formData) {
        var options = {
            method: method,
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        };

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
        var resourcesEl = document.getElementById('curriculumResourcesCount');
        if (sectionsEl && stats.sections_count !== undefined) sectionsEl.textContent = stats.sections_count;
        if (lessonsEl && stats.lessons_count !== undefined) lessonsEl.textContent = stats.lessons_count;
        if (durationEl && stats.duration_hours !== undefined) {
            durationEl.textContent = stats.duration_hours + ' ساعة';
        }
        if (resourcesEl && stats.resources_count !== undefined) resourcesEl.textContent = stats.resources_count;
    }

    function getOpenSectionIds() {
        return Array.from(document.querySelectorAll('.curriculum-section-card .collapse.show'))
            .map(function (el) {
                return el.id.replace('curriculumSectionBody', '');
            });
    }

    function restoreOpenSections(openIds) {
        if (!openIds || !openIds.length || typeof bootstrap === 'undefined') {
            return;
        }

        document.querySelectorAll('.curriculum-section-card .collapse').forEach(function (el) {
            var sectionId = el.id.replace('curriculumSectionBody', '');
            var shouldShow = openIds.indexOf(sectionId) !== -1;
            var instance = bootstrap.Collapse.getOrCreateInstance(el, { toggle: false });

            if (shouldShow) {
                instance.show();
            } else {
                instance.hide();
            }
        });
    }

    function syncSectionAccordionState(collapseEl) {
        if (!collapseEl) return;
        var isOpen = collapseEl.classList.contains('show');
        var card = collapseEl.closest('.curriculum-section-card');
        if (!card) return;

        var header = card.querySelector('[data-section-accordion-trigger]');
        if (header) {
            header.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }
    }

    function bindSectionAccordions() {
        document.querySelectorAll('.curriculum-section-card .collapse').forEach(function (collapseEl) {
            if (collapseEl._accordionBound) return;
            collapseEl._accordionBound = true;

            collapseEl.addEventListener('shown.bs.collapse', function () {
                syncSectionAccordionState(collapseEl);
            });
            collapseEl.addEventListener('hidden.bs.collapse', function () {
                syncSectionAccordionState(collapseEl);
            });

            syncSectionAccordionState(collapseEl);
        });

        document.querySelectorAll('[data-section-accordion-trigger]').forEach(function (headerEl) {
            if (headerEl._headerAccordionBound) return;
            headerEl._headerAccordionBound = true;

            var targetSelector = headerEl.getAttribute('data-collapse-target');
            var collapseEl = targetSelector ? document.querySelector(targetSelector) : null;
            if (!collapseEl || typeof bootstrap === 'undefined') return;

            function toggleSection() {
                bootstrap.Collapse.getOrCreateInstance(collapseEl).toggle();
            }

            headerEl.addEventListener('click', function (event) {
                if (event.target.closest('[data-accordion-ignore]')) {
                    return;
                }
                toggleSection();
            });

            headerEl.addEventListener('keydown', function (event) {
                if (event.target.closest('[data-accordion-ignore]')) {
                    return;
                }
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    toggleSection();
                }
            });
        });
    }

    function replaceHtml(html) {
        var target = document.getElementById('curriculumSectionsTarget');
        if (!target) return;

        var openIds = getOpenSectionIds();
        if (html) {
            target.innerHTML = html;
            restoreOpenSections(openIds);
            bindSectionAccordions();
        }
    }

    function replaceGlobalResourcesHtml(html) {
        var target = document.getElementById('curriculumGlobalResourcesTarget');
        if (target && html) {
            target.innerHTML = html;
        }
    }

    function updateCurriculum(data) {
        replaceHtml(data.html);
        replaceGlobalResourcesHtml(data.global_resources_html);
        updateStats(data.stats);
        if (window.AdminCourseCurriculum) {
            window.AdminCourseCurriculum.initSortable();
            window.AdminCourseCurriculum.bindSectionAccordions();
        }
    }

    function formatErrors(err) {
        return err.data && err.data.errors
            ? Object.values(err.data.errors).flat().join(' ')
            : (err.message || 'حدث خطأ');
    }

    window.AdminCourseCurriculum = {
        config: null,

        init: function (config) {
            this.config = config;
            this.bindSectionModal();
            this.bindLessonModal();
            this.bindResourceModal();
            this.bindDeletes();
            this.bindSectionAccordions();
            this.initSortable();
        },

        bindSectionAccordions: function () {
            bindSectionAccordions();
        },

        initSortable: function () {
            var self = this;
            if (typeof Sortable === 'undefined') return;

            var sectionsList = document.getElementById('curriculumSectionsList');
            if (sectionsList) {
                if (sectionsList._sortable) {
                    sectionsList._sortable.destroy();
                }
                sectionsList._sortable = Sortable.create(sectionsList, {
                    handle: '.curriculum-section-card .curriculum-drag-handle',
                    animation: 150,
                    onEnd: function () {
                        var ids = Array.from(sectionsList.querySelectorAll('.curriculum-section-card[data-section-id]'))
                            .map(function (el) { return parseInt(el.dataset.sectionId, 10); });
                        self.reorder({ sections: ids });
                    }
                });
            }

            document.querySelectorAll('.curriculum-lessons-builder').forEach(function (list) {
                if (list._sortable) {
                    list._sortable.destroy();
                }
                var sectionId = list.dataset.sectionId;
                list._sortable = Sortable.create(list, {
                    handle: '.curriculum-lesson-row .curriculum-drag-handle',
                    animation: 150,
                    onEnd: function () {
                        var ids = Array.from(list.querySelectorAll('[data-lesson-id]'))
                            .map(function (el) { return parseInt(el.dataset.lessonId, 10); });
                        self.reorder({ section_id: parseInt(sectionId, 10), lessons: ids });
                    }
                });
            });

            document.querySelectorAll('.curriculum-resources-builder[data-resource-scope="section"]').forEach(function (list) {
                if (list._sortable) {
                    list._sortable.destroy();
                }
                var sectionId = list.dataset.sectionId;
                list._sortable = Sortable.create(list, {
                    handle: '.curriculum-resource-row .curriculum-drag-handle',
                    animation: 150,
                    onEnd: function () {
                        var ids = Array.from(list.querySelectorAll('[data-resource-id]'))
                            .map(function (el) { return parseInt(el.dataset.resourceId, 10); });
                        self.reorder({ resource_section_id: parseInt(sectionId, 10), resources: ids });
                    }
                });
            });

            var globalList = document.getElementById('globalResourcesList');
            if (globalList) {
                if (globalList._sortable) {
                    globalList._sortable.destroy();
                }
                globalList._sortable = Sortable.create(globalList, {
                    handle: '.curriculum-resource-row .curriculum-drag-handle',
                    animation: 150,
                    onEnd: function () {
                        var ids = Array.from(globalList.querySelectorAll('[data-resource-id]'))
                            .map(function (el) { return parseInt(el.dataset.resourceId, 10); });
                        self.reorder({ global_resources: ids });
                    }
                });
            }
        },

        reorder: function (payload) {
            var self = this;
            request(this.config.routes.reorder, 'POST', payload).then(function (data) {
                updateCurriculum(data);
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
                    updateCurriculum(data);
                    toast(data.message);
                    bootstrap.Modal.getInstance(modal).hide();
                }).catch(function (err) {
                    toast(formatErrors(err), 'error');
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
                    updateCurriculum(data);
                    toast(data.message);
                    bootstrap.Modal.getInstance(modal).hide();
                }).catch(function (err) {
                    toast(formatErrors(err), 'error');
                });
            });
        },

        bindResourceModal: function () {
            var self = this;
            var modal = document.getElementById('resourceModal');
            var form = document.getElementById('resourceForm');
            var typeSelect = document.getElementById('resourceType');
            var linkField = document.getElementById('resourceLinkField');
            var fileField = document.getElementById('resourceFileField');
            var fileInput = document.getElementById('resourceFile');
            var currentFileEl = document.getElementById('resourceCurrentFile');
            if (!modal || !form) return;

            function toggleTypeFields() {
                var isLink = typeSelect.value === 'link';
                linkField.classList.toggle('d-none', !isLink);
                fileField.classList.toggle('d-none', isLink);
                if (isLink) {
                    fileInput.removeAttribute('required');
                } else if (!document.getElementById('resourceId').value) {
                    fileInput.setAttribute('required', 'required');
                }
            }

            if (typeSelect) {
                typeSelect.addEventListener('change', toggleTypeFields);
            }

            modal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var mode = button ? button.getAttribute('data-resource-mode') : 'create';
                var titleEl = document.getElementById('resourceModalTitle');
                var resourceId = mode === 'edit' && button ? (button.getAttribute('data-resource-id') || '') : '';

                document.getElementById('resourceId').value = resourceId;
                document.getElementById('resourceTitle').value = mode === 'edit' && button ? (button.getAttribute('data-resource-title') || '') : '';
                document.getElementById('resourceType').value = mode === 'edit' && button ? (button.getAttribute('data-resource-type') || 'link') : 'link';
                document.getElementById('resourceUrl').value = mode === 'edit' && button ? (button.getAttribute('data-resource-url') || '') : '';
                document.getElementById('resourceDescription').value = mode === 'edit' && button ? (button.getAttribute('data-resource-description') || '') : '';
                document.getElementById('resourceSection').value = button ? (button.getAttribute('data-resource-section-id') || 'global') : 'global';
                document.getElementById('resourcePublished').checked = mode === 'edit' && button
                    ? button.getAttribute('data-resource-published') === '1'
                    : true;

                var fileName = mode === 'edit' && button ? (button.getAttribute('data-resource-file-name') || '') : '';
                if (currentFileEl) {
                    currentFileEl.textContent = fileName ? 'الملف الحالي: ' + fileName : '';
                }

                fileInput.value = '';
                titleEl.textContent = mode === 'edit' ? 'تعديل المورد' : 'إضافة مورد';
                toggleTypeFields();
            });

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                var resourceId = document.getElementById('resourceId').value;
                var formData = new FormData(form);
                formData.set('title', document.getElementById('resourceTitle').value.trim());
                formData.set('type', document.getElementById('resourceType').value);
                formData.set('course_section_id', document.getElementById('resourceSection').value);
                formData.set('description', document.getElementById('resourceDescription').value.trim());
                formData.set('is_published', document.getElementById('resourcePublished').checked ? '1' : '0');

                if (formData.get('type') === 'link') {
                    formData.set('url', document.getElementById('resourceUrl').value.trim());
                    formData.delete('file');
                } else {
                    formData.delete('url');
                    var file = fileInput.files[0];
                    if (file) {
                        formData.set('file', file);
                    } else {
                        formData.delete('file');
                    }
                }

                var url = resourceId
                    ? self.config.routes.resourcesUpdate + '/' + resourceId
                    : self.config.routes.resourcesStore;
                var method = resourceId ? 'PUT' : 'POST';

                requestFormData(url, method, formData).then(function (data) {
                    updateCurriculum(data);
                    toast(data.message);
                    bootstrap.Modal.getInstance(modal).hide();
                }).catch(function (err) {
                    toast(formatErrors(err), 'error');
                });
            });
        },

        bindDeletes: function () {
            var self = this;
            document.addEventListener('click', function (event) {
                var sectionBtn = event.target.closest('[data-section-delete]');
                var lessonBtn = event.target.closest('[data-lesson-delete]');
                var resourceBtn = event.target.closest('[data-resource-delete]');

                if (sectionBtn) {
                    var sectionId = sectionBtn.getAttribute('data-section-delete');
                    var sectionTitle = sectionBtn.getAttribute('data-section-title') || '';
                    if (!confirm('حذف القسم «' + sectionTitle + '» وجميع دروسه وموارده؟')) return;

                    request(self.config.routes.sectionsDestroy + '/' + sectionId, 'DELETE').then(function (data) {
                        updateCurriculum(data);
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
                        updateCurriculum(data);
                        toast(data.message);
                    }).catch(function (err) {
                        toast(err.message || 'تعذر الحذف', 'error');
                    });
                }

                if (resourceBtn) {
                    var resourceId = resourceBtn.getAttribute('data-resource-delete');
                    var resourceTitle = resourceBtn.getAttribute('data-resource-title') || '';
                    if (!confirm('حذف المورد «' + resourceTitle + '»؟')) return;

                    request(self.config.routes.resourcesDestroy + '/' + resourceId, 'DELETE').then(function (data) {
                        updateCurriculum(data);
                        toast(data.message);
                    }).catch(function (err) {
                        toast(err.message || 'تعذر الحذف', 'error');
                    });
                }
            });
        }
    };
})(window);
