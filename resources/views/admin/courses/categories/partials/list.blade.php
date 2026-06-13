<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="min-width: 220px;">الاسم</th>
                    <th style="min-width: 140px;">التصنيف الأب</th>
                    <th style="min-width: 120px;">عدد الكورسات</th>
                    <th style="min-width: 80px;">الترتيب</th>
                    <th style="min-width: 100px;">الحالة</th>
                    <th style="min-width: 150px;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="text-muted fw-medium">{{ $categories->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}"
                                      @if($category->color) style="background: {{ $category->color }};" @endif>
                                    @if($category->icon)
                                        <i class="{{ $category->icon }}"></i>
                                    @else
                                        {{ mb_strtoupper(mb_substr($category->name, 0, 1)) }}
                                    @endif
                                </span>
                                <div>
                                    <a href="{{ route('admin.courses.categories.edit', $category) }}"
                                       class="fw-bold row-title-link text-decoration-none d-block">
                                        {{ $category->name }}
                                    </a>
                                    @if($category->slug)
                                        <span class="text-muted fs-11" dir="ltr">{{ $category->slug }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($category->parent)
                                <span class="badge-soft badge-soft-secondary">{{ $category->parent->name }}</span>
                            @else
                                <span class="badge-soft badge-soft-primary">رئيسي</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.courses.index', ['category' => $category->id]) }}"
                               class="badge-soft badge-soft-info text-decoration-none">
                                {{ number_format($category->courses_count) }} كورس
                            </a>
                        </td>
                        <td>
                            <span class="meta-text">
                                <i class="ri-sort-asc"></i>
                                {{ $category->order }}
                            </span>
                        </td>
                        <td>
                            @if($category->is_active)
                                <span class="badge-soft badge-soft-success">نشط</span>
                            @else
                                <span class="badge-soft badge-soft-secondary">غير نشط</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <a href="{{ route('admin.courses.categories.edit', $category) }}"
                                   class="action-btn action-btn--edit" title="تعديل">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('admin.courses.categories.toggle-active', $category) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="action-btn action-btn--success {{ $category->is_active ? 'is-active' : '' }}"
                                            title="{{ $category->is_active ? 'تعطيل' : 'تفعيل' }}">
                                        <i class="ri-{{ $category->is_active ? 'checkbox-circle-fill' : 'checkbox-circle-line' }}"></i>
                                    </button>
                                </form>
                                <button type="button"
                                        class="action-btn action-btn--delete"
                                        title="حذف"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteCourseCategoryModal"
                                        data-category-id="{{ $category->id }}"
                                        data-category-name="{{ $category->name }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="ri-folder-line"></i></div>
                                @if(request()->hasAny(['search', 'parent', 'status']))
                                    <h5 class="fw-bold mb-2">لا توجد نتائج</h5>
                                    <p class="text-muted mb-3">لم يتم العثور على تصنيفات مطابقة.</p>
                                    <button type="button" class="btn btn-light border btn-sm" data-ajax-reset>
                                        <i class="ri-refresh-line me-1"></i> إعادة تعيين
                                    </button>
                                @else
                                    <h5 class="fw-bold mb-2">لا توجد تصنيفات</h5>
                                    <p class="text-muted mb-3">لم يتم إنشاء أي تصنيفات بعد.</p>
                                    <a href="{{ route('admin.courses.categories.create') }}" class="btn btn-primary btn-sm">
                                        <i class="ri-add-line me-1"></i> إضافة تصنيف جديد
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
        <div class="card-footer border-top bg-transparent py-3 ajax-pagination">
            {{ $categories->withQueryString()->links() }}
        </div>
    @endif
</div>
