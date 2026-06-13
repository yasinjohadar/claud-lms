@extends('admin.layouts.master')

@section('page-title')
    فريق العمل
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'فريق العمل'],
            ],
            'title' => 'إدارة فريق العمل',
            'subtitle' => 'عرض أعضاء الفريق في الصفحة الرئيسية وصفحة من نحن',
            'actions' => '<a href="' . route('admin.team-members.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-user-add-line me-1 fs-18"></i> إضافة عضو جديد</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-team-line',
                'label' => 'إجمالي الأعضاء', 'value' => number_format($stats['total']),
                'hint' => 'كل الأعضاء',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'منشورون', 'value' => number_format($stats['published']),
                'hint' => 'يظهرون في الموقع',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-presentation-line',
                'label' => 'مدربون', 'value' => number_format($stats['instructors']),
                'hint' => 'مجموعة المدربين',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-link',
                'label' => 'مرتبطون بنظام', 'value' => number_format($stats['linked']),
                'hint' => 'مستخدمون موجودون',
            ])
        </div>

        <div class="filter-panel">
            <div class="filter-panel__title">تصفية الأعضاء</div>
            <div class="filter-panel__subtitle">ابحث أو فلتر حسب المجموعة والمصدر</div>
            <form action="{{ route('admin.team-members.index') }}" method="GET" id="teamMembersFilterForm"
                  data-admin-ajax-filter
                  data-target="#teamMembersAjaxTarget"
                  data-modals-target="#teamMembersModalsHost"
                  data-count-target="#teamMembersFilteredCount"
                  data-reset-url="{{ route('admin.team-members.index') }}">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="search" class="form-control" data-ajax-search
                                   placeholder="البحث بالاسم أو المسمى..."
                                   value="{{ request('search') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">المجموعة</label>
                        <select name="team_group" class="form-select" data-ajax-auto>
                            <option value="">الكل</option>
                            <option value="instructor" {{ request('team_group') === 'instructor' ? 'selected' : '' }}>مدربون</option>
                            <option value="admin" {{ request('team_group') === 'admin' ? 'selected' : '' }}>فريق إداري</option>
                            <option value="management" {{ request('team_group') === 'management' ? 'selected' : '' }}>إدارة</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">المصدر</label>
                        <select name="source" class="form-select" data-ajax-auto>
                            <option value="">الكل</option>
                            <option value="user" {{ request('source') === 'user' ? 'selected' : '' }}>مستخدم نظام</option>
                            <option value="manual" {{ request('source') === 'manual' ? 'selected' : '' }}>إدخال يدوي</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                        <select name="status" class="form-select" data-ajax-auto>
                            <option value="">الكل</option>
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
                    <span class="fw-bold fs-16">قائمة أعضاء الفريق</span>
                    <span class="table-count-badge" id="teamMembersFilteredCount">{{ number_format($stats['filtered']) }}</span>
                </div>
            </div>
            <div class="ajax-filter-target" id="teamMembersAjaxTarget">
                @include('admin.pages.team-members.partials.list')
            </div>
        </div>

        <div id="teamMembersModalsHost">
            @include('admin.pages.team-members.partials.modals')
        </div>

    </div>
</div>
@stop
