<style>
    body.student-lesson-page {
        background: #f1f5f9;
        min-height: 100vh;
    }

    .student-lesson-viewer {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .student-lesson-topbar {
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.75rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .student-lesson-topbar__back {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: #475569;
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 600;
    }

    .student-lesson-topbar__back:hover {
        color: rgb(var(--primary-rgb, 79, 70, 229));
    }

    .student-lesson-topbar__title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        text-align: center;
        flex: 1;
        min-width: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .student-lesson-topbar__progress {
        font-size: 0.78rem;
        color: #64748b;
        white-space: nowrap;
    }

    .student-lesson-body {
        display: flex;
        flex: 1;
        min-height: 0;
    }

    .student-lesson-sidebar {
        width: 340px;
        flex-shrink: 0;
        background: #fff;
        border-inline-start: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 57px);
        position: sticky;
        top: 57px;
    }

    .student-lesson-sidebar__header {
        padding: 1rem 1.1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .student-lesson-sidebar__content {
        flex: 1;
        overflow-y: auto;
        padding: 0.5rem 0;
    }

    .student-lesson-main {
        flex: 1;
        min-width: 0;
        padding: 1.25rem;
        overflow-y: auto;
    }

    .student-lesson-player {
        background: #0f172a;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.15);
    }

    .student-lesson-player__inner {
        aspect-ratio: 16 / 9;
        background: #000;
    }

    .student-lesson-player iframe,
    .student-lesson-player video {
        width: 100%;
        height: 100%;
        border: 0;
        display: block;
    }

    .student-lesson-player__placeholder {
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        gap: 0.75rem;
    }

    .student-lesson-player__placeholder i {
        font-size: 3rem;
        opacity: 0.5;
    }

    .student-lesson-meta {
        margin-top: 1.25rem;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.25rem;
    }

    .student-lesson-syllabus-section {
        border-bottom: 1px solid #f1f5f9;
    }

    .student-lesson-syllabus-section__toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 0.85rem 1.1rem;
        background: transparent;
        border: none;
        text-align: start;
        font-weight: 600;
        font-size: 0.85rem;
        color: #334155;
        cursor: pointer;
    }

    .student-lesson-syllabus-section__toggle:hover {
        background: #f8fafc;
    }

    .student-lesson-syllabus-item {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.7rem 1.1rem 0.7rem 1.35rem;
        text-decoration: none;
        color: #475569;
        font-size: 0.82rem;
        border-inline-start: 3px solid transparent;
        transition: background 0.15s, border-color 0.15s;
    }

    .student-lesson-syllabus-item:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .student-lesson-syllabus-item.is-active {
        background: rgba(var(--primary-rgb, 79, 70, 229), 0.08);
        border-inline-start-color: rgb(var(--primary-rgb, 79, 70, 229));
        color: rgb(var(--primary-rgb, 79, 70, 229));
        font-weight: 600;
    }

    .student-lesson-syllabus-item.is-done .student-lesson-syllabus-item__icon {
        color: #10b981;
    }

    .student-lesson-syllabus-item__icon {
        flex-shrink: 0;
        font-size: 1rem;
        color: #94a3b8;
    }

    .student-lesson-syllabus-item__title {
        flex: 1;
        line-height: 1.35;
    }

    .student-lesson-syllabus-item__duration {
        font-size: 0.72rem;
        color: #94a3b8;
        white-space: nowrap;
    }

    .student-lesson-sidebar-toggle {
        display: none;
        position: fixed;
        bottom: 1.25rem;
        inset-inline-start: 1.25rem;
        z-index: 200;
        border-radius: 999px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.2);
    }

    @media (max-width: 991.98px) {
        .student-lesson-body {
            flex-direction: column;
        }

        .student-lesson-sidebar {
            display: none;
            width: 100%;
            max-height: none;
            position: fixed;
            inset: 0;
            top: 57px;
            z-index: 150;
        }

        .student-lesson-sidebar.is-open {
            display: flex;
        }

        .student-lesson-sidebar-toggle {
            display: inline-flex;
        }

        .student-lesson-main {
            padding: 1rem;
        }
    }

    /* ── Dark mode (lesson viewer) ── */
    [data-theme-mode=dark] body.student-lesson-page {
        background: rgb(36, 43, 57);
        color: var(--default-text-color, #e2e8f0);
    }

    [data-theme-mode=dark] .student-lesson-topbar {
        background: rgb(var(--body-bg-rgb, 25, 32, 47));
        border-bottom-color: var(--default-border, rgba(255, 255, 255, 0.1));
    }

    [data-theme-mode=dark] .student-lesson-topbar__back {
        color: var(--text-muted, #94a3b8);
    }

    [data-theme-mode=dark] .student-lesson-topbar__back:hover {
        color: #a5b4fc;
    }

    [data-theme-mode=dark] .student-lesson-topbar__title {
        color: var(--default-text-color, #e2e8f0);
    }

    [data-theme-mode=dark] .student-lesson-topbar__progress {
        color: var(--text-muted, #94a3b8);
    }

    [data-theme-mode=dark] .student-lesson-sidebar {
        background: rgb(var(--body-bg-rgb, 25, 32, 47));
        border-inline-start-color: var(--default-border, rgba(255, 255, 255, 0.1));
    }

    [data-theme-mode=dark] .student-lesson-sidebar__header {
        border-bottom-color: var(--default-border, rgba(255, 255, 255, 0.1));
    }

    [data-theme-mode=dark] .student-lesson-meta {
        background: rgb(var(--body-bg-rgb, 25, 32, 47));
        border-color: var(--default-border, rgba(255, 255, 255, 0.1));
    }

    [data-theme-mode=dark] .student-lesson-syllabus-section {
        border-bottom-color: var(--default-border, rgba(255, 255, 255, 0.08));
    }

    [data-theme-mode=dark] .student-lesson-syllabus-section__toggle {
        color: var(--default-text-color, #e2e8f0);
    }

    [data-theme-mode=dark] .student-lesson-syllabus-section__toggle:hover {
        background: rgba(255, 255, 255, 0.04);
    }

    [data-theme-mode=dark] .student-lesson-syllabus-item {
        color: var(--text-muted, #94a3b8);
    }

    [data-theme-mode=dark] .student-lesson-syllabus-item:hover {
        background: rgba(99, 102, 241, 0.12);
        color: var(--default-text-color, #e2e8f0);
    }

    [data-theme-mode=dark] .student-lesson-syllabus-item.is-active {
        background: rgba(99, 102, 241, 0.18);
        border-inline-start-color: #818cf8;
        color: #c7d2fe;
    }

    [data-theme-mode=dark] .student-lesson-syllabus-item.is-done .student-lesson-syllabus-item__icon {
        color: #6ee7b7;
    }

    [data-theme-mode=dark] .student-lesson-syllabus-item__icon {
        color: #64748b;
    }

    [data-theme-mode=dark] .student-lesson-syllabus-item__duration {
        color: var(--text-muted, #94a3b8);
    }
</style>
