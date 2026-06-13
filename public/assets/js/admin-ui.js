/**
 * Admin UI – shared interactions for admin panel.
 */
(function () {
    'use strict';

    function initPasswordToggleButtons() {
        document.querySelectorAll('.password-toggle-btn:not([data-pw-toggle-bound])').forEach(function (btn) {
            btn.dataset.pwToggleBound = '1';
            btn.addEventListener('click', function () {
                var input = document.getElementById(btn.getAttribute('data-target'));
                if (!input) return;
                var icon = btn.querySelector('i');
                var isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                if (icon) {
                    icon.className = isPassword ? 'ri-eye-off-line' : 'ri-eye-line';
                }
            });
        });
    }

    function initActionButtonTooltips() {
        if (typeof bootstrap === 'undefined' || !bootstrap.Tooltip) return;
        document.querySelectorAll('.admin-ui .action-btn[title]').forEach(function (el) {
            if (el.dataset.tooltipBound) return;
            el.dataset.tooltipBound = '1';
            new bootstrap.Tooltip(el, { placement: 'top' });
        });
    }

    function initCopyButtons() {
        document.querySelectorAll('.admin-ui .copy-btn[data-copy]').forEach(function (btn) {
            if (btn.dataset.copyBound) return;
            btn.dataset.copyBound = '1';
            btn.addEventListener('click', function () {
                var text = btn.getAttribute('data-copy');
                if (!text) return;
                navigator.clipboard.writeText(text).then(function () {
                    var icon = btn.querySelector('i');
                    if (icon) {
                        icon.className = 'ri-check-line';
                        setTimeout(function () { icon.className = 'ri-file-copy-line'; }, 1500);
                    }
                    if (window.adminUiToast) window.adminUiToast('تم نسخ البريد', 'success');
                });
            });
        });
    }

    function initSettingsTabs(container) {
        if (!container) return;

        var navItems = container.querySelectorAll('.settings-nav__item');
        var panels = container.querySelectorAll('.settings-panel');
        var activeInput = document.getElementById('activeSectionInput');
        var defaultSection = container.getAttribute('data-default-section') || 'contact';
        var storageKey = 'admin_site_settings_tab';
        var sectionKeys = {};

        try {
            sectionKeys = JSON.parse(container.getAttribute('data-section-keys') || '{}');
        } catch (e) {
            sectionKeys = {};
        }

        function activateSection(sectionId, persist) {
            if (!sectionId) return;

            navItems.forEach(function (item) {
                var isActive = item.getAttribute('data-section') === sectionId;
                item.classList.toggle('is-active', isActive);
                item.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            panels.forEach(function (panel) {
                panel.classList.toggle('is-active', panel.getAttribute('data-section') === sectionId);
            });

            if (activeInput) activeInput.value = sectionId;

            if (persist !== false) {
                try { localStorage.setItem(storageKey, sectionId); } catch (e) { /* ignore */ }
            }
        }

        navItems.forEach(function (item) {
            item.addEventListener('click', function () {
                activateSection(item.getAttribute('data-section'));
            });
        });

        var initialSection = container.getAttribute('data-active-section');
        if (!initialSection) {
            try { initialSection = localStorage.getItem(storageKey); } catch (e) { /* ignore */ }
        }
        if (!initialSection || !container.querySelector('.settings-panel[data-section="' + initialSection + '"]')) {
            initialSection = defaultSection;
        }

        var errorFields = container.querySelectorAll('.is-invalid[name]');
        if (errorFields.length) {
            var errorName = errorFields[0].getAttribute('name');
            Object.keys(sectionKeys).some(function (sectionId) {
                var keys = sectionKeys[sectionId] || [];
                if (keys.indexOf(errorName) !== -1) {
                    initialSection = sectionId;
                    return true;
                }
                return false;
            });
        }

        activateSection(initialSection, false);
    }

    window.adminUiToast = function (message, type, containerId) {
        var container = document.getElementById(containerId || 'adminToastContainer');
        if (!container || typeof bootstrap === 'undefined') return;

        var bgClass = type === 'success' ? 'text-bg-success' : 'text-bg-danger';
        var icon = type === 'success' ? 'ri-checkbox-circle-line' : 'ri-error-warning-line';

        var toastEl = document.createElement('div');
        toastEl.className = 'toast align-items-center border-0 shadow ' + bgClass;
        toastEl.setAttribute('role', 'alert');
        toastEl.innerHTML =
            '<div class="d-flex">' +
                '<div class="toast-body d-flex align-items-center gap-2">' +
                    '<i class="' + icon + ' fs-18"></i>' + message +
                '</div>' +
                '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>' +
            '</div>';

        container.appendChild(toastEl);
        var toast = new bootstrap.Toast(toastEl, { delay: 3500 });
        toast.show();
        toastEl.addEventListener('hidden.bs.toast', function () { toastEl.remove(); });
    };

    function initDynamicConfirmModals() {
        document.querySelectorAll('.modal-confirm[data-dynamic="true"]').forEach(function (modalEl) {
            modalEl.addEventListener('show.bs.modal', function (event) {
                var trigger = event.relatedTarget;
                if (!trigger) return;

                var form = modalEl.querySelector('form');
                if (form && trigger.dataset.confirmAction) {
                    form.action = trigger.dataset.confirmAction;
                }

                var subjectEl = modalEl.querySelector('[data-confirm-subject]');
                if (subjectEl && trigger.dataset.confirmSubject !== undefined) {
                    subjectEl.textContent = trigger.dataset.confirmSubject;
                }

                var metaEl = modalEl.querySelector('[data-confirm-subject-meta]');
                if (metaEl && trigger.dataset.confirmSubjectMeta !== undefined) {
                    metaEl.textContent = trigger.dataset.confirmSubjectMeta;
                }

                var messageEl = modalEl.querySelector('[data-confirm-message]');
                if (messageEl && trigger.dataset.confirmMessage !== undefined) {
                    messageEl.textContent = trigger.dataset.confirmMessage;
                }
            });
        });
    }

    /**
     * ربط زر حذف بمودال ديناميكي واحد في الصفحة.
     * adminConfirmModal.bindTrigger(button, { modalId, action, subject, subjectMeta, message })
     */
    window.adminConfirmModal = {
        bindTrigger: function (button, opts) {
            if (!button || !opts || !opts.modalId) return;
            button.addEventListener('click', function () {
                if (opts.action) button.dataset.confirmAction = opts.action;
                if (opts.subject) button.dataset.confirmSubject = opts.subject;
                if (opts.subjectMeta) button.dataset.confirmSubjectMeta = opts.subjectMeta;
                if (opts.message) button.dataset.confirmMessage = opts.message;
            });
        }
    };

    function getToggleModalConfig(modalEl, isActive) {
        var configHost = modalEl ? modalEl.closest('[data-toggle-status-modal]') : null;
        var fallbackActivate = {
            variant: 'success',
            title: 'تأكيد التفعيل',
            message: 'سيتمكن من الدخول واستخدام النظام.',
            confirmText: 'نعم، فعّل',
            confirmIcon: 'ri-shut-down-line',
            icon: 'ri-shut-down-line'
        };
        var fallbackDeactivate = {
            variant: 'warning',
            title: 'تأكيد الإيقاف',
            message: 'لن يتمكن من الدخول إلى النظام حتى إعادة التفعيل.',
            confirmText: 'نعم، أوقف التفعيل',
            confirmIcon: 'ri-shut-down-line',
            icon: 'ri-shut-down-line'
        };

        if (!configHost) {
            return isActive ? fallbackActivate : fallbackDeactivate;
        }

        if (isActive) {
            return {
                variant: 'success',
                title: configHost.dataset.activateTitle || fallbackActivate.title,
                message: configHost.dataset.activateMessage || fallbackActivate.message,
                confirmText: configHost.dataset.activateConfirm || fallbackActivate.confirmText,
                confirmIcon: 'ri-shut-down-line',
                icon: 'ri-shut-down-line'
            };
        }

        return {
            variant: 'warning',
            title: configHost.dataset.deactivateTitle || fallbackDeactivate.title,
            message: configHost.dataset.deactivateMessage || fallbackDeactivate.message,
            confirmText: configHost.dataset.deactivateConfirm || fallbackDeactivate.confirmText,
            confirmIcon: 'ri-shut-down-line',
            icon: 'ri-shut-down-line'
        };
    }

    function initActivationToggles(root, baseUrl, modalSelector) {
        var scope = root || document;
        var urlBase = (baseUrl || '').replace(/\/$/, '');

        scope.querySelectorAll('.toggle-status:not([data-toggle-bound])').forEach(function (toggle) {
            toggle.dataset.toggleBound = '1';
            toggle.addEventListener('change', function () {
                var entityId = this.dataset.entityId || this.dataset.userId;
                var isActive = this.checked;
                var pill = this.closest('.activation-pill');
                var label = pill ? pill.querySelector('.toggle-label') : null;
                var self = this;
                var activeLabel = self.dataset.activeLabel || 'نشط';
                var inactiveLabel = self.dataset.inactiveLabel || 'غير نشط';

                self.checked = !isActive;

                var config = getToggleModalConfig(
                    modalSelector ? document.querySelector(modalSelector) : null,
                    isActive
                );

                function runToggleRequest() {
                    self.checked = isActive;
                    self.disabled = true;

                    var modalEl = modalSelector ? document.querySelector(modalSelector) : null;
                    var confirmBtn = modalEl ? modalEl.querySelector('[data-confirm-submit]') : null;
                    var defaultConfirmHtml = confirmBtn ? confirmBtn.innerHTML : '';

                    if (confirmBtn) {
                        confirmBtn.disabled = true;
                        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري التحديث...';
                    }

                    fetch(urlBase + '/' + entityId + '/toggle-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ is_active: isActive })
                    })
                    .then(function (r) {
                        return r.json().then(function (data) {
                            if (!r.ok) throw data;
                            return data;
                        });
                    })
                    .then(function (data) {
                        if (data.success) {
                            var active = Boolean(data.is_active);
                            if (label) {
                                label.textContent = data.status_label || (active ? activeLabel : inactiveLabel);
                            }
                            self.checked = active;
                            if (pill) {
                                pill.classList.toggle('activation-pill--active', active);
                                pill.classList.toggle('activation-pill--inactive', !active);
                            }
                            window.adminUiToast(data.message || 'تم التحديث بنجاح', 'success');
                        } else {
                            self.checked = !isActive;
                            window.adminUiToast(data.message || 'حدث خطأ', 'error');
                        }
                    })
                    .catch(function (err) {
                        self.checked = !isActive;
                        window.adminUiToast((err && err.message) || 'حدث خطأ أثناء التحديث', 'error');
                    })
                    .finally(function () {
                        self.disabled = false;
                        if (confirmBtn) {
                            confirmBtn.disabled = false;
                            confirmBtn.innerHTML = defaultConfirmHtml;
                        }
                        if (modalEl && typeof bootstrap !== 'undefined') {
                            var instance = bootstrap.Modal.getInstance(modalEl);
                            if (instance) instance.hide();
                        }
                    });
                }

                if (modalSelector && window.adminUiAjaxConfirm) {
                    window.adminUiAjaxConfirm(modalSelector, {
                        variant: config.variant,
                        title: config.title,
                        message: config.message,
                        subject: self.dataset.subjectName || self.dataset.userName || '',
                        subjectMeta: self.dataset.subjectMeta || self.dataset.userEmail || '',
                        confirmText: config.confirmText,
                        confirmIcon: config.confirmIcon,
                        icon: config.icon,
                        onCancel: function () {
                            self.checked = !isActive;
                        },
                        onConfirm: runToggleRequest
                    });
                    return;
                }

                if (confirm(config.title + '?')) {
                    runToggleRequest();
                } else {
                    self.checked = !isActive;
                }
            });
        });
    }

    var ajaxConfirmPending = { onConfirm: null, onCancel: null, confirmed: false };

    function setAjaxConfirmModalVariant(modalEl, variant) {
        ['danger', 'warning', 'success', 'primary', 'info'].forEach(function (v) {
            modalEl.classList.remove('modal-confirm--' + v);
        });
        modalEl.classList.add('modal-confirm--' + variant);
    }

    function initAjaxConfirmModals() {
        document.querySelectorAll('[data-ajax-confirm="true"]:not([data-ajax-confirm-init])').forEach(function (modalEl) {
            modalEl.dataset.ajaxConfirmInit = '1';

            modalEl.addEventListener('hidden.bs.modal', function () {
                if (!ajaxConfirmPending.confirmed && ajaxConfirmPending.onCancel) {
                    ajaxConfirmPending.onCancel();
                }
                ajaxConfirmPending = { onConfirm: null, onCancel: null, confirmed: false };

                var confirmBtn = modalEl.querySelector('[data-confirm-submit]');
                if (confirmBtn) confirmBtn.disabled = false;
            });

            var confirmBtn = modalEl.querySelector('[data-confirm-submit]');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function () {
                    ajaxConfirmPending.confirmed = true;
                    if (ajaxConfirmPending.onConfirm) ajaxConfirmPending.onConfirm();
                });
            }
        });
    }

    window.adminUiAjaxConfirm = function (modalSelector, options) {
        options = options || {};
        var modalEl = document.querySelector(modalSelector);
        if (!modalEl || typeof bootstrap === 'undefined') return;

        initAjaxConfirmModals();

        var variant = options.variant || 'primary';
        setAjaxConfirmModalVariant(modalEl, variant);

        var titleEl = modalEl.querySelector('[data-confirm-title]');
        var messageEl = modalEl.querySelector('[data-confirm-message]');
        var subjectEl = modalEl.querySelector('[data-confirm-subject]');
        var metaEl = modalEl.querySelector('[data-confirm-subject-meta]');
        var iconWrap = modalEl.querySelector('[data-confirm-icon-wrap]');
        var iconEl = modalEl.querySelector('[data-confirm-icon]');
        var confirmBtn = modalEl.querySelector('[data-confirm-submit]');
        var submitIcon = modalEl.querySelector('[data-confirm-submit-icon]');
        var submitText = modalEl.querySelector('[data-confirm-submit-text]');

        if (titleEl) {
            titleEl.textContent = options.title || '';
            titleEl.className = 'modal-confirm__title mb-2 fw-bold text-' + variant;
        }
        if (messageEl) messageEl.textContent = options.message || '';
        if (subjectEl) subjectEl.textContent = options.subject || '';
        if (metaEl) {
            metaEl.textContent = options.subjectMeta || '';
            metaEl.style.display = options.subjectMeta ? '' : 'none';
        }
        if (iconWrap) {
            iconWrap.className = 'action-modal-icon action-modal-icon--' + variant;
        }
        if (iconEl && options.icon) iconEl.className = options.icon;
        if (confirmBtn) {
            confirmBtn.className = 'btn btn-' + variant + ' btn-lg px-4 modal-confirm__submit';
            confirmBtn.disabled = false;
        }
        if (submitIcon && options.confirmIcon) submitIcon.className = options.confirmIcon + ' me-1';
        if (submitText) submitText.textContent = options.confirmText || 'تأكيد';

        ajaxConfirmPending.onConfirm = options.onConfirm || null;
        ajaxConfirmPending.onCancel = options.onCancel || null;
        ajaxConfirmPending.confirmed = false;

        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    };

    window.adminUiRefresh = function (root, options) {
        options = options || {};
        initCopyButtons();
        initActionButtonTooltips();
        initPasswordToggleButtons();
        initDynamicConfirmModals();
        initActivationToggles(root, options.toggleUrl, options.toggleModal);
    };

    document.addEventListener('DOMContentLoaded', function () {
        initPasswordToggleButtons();
        initActionButtonTooltips();
        initCopyButtons();
        initDynamicConfirmModals();
        initAjaxConfirmModals();
        initSettingsTabs(document.getElementById('siteSettingsTabs'));
    });
})();
