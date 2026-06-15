@extends('admin.layouts.master')

@section('page-title')
    قائمة الطلاب
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @php
            $studentHeaderActions = '';
            if (auth()->user()?->can('enrollment-manage')) {
                $studentHeaderActions .= '<button type="button" class="btn btn-success btn-wave me-2" data-open-enrollment-grant data-modal-id="studentsIndexGrantModal"><i class="ri-user-add-line me-1"></i> تسجيل في كورس</button>';
            }
            $studentHeaderActions .= '<a href="' . route('admin.students.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-user-add-line me-1 fs-18"></i> إضافة طالب</a>';
        @endphp

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الطلاب'],
            ],
            'title' => 'إدارة الطلاب',
            'subtitle' => 'ملفات الطلاب والتسجيلات والتقدم',
            'actions' => $studentHeaderActions,
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-graduation-cap-line',
                'label' => 'إجمالي الطلاب', 'value' => number_format($stats['total']),
                'hint' => 'كل الملفات',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'طلاب نشطون', 'value' => number_format($stats['active']),
                'hint' => 'حالة active',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-book-open-line',
                'label' => 'مسجّلون بكورسات', 'value' => number_format($stats['enrolled']),
                'hint' => 'تسجيل نشط',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-filter-3-line',
                'label' => 'نتائج الفلتر', 'value' => number_format($stats['filtered']),
                'hint' => 'حسب البحث',
            ])
        </div>

        <div class="filter-panel">
            <div class="filter-panel__title">تصفية الطلاب</div>
            <div class="filter-panel__subtitle">ابحث بالاسم أو البريد أو رمز الطالب</div>
            <form action="{{ route('admin.students.index') }}" method="GET" id="studentsFilterForm"
                  data-admin-ajax-filter
                  data-target="#studentsAjaxTarget"
                  data-modals-target="#studentsModalsHost"
                  data-count-target="#studentsFilteredCount"
                  data-reset-url="{{ route('admin.students.index') }}"
                  data-toggle-url="{{ url('/admin/students') }}"
                  data-toggle-modal="#studentToggleStatusModal">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-5">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="search" class="form-control" data-ajax-search
                                   placeholder="الاسم، البريد، الهاتف، رمز الطالب..."
                                   value="{{ request('search') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                        <select name="status" class="form-select" data-ajax-auto>
                            <option value="">كل الحالات</option>
                            @foreach(\App\Models\Student::STATUSES as $status)
                                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                    {{ (new \App\Models\Student(['status' => $status]))->status_label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="ri-search-2-line me-1"></i> بحث
                        </button>
                        <button type="button" class="btn btn-light border" data-ajax-reset title="مسح الفلاتر">
                            <i class="ri-refresh-line"></i>
                        </button>
                    </div>
                </div>
                <div class="ajax-filter-status mt-2" id="studentsFilterStatus" aria-live="polite"></div>
            </form>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold fs-16">قائمة الطلاب</span>
                    <span class="table-count-badge" id="studentsFilteredCount">{{ number_format($stats['filtered']) }}</span>
                </div>
            </div>
            <div class="ajax-filter-target" id="studentsAjaxTarget">
                @include('admin.pages.students.partials.list')
            </div>
        </div>

        <div id="studentsModalsHost">
            @include('admin.pages.students.partials.modals')
        </div>

        <x-admin.toggle-status-modal
            id="studentToggleStatusModal"
            entity-label="الطالب"
            activate-confirm-text="نعم، فعّل"
            deactivate-confirm-text="نعم، أوقف"
        />

        @can('enrollment-manage')
        @include('admin.partials.enrollments.grant-modal', ['modalId' => 'studentsIndexGrantModal'])
        @endcan
    </div>
</div>
@stop

@push('scripts')
    @can('enrollment-manage')
    @include('admin.partials.enrollments.grant-scripts')
    @endcan
@endpush
