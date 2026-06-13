@extends('admin.layouts.master')

@section('page-title')
    تاغات الكورسات
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
                ['label' => 'التاغات'],
            ],
            'title' => 'تاغات الكورسات',
            'subtitle' => 'إدارة تاغات الكورسات وتصنيف المحتوى',
            'actions' => '<a href="' . route('admin.courses.tags.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-circle-line me-1 fs-18"></i> إضافة تاغ جديد</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'col' => 'col-sm-6 col-xl-3',
                'variant' => 'purple', 'icon' => 'ri-price-tag-3-line',
                'label' => 'إجمالي التاغات', 'value' => number_format($stats['total']),
                'hint' => number_format($stats['filtered']) . ' حسب الفلاتر',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'col' => 'col-sm-6 col-xl-3',
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'تاغات نشطة', 'value' => number_format($stats['active']),
                'hint' => 'مفعّلة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'col' => 'col-sm-6 col-xl-3',
                'variant' => 'cyan', 'icon' => 'ri-book-open-line',
                'label' => 'تاغات مستخدمة', 'value' => number_format($stats['used']),
                'hint' => 'مرتبطة بكورسات',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'col' => 'col-sm-6 col-xl-3',
                'variant' => 'orange', 'icon' => 'ri-links-line',
                'label' => 'إجمالي الربط', 'value' => number_format($stats['courses']),
                'hint' => 'تاغ × كورس',
            ])
        </div>

        <div class="filter-panel">
            <div class="filter-panel__title">تصفية التاغات</div>
            <div class="filter-panel__subtitle">ابحث بالاسم أو رتّب حسب الاستخدام</div>
            <x-admin.ajax-filter-form
                :action="route('admin.courses.tags.index')"
                target="#courseTagsAjaxTarget"
                modals-target="#courseTagsModalsHost"
                count-target="#courseTagsFilteredCount"
                :reset-url="route('admin.courses.tags.index')"
                id="courseTagsFilterForm">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-5">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="search" class="form-control" data-ajax-search
                                   placeholder="البحث بالاسم..."
                                   value="{{ request('search') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <label class="form-label fs-12 text-muted mb-1">الترتيب</label>
                        <select name="sort" class="form-select" data-ajax-auto>
                            <option value="">الترتيب الافتراضي</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>الأكثر استخداماً</option>
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
                    <span class="fw-bold fs-16">قائمة التاغات</span>
                    <span class="table-count-badge" id="courseTagsFilteredCount">{{ number_format($stats['filtered']) }}</span>
                </div>
            </div>
            <div class="ajax-filter-target" id="courseTagsAjaxTarget">
                @include('admin.courses.tags.partials.list')
            </div>
        </div>

        <div id="courseTagsModalsHost">
            @include('admin.courses.tags.partials.modals')
        </div>

    </div>
</div>
@stop
