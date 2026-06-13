<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="min-width: 180px;">الاسم</th>
                    <th style="min-width: 130px;">عدد الكورسات</th>
                    <th style="min-width: 140px;">اللون</th>
                    <th style="min-width: 100px;">الحالة</th>
                    <th style="min-width: 100px;">إجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tags as $tag)
                    @php $tagColor = $tag->color ?? '#6366f1'; @endphp
                    <tr>
                        <td class="text-muted fw-medium">{{ $tags->firstItem() + $loop->index }}</td>
                        <td>
                            <span class="tag-name-badge" style="--tag-color: {{ $tagColor }}">
                                <i class="ri-hashtag"></i>{{ $tag->name }}
                            </span>
                            <span class="text-muted fs-11 d-block mt-1" dir="ltr">{{ $tag->slug }}</span>
                        </td>
                        <td>
                            <span class="badge-soft badge-soft-info">
                                <i class="ri-book-open-line me-1"></i>{{ number_format($tag->courses_count) }} كورس
                            </span>
                        </td>
                        <td>
                            <span class="tag-color-chip">
                                <span class="tag-color-chip__dot" style="background-color: {{ $tagColor }}"></span>
                                <span dir="ltr" class="tag-color-chip__hex">{{ $tag->color ?? '—' }}</span>
                            </span>
                        </td>
                        <td>
                            @if($tag->is_active)
                                <span class="badge-soft badge-soft-success">نشط</span>
                            @else
                                <span class="badge-soft badge-soft-secondary">غير نشط</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <a href="{{ route('admin.courses.tags.edit', $tag) }}"
                                   class="action-btn action-btn--edit" title="تعديل">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <button type="button"
                                        class="action-btn action-btn--delete"
                                        title="حذف"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteCourseTag{{ $tag->id }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="ri-price-tag-3-line"></i></div>
                                @if(request()->hasAny(['search', 'sort']))
                                    <h5 class="fw-bold mb-2">لا توجد نتائج</h5>
                                    <p class="text-muted mb-3">لم يتم العثور على تاغات مطابقة.</p>
                                    <button type="button" class="btn btn-light border btn-sm" data-ajax-reset>
                                        <i class="ri-refresh-line me-1"></i> إعادة تعيين
                                    </button>
                                @else
                                    <h5 class="fw-bold mb-2">لا توجد تاغات</h5>
                                    <p class="text-muted mb-3">لم يتم إنشاء أي تاغات بعد.</p>
                                    <a href="{{ route('admin.courses.tags.create') }}" class="btn btn-primary btn-sm">
                                        <i class="ri-add-line me-1"></i> إضافة تاغ جديد
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tags->hasPages())
        <div class="card-footer border-top bg-transparent py-3 ajax-pagination">
            {{ $tags->withQueryString()->links() }}
        </div>
    @endif
</div>
