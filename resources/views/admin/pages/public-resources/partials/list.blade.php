<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="min-width: 220px;">المورد</th>
                    <th style="min-width: 100px;">النوع</th>
                    <th style="min-width: 200px;">التفاصيل</th>
                    <th style="min-width: 80px;">الترتيب</th>
                    <th style="min-width: 110px;">الحالة</th>
                    <th style="min-width: 80px;">إجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resources as $resource)
                    <tr>
                        <td class="text-muted fw-medium">{{ $resources->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}">
                                    <i class="{{ $resource->isLink() ? 'ri-link' : 'ri-file-line' }}"></i>
                                </span>
                                <div>
                                    <a href="{{ route('admin.public-resources.edit', $resource) }}"
                                       class="fw-bold row-title-link text-decoration-none d-block">
                                        {{ $resource->title }}
                                    </a>
                                    <span class="text-muted fs-11" dir="ltr">{{ $resource->slug }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-soft {{ $resource->isLink() ? 'badge-soft-info' : 'badge-soft-warning' }}">
                                <i class="{{ $resource->isLink() ? 'ri-link' : 'ri-file-line' }} me-1"></i>{{ $resource->type_label }}
                            </span>
                        </td>
                        <td>
                            @if($resource->isLink())
                                <span class="email-copy-wrap">
                                    <span dir="ltr" class="text-primary">{{ Str::limit($resource->url, 40) }}</span>
                                    <button type="button" class="copy-btn" data-copy="{{ $resource->url }}" title="نسخ">
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                </span>
                            @elseif($resource->file_original_name)
                                <span class="meta-text">
                                    <i class="ri-file-line"></i>
                                    {{ Str::limit($resource->file_original_name, 35) }}
                                </span>
                                @if($resource->formatted_file_size)
                                    <span class="text-muted fs-11 d-block">{{ $resource->formatted_file_size }}</span>
                                @endif
                            @else
                                <span class="text-muted fs-12">—</span>
                            @endif
                        </td>
                        <td><span class="en-text fw-medium">{{ $resource->sort_order }}</span></td>
                        <td>
                            @if($resource->is_published)
                                <span class="badge-soft badge-soft-success">منشور</span>
                            @else
                                <span class="badge-soft badge-soft-secondary">غير منشور</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btn-group">
                                @if($resource->isLink())
                                    <a href="{{ $resource->url }}" target="_blank" rel="noopener noreferrer"
                                       class="action-btn action-btn--view" title="فتح الرابط">
                                        <i class="ri-external-link-line"></i>
                                    </a>
                                @elseif($resource->file_url)
                                    <a href="{{ $resource->file_url }}" target="_blank"
                                       class="action-btn action-btn--view" title="فتح الملف">
                                        <i class="ri-external-link-line"></i>
                                    </a>
                                @endif
                                <a href="{{ route('admin.public-resources.edit', $resource) }}"
                                   class="action-btn action-btn--edit" title="تعديل">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <button type="button"
                                        class="action-btn action-btn--delete"
                                        title="حذف"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deletePublicResource{{ $resource->id }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="ri-folder-open-line"></i></div>
                                @if(request()->hasAny(['search', 'type', 'status']))
                                    <h5 class="fw-bold mb-2">لا توجد نتائج</h5>
                                    <p class="text-muted mb-3">لم يتم العثور على موارد مطابقة.</p>
                                    <button type="button" class="btn btn-light border btn-sm" data-ajax-reset>
                                        <i class="ri-refresh-line me-1"></i> إعادة تعيين
                                    </button>
                                @else
                                    <h5 class="fw-bold mb-2">لا توجد موارد عامة</h5>
                                    <p class="text-muted mb-3">لم يتم إنشاء أي موارد بعد.</p>
                                    <a href="{{ route('admin.public-resources.create') }}" class="btn btn-primary btn-sm">
                                        <i class="ri-add-line me-1"></i> إضافة مورد جديد
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($resources->hasPages())
        <div class="card-footer border-top bg-transparent py-3 ajax-pagination">
            {{ $resources->withQueryString()->links() }}
        </div>
    @endif
</div>
