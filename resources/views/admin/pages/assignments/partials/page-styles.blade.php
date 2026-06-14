<style>
    @keyframes dashboardFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes dashboardStaggerIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .dashboard-fade-in {
        animation: dashboardFadeIn 0.45s ease both;
    }

    .dashboard-stagger-item {
        animation: dashboardStaggerIn 0.45s ease both;
        animation-delay: var(--stagger-delay, 0ms);
    }

    .group-show-hero {
        padding: 1.35rem 1.5rem;
        border-radius: 1rem;
        border: 1px solid var(--default-border, #e2e8f0);
        background: linear-gradient(135deg, rgba(var(--primary-rgb), 0.06) 0%, rgba(var(--primary-rgb), 0.02) 100%);
    }

    .group-show-hero__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: rgb(var(--primary-rgb));
        margin-bottom: 0.5rem;
    }

    .group-show-hero__title {
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--default-text-color);
    }

    .group-show-hero__desc {
        color: var(--text-muted);
        line-height: 1.6;
        max-width: 52rem;
    }

    .group-show-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        justify-content: flex-end;
    }

    .group-show-action {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.55rem 0.9rem;
        border-radius: 0.75rem;
        border: 1px solid var(--default-border, #e2e8f0);
        background: var(--custom-white, #fff);
        color: var(--default-text-color);
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 600;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .group-show-action:hover {
        transform: translateY(-1px);
        border-color: rgba(var(--primary-rgb), 0.25);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
        color: var(--default-text-color);
    }

    .group-show-action--primary {
        background: rgba(var(--primary-rgb), 0.1);
        border-color: rgba(var(--primary-rgb), 0.2);
        color: rgb(var(--primary-rgb));
    }

    .group-show-action--success {
        background: rgba(25, 135, 84, 0.1);
        border-color: rgba(25, 135, 84, 0.2);
        color: #198754;
    }

    .group-show-action--warning {
        background: rgba(255, 193, 7, 0.12);
        border-color: rgba(255, 193, 7, 0.25);
        color: #b78103;
    }

    .group-show-action--info {
        background: rgba(13, 202, 240, 0.1);
        border-color: rgba(13, 202, 240, 0.2);
        color: #0aa2c0;
    }

    .group-show-action__icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .group-show-members-card {
        border-radius: 1rem;
        overflow: hidden;
    }

    .group-show-members-card__title {
        font-weight: 700;
        color: var(--default-text-color);
    }

    .group-show-members-card__count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 1.6rem;
        height: 1.6rem;
        padding: 0 0.45rem;
        margin-inline-start: 0.35rem;
        border-radius: 999px;
        background: rgba(var(--primary-rgb), 0.1);
        color: rgb(var(--primary-rgb));
        font-size: 0.75rem;
        font-weight: 700;
    }

    .group-show-filters .form-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--text-muted);
    }

    .group-show-table thead th {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--text-muted);
        white-space: nowrap;
    }

    .group-show-chip {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.55rem;
        border-radius: 999px;
        background: rgba(var(--primary-rgb), 0.1);
        color: rgb(var(--primary-rgb));
        font-size: 0.75rem;
        font-weight: 600;
    }

    .group-show-chip--sm {
        font-size: 0.72rem;
        padding: 0.2rem 0.45rem;
    }

    .assignments-section-icon {
        width: 2rem;
        height: 2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.65rem;
        background: rgba(var(--primary-rgb), 0.1);
        color: rgb(var(--primary-rgb));
        margin-inline-end: 0.5rem;
    }

    .assignments-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0.85rem 1rem;
    }

    .assignments-info-item__label {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
    }

    .assignments-info-item__value {
        font-weight: 600;
        color: var(--default-text-color);
    }

    .assignments-course-chip,
    .assignments-lesson-chip,
    .assignments-grade-chip {
        display: inline-flex;
        align-items: center;
        padding: 0.22rem 0.55rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .assignments-course-chip {
        background: rgba(var(--primary-rgb), 0.1);
        color: rgb(var(--primary-rgb));
    }

    .assignments-lesson-chip {
        background: rgba(108, 117, 125, 0.12);
        color: #495057;
    }

    .assignments-grade-chip {
        background: rgba(255, 193, 7, 0.12);
        color: #b78103;
    }

    .assignments-status-chip {
        display: inline-flex;
        align-items: center;
        padding: 0.22rem 0.55rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .assignments-status-chip--published {
        background: rgba(25, 135, 84, 0.12);
        color: #198754;
    }

    .assignments-status-chip--draft {
        background: rgba(108, 117, 125, 0.12);
        color: #6c757d;
    }

    .assignments-form-actions {
        position: sticky;
        bottom: 1rem;
        z-index: 2;
    }

    .assignments-actions__btn {
        min-width: 2rem;
    }

    .assignments-empty-state__icon {
        width: 2.5rem;
        height: 2.5rem;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        background: rgba(var(--primary-rgb), 0.08);
        color: rgb(var(--primary-rgb));
        font-size: 1.1rem;
    }

    .admin-stats-card {
        border: 1px solid var(--default-border, #e2e8f0);
        border-radius: 1rem;
        height: 100%;
        overflow: hidden;
    }

    .admin-stats-card__icon-wrap {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 0.75rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .admin-stats-card__icon {
        font-size: 1.2rem;
    }

    .admin-stats-card__label {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-bottom: 0.15rem;
    }

    .admin-stats-card__value {
        font-size: 1.35rem;
        font-weight: 700;
        margin-bottom: 0;
        color: var(--default-text-color);
    }

    .admin-stats-card__sub {
        font-size: 0.72rem;
        color: var(--text-muted);
    }

    .admin-stats-card--blue .admin-stats-card__icon-wrap { background: rgba(79, 70, 229, 0.12); color: #4f46e5; }
    .admin-stats-card--green .admin-stats-card__icon-wrap { background: rgba(16, 185, 129, 0.12); color: #059669; }
    .admin-stats-card--cyan .admin-stats-card__icon-wrap { background: rgba(6, 182, 212, 0.12); color: #0891b2; }
    .admin-stats-card--orange .admin-stats-card__icon-wrap { background: rgba(249, 115, 22, 0.12); color: #ea580c; }
    .admin-stats-card--purple .admin-stats-card__icon-wrap { background: rgba(139, 92, 246, 0.12); color: #7c3aed; }

    @media (max-width: 991.98px) {
        .group-show-actions {
            justify-content: flex-start;
        }
    }
</style>
