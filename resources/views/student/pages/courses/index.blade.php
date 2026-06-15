@extends('student.layouts.master')

@section('page-title')
    كورساتي
@stop

@section('styles')
    @include('student.pages.courses.partials.page-styles')
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'كورساتي'],
            ],
            'title' => 'كورساتي',
            'subtitle' => 'جميع الكورسات المسجّل فيها وتقدّمك في كل منها',
            'actions' => '<a href="' . route('courses') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-compass-3-line me-1 fs-18"></i> اكتشف كورسات جديدة</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple',
                'icon' => 'ri-book-open-line',
                'label' => 'إجمالي التسجيلات',
                'value' => number_format($stats['total']),
                'hint' => 'كل الكورسات المسجّلة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green',
                'icon' => 'ri-play-circle-line',
                'label' => 'نشطة',
                'value' => number_format($stats['active']),
                'hint' => 'قيد التعلم حالياً',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan',
                'icon' => 'ri-loader-4-line',
                'label' => 'قيد التقدم',
                'value' => number_format($stats['in_progress']),
                'hint' => 'بدأت ولم تُكمل بعد',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange',
                'icon' => 'ri-award-line',
                'label' => 'مكتملة',
                'value' => number_format($stats['completed']),
                'hint' => 'أتممتها بنجاح',
            ])
        </div>

        <div class="filter-panel mb-4">
            <div class="filter-panel__title">تصفية الكورسات</div>
            <div class="filter-panel__subtitle">ابحث باسم الكورس أو فلتر حسب حالة التسجيل</div>
            <form action="{{ route('student.courses.index') }}" method="GET">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-md-5 col-lg-4">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="search" class="form-control" placeholder="ابحث باسم الكورس..."
                                   value="{{ request('search') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="">كل الحالات</option>
                            @foreach(['active' => 'نشط', 'completed' => 'مكتمل', 'pending' => 'قيد الانتظار', 'expired' => 'منتهي', 'cancelled' => 'ملغى'] as $value => $label)
                                <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill btn-wave">
                            <i class="ri-filter-3-line me-1"></i>تصفية
                        </button>
                        <a href="{{ route('student.courses.index') }}" class="btn btn-light border btn-wave" title="مسح الفلاتر">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        @if($enrollments->isNotEmpty())
            <div class="row g-3 mb-4">
                @foreach($enrollments as $enrollment)
                    @include('student.pages.courses.partials.enrollment-card', ['enrollment' => $enrollment])
                @endforeach
            </div>

            <div class="d-flex justify-content-center">
                {{ $enrollments->links() }}
            </div>
        @else
            <div class="card custom-card">
                <div class="card-body text-center py-5">
                    <div class="empty-state-icon mx-auto mb-3"><i class="ri-graduation-cap-line"></i></div>
                    <h5 class="mb-2">لا توجد كورسات</h5>
                    <p class="text-muted fs-13 mb-3">
                        @if(request()->hasAny(['search', 'status']))
                            لم نجد نتائج تطابق البحث أو الفلتر الحالي.
                        @else
                            لم تسجّل في أي كورس بعد. ابدأ رحلة التعلم الآن!
                        @endif
                    </p>
                    <a href="{{ route('courses') }}" class="btn btn-primary btn-wave rounded-pill">
                        <i class="ri-compass-3-line me-1"></i>تصفح الكورسات
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@stop
