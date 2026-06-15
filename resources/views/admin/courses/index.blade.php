@extends('admin.layouts.master')

@section('page-title')
    الكورسات
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-toast-container" id="adminToastContainer"></div>
            @include('admin.partials.ui.alerts')

        @php
            $courseHeaderActions = '';
            if (auth()->user()?->can('enrollment-manage')) {
                $courseHeaderActions .= '<button type="button" class="btn btn-success btn-wave me-2" data-open-enrollment-grant data-modal-id="coursesIndexGrantModal"><i class="ri-user-add-line me-1"></i> إضافة طالب لكورس</button>';
            }
            $courseHeaderActions .= '<a href="' . route('admin.courses.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-circle-line me-1 fs-18"></i> إضافة كورس جديد</a>';
        @endphp

            @include('admin.partials.ui.page-header', [
                'breadcrumbs' => [
                    ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                    ['label' => 'الكورسات'],
                ],
                'title' => 'كافة الكورسات',
                'subtitle' => 'إدارة كورسات المنصة والنشر والتصنيف',
                'actions' => $courseHeaderActions,
            ])

            <div class="row g-3 mb-4">
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'purple', 'icon' => 'ri-graduation-cap-line',
                    'label' => 'إجمالي الكورسات', 'value' => number_format($stats['total']),
                    'hint' => number_format($stats['filtered']) . ' حسب الفلاتر',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                    'label' => 'كورسات منشورة', 'value' => number_format($stats['published']),
                    'hint' => 'حالة منشور',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'cyan', 'icon' => 'ri-draft-line',
                    'label' => 'مسودات', 'value' => number_format($stats['draft']),
                    'hint' => 'بانتظار النشر',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'orange', 'icon' => 'ri-star-line',
                    'label' => 'كورسات مميزة', 'value' => number_format($stats['featured']),
                    'hint' => 'في الرئيسية',
                ])
            </div>

            <div class="filter-panel">
                <div class="filter-panel__title">تصفية الكورسات</div>
                <div class="filter-panel__subtitle">ابحث بالعنوان أو فلتر حسب التصنيف والحالة والمدرب</div>
                <x-admin.ajax-filter-form
                    :action="route('admin.courses.index')"
                    target="#coursesAjaxTarget"
                    count-target="#coursesFilteredCount"
                    :reset-url="route('admin.courses.index')"
                    id="coursesFilterForm">
                    <div class="row g-2 g-md-3 align-items-end">
                        <div class="col-lg-3">
                            <label class="form-label fs-12 text-muted mb-1">بحث</label>
                            <div class="search-input-wrap">
                                <i class="ri-search-line"></i>
                                <input type="text" name="search" class="form-control" data-ajax-search
                                       placeholder="ابحث بالعنوان أو الـ slug..."
                                       value="{{ request('search') }}" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label fs-12 text-muted mb-1">التصنيف</label>
                            <select name="category" class="form-select" data-ajax-auto>
                                <option value="">كل التصنيفات</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                            <select name="status" class="form-select" data-ajax-auto>
                                <option value="">كل الحالات</option>
                                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>منشور</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>مسودة</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label fs-12 text-muted mb-1">المدرب</label>
                            <select name="instructor" class="form-select" data-ajax-auto>
                                <option value="">كل المدربين</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ request('instructor') == $instructor->id ? 'selected' : '' }}>
                                        {{ $instructor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill" id="coursesSearchBtn">
                                <i class="ri-search-2-line me-1"></i> بحث
                            </button>
                            <button type="button" class="btn btn-light border" data-ajax-reset title="مسح الفلاتر">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>
                </x-admin.ajax-filter-form>
            </div>

            <div class="card custom-card data-table-card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold fs-16">قائمة الكورسات</span>
                        <span class="table-count-badge" id="coursesFilteredCount">{{ number_format($stats['filtered']) }}</span>
                    </div>
                </div>
                <div class="ajax-filter-target" id="coursesAjaxTarget">
                    @include('admin.courses.partials.list')
                </div>
            </div>

        </div>
    </div>

    @include('admin.partials.ui.modal-action')

    <x-admin.confirm-modal
        id="deleteCourseModal"
        ajax-confirm
        variant="danger"
        icon="ri-delete-bin-7-line"
        title="تأكيد حذف الكورس"
        message="لا يمكن التراجع عن هذا الإجراء. سيتم حذف الكورس نهائياً."
        confirm-text="نعم، احذف الكورس"
    />

    @can('enrollment-manage')
    @include('admin.partials.enrollments.grant-modal', ['modalId' => 'coursesIndexGrantModal'])
    @endcan
@stop

@push('scripts')
    @can('enrollment-manage')
    @include('admin.partials.enrollments.grant-scripts')
    @endcan
<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteCourseModal');
    var currentCourseId = null;

    if (!deleteModal) return;

    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        if (!button) return;
        currentCourseId = button.getAttribute('data-course-id');
        var subjectEl = deleteModal.querySelector('[data-confirm-subject]');
        if (subjectEl) subjectEl.textContent = button.getAttribute('data-course-title') || '';
    });

    var confirmBtn = deleteModal.querySelector('[data-confirm-submit]');
    if (!confirmBtn) return;

    confirmBtn.addEventListener('click', function () {
        if (!currentCourseId) return;

        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري الحذف...';

        fetch('{{ url('/admin/courses') }}/' + currentCourseId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (data.success) {
                if (window.adminUiToast) {
                    window.adminUiToast(data.message || 'تم حذف الكورس بنجاح', 'success');
                }
                var filterForm = document.getElementById('coursesFilterForm');
                if (filterForm && window.AdminAjaxFilter) {
                    window.AdminAjaxFilter.fetch(filterForm, window.location.href, false);
                }
                bootstrap.Modal.getInstance(deleteModal)?.hide();
            } else if (window.adminUiToast) {
                window.adminUiToast(data.message || 'حدث خطأ أثناء الحذف', 'error');
            }
        })
        .catch(function () {
            if (window.adminUiToast) {
                window.adminUiToast('حدث خطأ أثناء الحذف', 'error');
            }
        })
        .finally(function () {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="ri-delete-bin-7-line me-1"></i><span data-confirm-submit-text>نعم، احذف الكورس</span>';
            currentCourseId = null;
        });
    });
});
</script>
@endpush
