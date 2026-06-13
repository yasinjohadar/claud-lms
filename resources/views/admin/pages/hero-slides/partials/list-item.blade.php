<div class="list-group-item d-flex align-items-center gap-3 py-3" data-id="{{ $slide->id }}">
    <span class="text-muted cursor-grab" title="سحب"><i class="ri-draggable fs-18"></i></span>
    <div class="flex-grow-1 min-w-0">
        <div class="fw-bold">{{ $slide->admin_title }}</div>
        <div class="text-muted fs-12">
            {{ $slide->pagination_label ?? '—' }}
            · {{ $slide->theme_variant }}
            · {{ $slide->visual_type }}
            @if($slide->starts_at || $slide->expires_at)
                · <i class="ri-calendar-line"></i> مجدولة
            @endif
        </div>
    </div>
    <span class="badge-soft {{ $slide->is_active ? 'badge-soft-success' : 'badge-soft-secondary' }}">
        {{ $slide->is_active ? 'نشطة' : 'موقوفة' }}
    </span>
    <div class="d-flex gap-1">
        <button type="button" class="btn btn-sm btn-light border hero-slide-toggle"
                data-url="{{ route('admin.hero-slides.toggle', $slide) }}"
                title="تفعيل/إيقاف">
            <i class="ri-shut-down-line"></i>
        </button>
        <form method="POST" action="{{ route('admin.hero-slides.duplicate', $slide) }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-light border" title="نسخ"><i class="ri-file-copy-line"></i></button>
        </form>
        <a href="{{ route('admin.hero-slides.edit', $slide) }}" class="btn btn-sm btn-primary"><i class="ri-pencil-line"></i></a>
        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteSlide{{ $slide->id }}">
            <i class="ri-delete-bin-line"></i>
        </button>
    </div>
</div>

<x-admin.confirm-modal
    :id="'deleteSlide' . $slide->id"
    variant="danger"
    title="حذف الشريحة"
    :message="'حذف «' . $slide->admin_title . '» نهائياً؟'"
    confirm-text="نعم، احذف"
    :action="route('admin.hero-slides.destroy', $slide)"
    method="DELETE"
/>
