<style>
    .admin-ui .student-course-thumb {
        width: 5.5rem;
        height: 5.5rem;
        border-radius: 0.75rem;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.12), rgba(16, 185, 129, 0.12));
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--default-border, #e2e8f0);
    }

    .admin-ui .student-course-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .admin-ui .student-course-thumb i {
        font-size: 1.75rem;
        color: rgba(99, 102, 241, 0.55);
    }

    .admin-ui .student-enrollment-card {
        border: 1px solid var(--default-border, #e2e8f0);
        border-radius: 1rem;
        overflow: hidden;
        background: var(--custom-white, #fff);
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }

    .admin-ui .student-enrollment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
    }

    .admin-ui .student-enrollment-card__thumb {
        position: relative;
        height: 140px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(16, 185, 129, 0.08));
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .admin-ui .student-enrollment-card__thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .admin-ui .student-enrollment-card__thumb-icon {
        font-size: 2.25rem;
        color: rgba(99, 102, 241, 0.55);
    }

    .admin-ui .student-enrollment-card__status {
        position: absolute;
        top: 0.75rem;
        inset-inline-start: 0.75rem;
    }

    .admin-ui .student-enrollment-card__body {
        padding: 1rem 1.1rem 1.1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .admin-ui .student-enrollment-card__category {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6366f1;
        margin-bottom: 0.35rem;
    }

    .admin-ui .student-enrollment-card__title {
        font-size: 0.95rem;
        font-weight: 700;
        line-height: 1.45;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .admin-ui .student-enrollment-card__title a {
        color: inherit;
        text-decoration: none;
    }

    .admin-ui .student-enrollment-card__title a:hover {
        color: #6366f1;
    }

    .admin-ui .student-enrollment-card__meta {
        font-size: 0.78rem;
        color: var(--text-muted, #64748b);
        margin-bottom: 0.85rem;
    }

    .admin-ui .student-enrollment-card__progress-label {
        display: flex;
        justify-content: space-between;
        font-size: 0.78rem;
        margin-bottom: 0.35rem;
    }

    .admin-ui .student-enrollment-card__footer {
        margin-top: auto;
        padding-top: 0.75rem;
        border-top: 1px dashed var(--default-border, #e2e8f0);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .admin-ui .student-course-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .admin-ui .student-course-stat {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.75rem;
        border-radius: 0.5rem;
        color: #fff;
        font-size: 0.75rem;
        font-weight: 500;
        line-height: 1;
        white-space: nowrap;
    }

    .admin-ui .student-course-stat i {
        font-size: 0.95rem;
        opacity: 0.9;
    }

    .admin-ui .student-course-stat__value {
        font-weight: 800;
        font-size: 0.85rem;
    }

    .admin-ui .student-course-stat__label {
        opacity: 0.88;
        font-size: 0.72rem;
    }

    .admin-ui .student-course-stat--purple { background: linear-gradient(135deg, #4f46e5, #6366f1); }
    .admin-ui .student-course-stat--green  { background: linear-gradient(135deg, #059669, #10b981); }
    .admin-ui .student-course-stat--cyan   { background: linear-gradient(135deg, #0284c7, #0ea5e9); }
    .admin-ui .student-course-stat--orange { background: linear-gradient(135deg, #ea580c, #f59e0b); }

    .admin-ui .student-curriculum-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.9rem 1.15rem;
        border-bottom: 1px solid var(--default-border, #e2e8f0);
        text-decoration: none;
        color: var(--default-text-color, #334155);
        transition: background 0.15s ease, color 0.15s ease;
    }

    .admin-ui .student-curriculum-accordion .student-curriculum-item:nth-child(odd) {
        background: rgba(148, 163, 184, 0.05);
    }

    .admin-ui .student-curriculum-item:last-child {
        border-bottom: none;
    }

    .admin-ui .student-curriculum-item:hover {
        background: #eef2ff;
        color: #1e293b;
    }

    .admin-ui .student-curriculum-item:hover .student-curriculum-item__meta {
        color: #64748b;
    }

    .admin-ui .student-curriculum-item__icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.95rem;
    }

    .admin-ui .student-curriculum-item__icon--video { background: #eef2ff; color: #4f46e5; }
    .admin-ui .student-curriculum-item__icon--quiz { background: #ecfdf5; color: #059669; }
    .admin-ui .student-curriculum-item__icon--module { background: #fffbeb; color: #d97706; }
    .admin-ui .student-curriculum-item__icon--file { background: #f1f5f9; color: #64748b; }
    .admin-ui .student-curriculum-item__icon--done { background: #ecfdf5; color: #059669; }

    .admin-ui .student-curriculum-item__title {
        flex: 1;
        font-weight: 600;
        font-size: 0.875rem;
        line-height: 1.4;
    }

    .admin-ui .student-curriculum-item__meta {
        font-size: 0.75rem;
        color: var(--text-muted, #64748b);
        white-space: nowrap;
    }

    .admin-ui .student-curriculum-accordion {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        padding: 1rem;
    }

    .admin-ui .student-curriculum-accordion .accordion-item {
        border: 1px solid var(--default-border, #e2e8f0) !important;
        border-radius: 0.75rem !important;
        overflow: hidden;
        margin-bottom: 0 !important;
        background: var(--custom-white, #fff);
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
    }

    .admin-ui .student-curriculum-accordion .accordion-button {
        padding: 1rem 1.15rem;
        gap: 0.5rem;
        box-shadow: none;
    }

    .admin-ui .student-curriculum-accordion .accordion-button:not(.collapsed) {
        border-bottom: 1px solid var(--default-border, #e2e8f0);
        border-radius: 0.75rem 0.75rem 0 0 !important;
    }

    .admin-ui .student-curriculum-accordion .accordion-collapse {
        border-top: 0;
    }

    .admin-ui .form-accordion .accordion-body.p-0 {
        padding: 0 !important;
        border-top: 1px solid var(--default-border, #e2e8f0);
    }

    /* ── Dark mode ── */
    [data-theme-mode=dark] .admin-ui .student-course-thumb {
        background: rgba(99, 102, 241, 0.12);
        border-color: var(--default-border);
    }

    [data-theme-mode=dark] .admin-ui .student-course-thumb i {
        color: #a5b4fc;
    }

    [data-theme-mode=dark] .admin-ui .student-enrollment-card {
        background: var(--custom-white);
        border-color: var(--default-border);
        box-shadow: none;
    }

    [data-theme-mode=dark] .admin-ui .student-enrollment-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
    }

    [data-theme-mode=dark] .admin-ui .student-enrollment-card__thumb {
        background: rgba(99, 102, 241, 0.1);
    }

    [data-theme-mode=dark] .admin-ui .student-enrollment-card__thumb-icon {
        color: #a5b4fc;
    }

    [data-theme-mode=dark] .admin-ui .student-enrollment-card__category {
        color: #a5b4fc;
    }

    [data-theme-mode=dark] .admin-ui .student-enrollment-card__title a:hover {
        color: #a5b4fc;
    }

    [data-theme-mode=dark] .admin-ui .student-enrollment-card__footer {
        border-top-color: var(--default-border);
    }

    [data-theme-mode=dark] .admin-ui .student-curriculum-item {
        color: var(--default-text-color);
        border-bottom-color: var(--default-border);
    }

    [data-theme-mode=dark] .admin-ui .student-curriculum-item:hover {
        background: rgba(99, 102, 241, 0.12);
        color: var(--default-text-color);
    }

    [data-theme-mode=dark] .admin-ui .student-curriculum-item:focus,
    [data-theme-mode=dark] .admin-ui .student-curriculum-item:active {
        background: rgba(99, 102, 241, 0.12);
        color: var(--default-text-color);
    }

    [data-theme-mode=dark] .admin-ui .student-curriculum-item:hover .student-curriculum-item__title {
        color: #e2e8f0;
    }

    [data-theme-mode=dark] .admin-ui .student-curriculum-item__icon--video {
        background: rgba(99, 102, 241, 0.2);
        color: #a5b4fc;
    }

    [data-theme-mode=dark] .admin-ui .student-curriculum-item__icon--quiz {
        background: rgba(16, 185, 129, 0.2);
        color: #6ee7b7;
    }

    [data-theme-mode=dark] .admin-ui .student-curriculum-item__icon--module {
        background: rgba(245, 158, 11, 0.2);
        color: #fde68a;
    }

    [data-theme-mode=dark] .admin-ui .student-curriculum-item__icon--file {
        background: rgba(148, 163, 184, 0.15);
        color: #cbd5e1;
    }

    [data-theme-mode=dark] .admin-ui .student-curriculum-item__icon--done {
        background: rgba(16, 185, 129, 0.2);
        color: #6ee7b7;
    }

    [data-theme-mode=dark] .admin-ui .student-curriculum-item__meta {
        color: var(--text-muted);
    }

    [data-theme-mode=dark] .admin-ui .student-curriculum-accordion .accordion-item {
        background: rgba(255, 255, 255, 0.03);
        border-color: var(--default-border) !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    [data-theme-mode=dark] .admin-ui .student-curriculum-accordion .accordion-button:not(.collapsed) {
        border-bottom-color: var(--default-border);
    }

    [data-theme-mode=dark] .admin-ui .form-accordion .accordion-body.p-0 {
        background: transparent;
        border-top-color: var(--default-border);
    }

    [data-theme-mode=dark] .admin-ui .student-curriculum-accordion .student-curriculum-item:nth-child(odd) {
        background: rgba(255, 255, 255, 0.02);
    }

    [data-theme-mode=dark] .admin-ui .student-curriculum-item:hover,
    [data-theme-mode=dark] .admin-ui .student-curriculum-accordion .student-curriculum-item:nth-child(odd):hover {
        background: rgba(99, 102, 241, 0.12);
    }
</style>
