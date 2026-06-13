@extends('admin.layouts.master')

@section('page-title')
    تصنيفات الكورسات
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الكورسات'],
                ['label' => 'التصنيفات'],
            ],
            'title' => 'تصنيفات الكورسات',
            'subtitle' => 'إدارة تصنيفات الكورسات وتنظيمها',
            'actions' => '<a href="' . route('admin.courses.categories.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-circle-line me-1 fs-18"></i> إضافة تصنيف جديد</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-folder-line',
                'label' => 'إجمالي التصنيفات', 'value' => number_format($stats['total']),
                'hint' => number_format($stats['filtered']) . ' حسب الفلاتر',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'تصنيفات نشطة', 'value' => number_format($stats['active']),
                'hint' => 'مفعّلة حالياً',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-pause-circle-line',
                'label' => 'غير نشطة', 'value' => number_format($stats['inactive']),
                'hint' => 'معطّلة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-graduation-cap-line',
                'label' => 'إجمالي الكورسات', 'value' => number_format($stats['courses']),
                'hint' => 'في كل التصنيفات',
            ])
        </div>

        <div class="filter-panel">
            <div class="filter-panel__title">تصفية التصنيفات</div>
            <div class="filter-panel__subtitle">ابحث بالاسم أو فلتر حسب التصنيف الأب والحالة</div>
            <x-admin.ajax-filter-form
                :action="route('admin.courses.categories.index')"
                target="#courseCategoriesAjaxTarget"
                count-target="#courseCategoriesFilteredCount"
                :reset-url="route('admin.courses.categories.index')"
                id="courseCategoriesFilterForm">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="search" class="form-control" data-ajax-search
                                   placeholder="ابحث بالاسم..."
                                   value="{{ request('search') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">التصنيف الأب</label>
                        <select name="parent" class="form-select" data-ajax-auto>
                            <option value="">الكل</option>
                            <option value="root" {{ request('parent') === 'root' ? 'selected' : '' }}>التصنيفات الرئيسية فقط</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ request('parent') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                        <select name="status" class="form-select" data-ajax-auto>
                            <option value="">كل الحالات</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
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
                    <span class="fw-bold fs-16">قائمة التصنيفات</span>
                    <span class="table-count-badge" id="courseCategoriesFilteredCount">{{ number_format($stats['filtered']) }}</span>
                </div>
            </div>
            <div class="ajax-filter-target" id="courseCategoriesAjaxTarget">
                @include('admin.courses.categories.partials.list')
            </div>
        </div>

    </div>
</div>

@include('admin.partials.ui.modal-action')

<x-admin.confirm-modal
    id="deleteCourseCategoryModal"
    ajax-confirm
    variant="danger"
    icon="ri-delete-bin-7-line"
    title="تأكيد حذف التصنيف"
    message="لا يمكن التراجع عن هذا الإجراء. سيتم حذف التصنيف نهائياً."
    confirm-text="نعم، احذف التصنيف"
/>
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteCourseCategoryModal');
    var currentCategoryId = null;

    if (!deleteModal) return;

    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        if (!button) return;
        currentCategoryId = button.getAttribute('data-category-id');
        var subjectEl = deleteModal.querySelector('[data-confirm-subject]');
        if (subjectEl) subjectEl.textContent = button.getAttribute('data-category-name') || '';
    });

    var confirmBtn = deleteModal.querySelector('[data-confirm-submit]');
    if (!confirmBtn) return;

    confirmBtn.addEventListener('click', function () {
        if (!currentCategoryId) return;

        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري الحذف...';

        fetch('{{ url('/admin/courses/categories') }}/' + currentCategoryId, {
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
                    window.adminUiToast(data.message || 'تم حذف التصنيف بنجاح', 'success');
                }
                var filterForm = document.getElementById('courseCategoriesFilterForm');
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
            confirmBtn.innerHTML = '<i class="ri-delete-bin-7-line me-1"></i><span data-confirm-submit-text>نعم، احذف التصنيف</span>';
            currentCategoryId = null;
        });
    });
});
</script>
@endpush
