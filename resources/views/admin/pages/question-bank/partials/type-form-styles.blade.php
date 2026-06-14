<style>
    .qb-type-hero {
        padding: 1.25rem 1.35rem;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.06) 0%, rgba(255, 255, 255, 1) 55%);
        margin-bottom: 1.25rem;
    }

    .qb-type-hero__badge {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 0.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .qb-type-card__icon {
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 0.65rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .qb-type-card__icon--primary { background: rgba(79, 70, 229, 0.12); color: #4f46e5; }
    .qb-type-card__icon--success { background: rgba(16, 185, 129, 0.12); color: #059669; }
    .qb-type-card__icon--warning { background: rgba(245, 158, 11, 0.12); color: #d97706; }
    .qb-type-card__icon--danger { background: rgba(239, 68, 68, 0.12); color: #dc2626; }
    .qb-type-card__icon--purple { background: rgba(139, 92, 246, 0.12); color: #7c3aed; }
    .qb-type-card__icon--cyan { background: rgba(6, 182, 212, 0.12); color: #0891b2; }
    .qb-type-card__icon--info { background: rgba(14, 165, 233, 0.12); color: #0284c7; }
    .qb-type-card__icon--orange { background: rgba(249, 115, 22, 0.12); color: #ea580c; }
    .qb-type-card__icon--secondary { background: rgba(100, 116, 139, 0.12); color: #475569; }

    .qb-type-tip {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        padding: 0.85rem 1rem;
        border-radius: 0.75rem;
        background: rgba(14, 165, 233, 0.08);
        border: 1px solid rgba(14, 165, 233, 0.18);
        color: #0c4a6e;
        font-size: 0.84rem;
        line-height: 1.55;
    }

    .qb-type-tip i { font-size: 1.1rem; margin-top: 0.1rem; color: #0284c7; }

    .qb-option-row {
        padding: 1rem 1.1rem;
        border-radius: 0.85rem;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .qb-option-row:hover {
        border-color: #c7d2fe;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.08);
    }

    .qb-option-row__letter {
        width: 2rem;
        height: 2rem;
        border-radius: 0.55rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        background: rgba(79, 70, 229, 0.1);
        color: #4f46e5;
    }

    .qb-tf-choice {
        cursor: pointer;
        border: 2px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        transition: all 0.2s ease;
        background: #fff;
    }

    .qb-tf-choice:hover { border-color: #a5b4fc !important; transform: translateY(-2px); }
    .qb-tf-choice.is-selected-true { border-color: #10b981 !important; background: rgba(16, 185, 129, 0.08) !important; }
    .qb-tf-choice.is-selected-false { border-color: #ef4444 !important; background: rgba(239, 68, 68, 0.08) !important; }

    .qb-pair-row {
        padding: 0.9rem 1rem;
        border-radius: 0.85rem;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .qb-type-sidebar-sticky {
        position: sticky;
        top: 5.5rem;
    }

    .qb-type-actions .btn-primary {
        padding-block: 0.65rem;
        font-weight: 600;
    }
</style>
