@extends('admin.layouts.master')

@section('page-title')
    التسجيلات
@stop

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'التسجيلات'],
            ],
            'title' => 'إدارة التسجيلات',
            'subtitle' => 'ضم الطلاب في الكورسات ومتابعة الحالة والتقدم',
            'actions' => '<button type="button" class="btn btn-primary btn-wave" data-open-enrollment-grant data-modal-id="enrollmentGrantModal"><i class="ri-user-add-line me-1"></i> تسجيل جديد</button>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-book-mark-line',
                'label' => 'إجمالي التسجيلات', 'value' => number_format($stats['total']),
                'hint' => 'كل السجلات',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'نشطة', 'value' => number_format($stats['active']),
                'hint' => 'حالة active',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-shield-user-line',
                'label' => 'منح إداري', 'value' => number_format($stats['admin_granted']),
                'hint' => 'مصدر admin_grant',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-shopping-bag-line',
                'label' => 'مرتبطة بطلب', 'value' => number_format($stats['via_order']),
                'hint' => 'عبر الشراء',
            ])
        </div>

        <div class="filter-panel">
            <div class="filter-panel__title">تصفية التسجيلات</div>
            <div class="filter-panel__subtitle">ابحث بالطالب أو الكورس أو رقم الطلب</div>
            <form action="{{ route('admin.enrollments.index') }}" method="GET" id="enrollmentsFilterForm"
                  data-admin-ajax-filter
                  data-target="#enrollmentsAjaxTarget"
                  data-modals-target="#enrollmentsModalsHost"
                  data-count-target="#enrollmentsFilteredCount"
                  data-reset-url="{{ route('admin.enrollments.index') }}">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="search" class="form-control" data-ajax-search
                                   placeholder="اسم الطالب، البريد، الكورس، رقم الطلب..."
                                   value="{{ request('search') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                        <select name="status" class="form-select" data-ajax-auto>
                            <option value="">كل الحالات</option>
                            @foreach(\App\Models\CourseEnrollment::STATUSES as $status)
                                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                    {{ (new \App\Models\CourseEnrollment(['status' => $status]))->status_label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">المصدر</label>
                        <select name="source" class="form-select" data-ajax-auto>
                            <option value="">كل المصادر</option>
                            @foreach(\App\Models\CourseEnrollment::SOURCES as $source)
                                <option value="{{ $source }}" {{ request('source') === $source ? 'selected' : '' }}>
                                    {{ (new \App\Models\CourseEnrollment(['source' => $source]))->source_label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if(request('student_id'))
                        <input type="hidden" name="student_id" value="{{ request('student_id') }}">
                    @endif
                    @if(request('course_id'))
                        <input type="hidden" name="course_id" value="{{ request('course_id') }}">
                    @endif
                    @if(request('order_id'))
                        <input type="hidden" name="order_id" value="{{ request('order_id') }}">
                    @endif
                    <div class="col-lg-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="ri-search-2-line me-1"></i> بحث
                        </button>
                        <a href="{{ route('admin.enrollments.index') }}" class="btn btn-light border" data-ajax-reset title="مسح الفلاتر">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
                @if(request()->hasAny(['student_id', 'course_id', 'order_id']))
                    <div class="mt-2">
                        @if(request('student_id'))
                            <span class="badge badge-soft-primary me-1">فلتر: طالب #{{ request('student_id') }}</span>
                        @endif
                        @if(request('course_id'))
                            <span class="badge badge-soft-info me-1">فلتر: كورس #{{ request('course_id') }}</span>
                        @endif
                        @if(request('order_id'))
                            <span class="badge badge-soft-warning me-1">فلتر: طلب #{{ request('order_id') }}</span>
                        @endif
                    </div>
                @endif
                <div class="ajax-filter-status mt-2" id="enrollmentsFilterStatus" aria-live="polite"></div>
            </form>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold fs-16">قائمة التسجيلات</span>
                    <span class="table-count-badge" id="enrollmentsFilteredCount">{{ number_format($stats['filtered']) }}</span>
                </div>
            </div>
            <div class="ajax-filter-target" id="enrollmentsAjaxTarget">
                @include('admin.pages.enrollments.partials.list')
            </div>
        </div>

        <div id="enrollmentsModalsHost">
            @include('admin.pages.enrollments.partials.modals')
        </div>

        @include('admin.partials.enrollments.grant-modal', ['modalId' => 'enrollmentGrantModal'])
    </div>
</div>
@stop

@push('scripts')
    @include('admin.partials.enrollments.grant-scripts')
@endpush
