(function () {
    'use strict';

    function initSortable() {
        var list = document.getElementById('heroSlidesSortable');
        if (!list || typeof Sortable === 'undefined') return;

        Sortable.create(list, {
            handle: '.ri-draggable',
            animation: 150,
            onEnd: function () {
                var order = Array.from(list.querySelectorAll('[data-id]')).map(function (el) {
                    return parseInt(el.dataset.id, 10);
                });
                fetch(list.dataset.reorderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ order: order }),
                }).then(function (r) { return r.json(); }).then(function (data) {
                    if (data.success && window.adminUiToast) {
                        window.adminUiToast(data.message, 'success');
                    }
                });
            },
        });
    }

    function initToggles() {
        document.querySelectorAll('.hero-slide-toggle').forEach(function (btn) {
            btn.addEventListener('click', function () {
                fetch(btn.dataset.url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                }).then(function (r) { return r.json(); }).then(function (data) {
                    if (data.success) {
                        if (window.adminUiToast) window.adminUiToast(data.message, 'success');
                        location.reload();
                    }
                });
            });
        });
    }

    function toggleFields(selectId, map) {
        var select = document.getElementById(selectId);
        if (!select) return;

        function apply() {
            var val = select.value;
            Object.keys(map).forEach(function (key) {
                document.querySelectorAll(map[key]).forEach(function (el) {
                    el.style.display = key === val ? '' : 'none';
                });
            });
        }

        select.addEventListener('change', apply);
        apply();
    }

    function initForm() {
        function applyBackground() {
            var type = document.getElementById('background_type');
            if (!type) return;
            document.querySelectorAll('.bg-field').forEach(function (el) {
                if (type.value === 'theme') {
                    el.style.display = 'none';
                    return;
                }
                var match = el.classList.contains('bg-' + type.value);
                el.style.display = match ? '' : 'none';
            });
        }

        var bgType = document.getElementById('background_type');
        if (bgType) {
            bgType.addEventListener('change', applyBackground);
            applyBackground();
        }

        var visualType = document.getElementById('visual_type');
        if (visualType) {
            var visualMap = {
                image: ['.visual-field.visual-image'],
                icon: ['.visual-field.visual-icon'],
                main: ['.visual-field.visual-main', '.visual-field.visual-icon'],
                code: ['.visual-field.visual-code'],
                design: ['.visual-field.visual-design', '.visual-field.visual-icon'],
                ai: ['.visual-field.visual-ai', '.visual-field.visual-icon'],
            };
            function applyVisual() {
                var show = visualMap[visualType.value] || [];
                document.querySelectorAll('.visual-field').forEach(function (el) {
                    if (visualType.value === 'hidden') {
                        el.style.display = 'none';
                        return;
                    }
                    var visible = show.some(function (sel) { return el.matches(sel); });
                    el.style.display = visible ? '' : 'none';
                });
            }
            visualType.addEventListener('change', applyVisual);
            applyVisual();
        }

        var headingMode = document.getElementById('heading_mode');
        if (headingMode) {
            function applyHeading() {
                document.querySelectorAll('.heading-static').forEach(function (el) {
                    el.style.display = headingMode.value === 'static' ? '' : 'none';
                });
                document.querySelectorAll('.heading-typing').forEach(function (el) {
                    el.style.display = headingMode.value === 'typing' ? '' : 'none';
                });
            }
            headingMode.addEventListener('change', applyHeading);
            applyHeading();
        }

        var aiTags = document.querySelector('[name="visual_extras_ai_tags"]');
        if (aiTags) {
            var form = document.getElementById('heroSlideForm');
            form && form.addEventListener('submit', function () {
                var tags = aiTags.value.split(',').map(function (t) { return t.trim(); }).filter(Boolean);
                tags.forEach(function (tag, i) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'visual_extras[ai_tags][' + i + ']';
                    input.value = tag;
                    form.appendChild(input);
                });
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initSortable();
        initToggles();
        initForm();
    });
})();
