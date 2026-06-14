/**
 * TinyMCE for question bank — question text & explanation fields.
 */
(function (window) {
    'use strict';

    var SELECTOR = 'textarea.qb-rich-text';

    function initQuestionRichText() {
        if (typeof tinymce === 'undefined') {
            setTimeout(initQuestionRichText, 100);
            return;
        }

        var fields = document.querySelectorAll(SELECTOR);
        if (!fields.length) {
            return;
        }

        var hasEditor = false;
        fields.forEach(function (el) {
            if (el.dataset.tinymceInit === '1') {
                hasEditor = true;
            }
        });
        if (hasEditor) {
            return;
        }

        fields.forEach(function (el) {
            el.dataset.tinymceInit = '1';
        });

        tinymce.init({
            selector: SELECTOR,
            height: 320,
            directionality: 'rtl',
            language: 'ar',
            language_url: 'https://cdn.jsdelivr.net/npm/tinymce-i18n@latest/langs6/ar.js',
            promotion: false,
            branding: false,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code codesample fullscreen insertdatetime media table help wordcount emoticons directionality',
            toolbar: 'undo redo | blocks | bold italic underline | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | link image media table | codesample code | fullscreen | help',
            menubar: 'edit view insert format tools table help',
            content_style: 'body { font-family: "Segoe UI", Tahoma, Arial, sans-serif; font-size: 14px; direction: rtl; }',
            relative_urls: false,
            remove_script_host: false,
            image_advtab: true,
            paste_data_images: true,
            codesample_global_prismjs: true,
            codesample_languages: [
                { text: 'HTML/XML', value: 'markup' },
                { text: 'JavaScript', value: 'javascript' },
                { text: 'CSS', value: 'css' },
                { text: 'PHP', value: 'php' },
                { text: 'Python', value: 'python' },
                { text: 'SQL', value: 'sql' },
                { text: 'JSON', value: 'json' }
            ],
            setup: function (editor) {
                editor.on('change keyup', function () {
                    editor.save();
                });
            }
        }).catch(function (err) {
            console.error('Question bank TinyMCE error:', err);
        });
    }

    function bindFormSync() {
        document.querySelectorAll('form').forEach(function (form) {
            if (form.dataset.qbRichTextBound === '1') {
                return;
            }
            if (!form.querySelector(SELECTOR)) {
                return;
            }
            form.dataset.qbRichTextBound = '1';
            form.addEventListener('submit', function () {
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                }
            });
        });
    }

    window.AdminQuestionRichText = {
        init: initQuestionRichText
    };

    document.addEventListener('DOMContentLoaded', function () {
        if (!document.querySelector(SELECTOR)) {
            return;
        }
        setTimeout(function () {
            initQuestionRichText();
            bindFormSync();
        }, 150);
    });
})(window);
