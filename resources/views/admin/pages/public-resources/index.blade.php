@extends('admin.layouts.master')

@section('page-title')
    الموارد العامة
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الموارد العامة'],
            ],
            'title' => 'كافة الموارد العامة',
            'subtitle' => 'إدارة موارد الموقع المستقلة — غير مرتبطة بأي كورس',
            'actions' => '<a href="' . route('admin.public-resources.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-circle-line me-1 fs-18"></i> إضافة مورد جديد</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-folder-open-line',
                'label' => 'إجمالي الموارد', 'value' => number_format($stats['total']),
                'hint' => 'حسب الفلاتر الحالية',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'منشورة', 'value' => number_format($stats['published']),
                'hint' => 'حالة منشور',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-link',
                'label' => 'روابط', 'value' => number_format($stats['links']),
                'hint' => 'نوع رابط',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-file-line',
                'label' => 'ملفات', 'value' => number_format($stats['files']),
                'hint' => 'نوع ملف',
            ])
        </div>

        <div class="filter-panel">
            <div class="filter-panel__title">تصفية الموارد</div>
            <div class="filter-panel__subtitle">ابحث أو فلتر حسب النوع والحالة</div>
            <form action="{{ route('admin.public-resources.index') }}" method="GET" id="publicResourcesFilterForm"
                  data-admin-ajax-filter
                  data-target="#publicResourcesAjaxTarget"
                  data-modals-target="#publicResourcesModalsHost"
                  data-count-target="#publicResourcesFilteredCount"
                  data-reset-url="{{ route('admin.public-resources.index') }}">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="search" class="form-control" data-ajax-search
                                   placeholder="البحث بالعنوان أو الوصف..."
                                   value="{{ request('search') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">النوع</label>
                        <select name="type" class="form-select" data-ajax-auto>
                            <option value="">كل الأنواع</option>
                            <option value="link" {{ request('type') === 'link' ? 'selected' : '' }}>رابط</option>
                            <option value="file" {{ request('type') === 'file' ? 'selected' : '' }}>ملف</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                        <select name="status" class="form-select" data-ajax-auto>
                            <option value="">كل الحالات</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>منشور</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>غير منشور</option>
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
                <div class="ajax-filter-status mt-2" aria-live="polite"></div>
            </form>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold fs-16">قائمة الموارد العامة</span>
                    <span class="table-count-badge" id="publicResourcesFilteredCount">{{ number_format($stats['filtered']) }}</span>
                </div>
            </div>
            <div class="ajax-filter-target" id="publicResourcesAjaxTarget">
                @include('admin.pages.public-resources.partials.list')
            </div>
        </div>

        <div id="publicResourcesModalsHost">
            @include('admin.pages.public-resources.partials.modals')
        </div>

    </div>
</div>
@stop
