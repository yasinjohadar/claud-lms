<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="min-width: 260px;">العنوان</th>
                    <th style="min-width: 140px;">التصنيف</th>
                    <th style="min-width: 120px;">المدرب</th>
                    <th style="min-width: 90px;">السعر</th>
                    <th style="min-width: 100px;">الحالة</th>
                    <th style="min-width: 180px;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                    <tr>
                        <td class="text-muted fw-medium">{{ $courses->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}"
                                      @if($course->category?->color) style="background: {{ $course->category->color }};" @endif>
                                    @if($course->icon)
                                        <i class="{{ str_starts_with($course->icon, 'fa') ? $course->icon : 'fas fa-' . $course->icon }}"></i>
                                    @else
                                        <i class="ri-book-open-line"></i>
                                    @endif
                                </span>
                                <div>
                                    <a href="{{ route('admin.courses.edit', $course) }}"
                                       class="fw-bold row-title-link text-decoration-none d-block">
                                        {{ Str::limit($course->title, 55) }}
                                    </a>
                                    <span class="text-muted fs-11" dir="ltr">{{ $course->slug }}</span>
                                    @if($course->is_featured)
                                        <span class="badge-soft badge-soft-warning fs-11 mt-1 d-inline-block">
                                            <i class="ri-star-fill"></i> مميز
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($course->category)
                                <span class="badge-soft badge-soft-primary">
                                    @if($course->category->icon)<i class="{{ $course->category->icon }} me-1"></i>@endif
                                    {{ $course->category->name }}
                                </span>
                            @else
                                <span class="text-muted fs-12">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="meta-text">
                                <i class="ri-user-star-line"></i>
                                {{ $course->instructor?->name ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <span class="meta-text en-text">
                                <i class="ri-money-dollar-circle-line"></i>
                                ${{ number_format((float) $course->price, 2) }}
                            </span>
                        </td>
                        <td>
                            @if($course->status === 'published')
                                <span class="badge-soft badge-soft-success">منشور</span>
                            @else
                                <span class="badge-soft badge-soft-secondary">مسودة</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <a href="{{ route('courses.show', $course->slug) }}"
                                   target="_blank" rel="noopener"
                                   class="action-btn action-btn--view" title="عرض">
                                    <i class="ri-external-link-line"></i>
                                </a>
                                <a href="{{ route('admin.courses.curriculum', $course) }}"
                                   class="action-btn action-btn--view" title="المنهاج">
                                    <i class="ri-list-ordered"></i>
                                </a>
                                <a href="{{ route('admin.courses.edit', $course) }}"
                                   class="action-btn action-btn--edit" title="تعديل">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                @can('enrollment-manage')
                                <button type="button"
                                        class="action-btn action-btn--success"
                                        title="إضافة طالب"
                                        data-open-enrollment-grant
                                        data-modal-id="coursesIndexGrantModal"
                                        data-course-id="{{ $course->id }}"
                                        data-course-label="{{ e($course->title) }}">
                                    <i class="ri-user-add-line"></i>
                                </button>
                                @endcan
                                <form action="{{ route('admin.courses.toggle-featured', $course) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="action-btn action-btn--key {{ $course->is_featured ? 'is-active' : '' }}"
                                            title="{{ $course->is_featured ? 'إزالة من المميز' : 'جعله مميز' }}">
                                        <i class="ri-star{{ $course->is_featured ? '-fill' : '-line' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.courses.toggle-publish', $course) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="action-btn action-btn--success {{ $course->status === 'published' ? 'is-active' : '' }}"
                                            title="{{ $course->status === 'published' ? 'إلغاء النشر' : 'نشر' }}">
                                        <i class="ri-{{ $course->status === 'published' ? 'checkbox-circle-fill' : 'checkbox-circle-line' }}"></i>
                                    </button>
                                </form>
                                <button type="button"
                                        class="action-btn action-btn--delete"
                                        title="حذف"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteCourseModal"
                                        data-course-id="{{ $course->id }}"
                                        data-course-title="{{ Str::limit($course->title, 50) }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="ri-graduation-cap-line"></i></div>
                                @if(request()->hasAny(['search', 'category', 'status', 'instructor', 'featured']))
                                    <h5 class="fw-bold mb-2">لا توجد نتائج</h5>
                                    <p class="text-muted mb-3">لم يتم العثور على كورسات مطابقة للبحث.</p>
                                    <button type="button" class="btn btn-light border btn-sm" data-ajax-reset>
                                        <i class="ri-refresh-line me-1"></i> إعادة تعيين
                                    </button>
                                @else
                                    <h5 class="fw-bold mb-2">لا توجد كورسات</h5>
                                    <p class="text-muted mb-3">لم يتم إنشاء أي كورسات بعد.</p>
                                    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary btn-sm">
                                        <i class="ri-add-line me-1"></i> إضافة كورس جديد
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($courses->hasPages())
        <div class="card-footer border-top bg-transparent py-3 ajax-pagination">
            {{ $courses->withQueryString()->links() }}
        </div>
    @endif
</div>
