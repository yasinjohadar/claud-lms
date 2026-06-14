@extends('admin.layouts.master')

@section('page-title')
    تحليلات الاختبارات
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        <div class="my-4 page-header-breadcrumb exam-page-animate dashboard-fade-in">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item active">تحليلات الاختبارات</li>
                </ol>
            </nav>
        </div>

        <div class="group-show-hero dashboard-fade-in exam-page-animate mb-4">
            <div class="row align-items-start g-3">
                <div class="col-lg-8">
                    <span class="group-show-hero__eyebrow">
                        <i class="fe fe-bar-chart-2 me-1"></i>
                        تحليلات الأداء
                    </span>
                    <h2 class="group-show-hero__title mb-2">تحليلات الاختبارات</h2>
                    <p class="group-show-hero__desc mb-0">
                        عرض شامل لأداء الاختبارات والطلاب — متوسط الدرجات، أفضل الطلاب، والاختبارات الأكثر صعوبة.
                    </p>
                </div>
                <div class="col-lg-4">
                    <div class="group-show-actions">
                        <a href="{{ route('grading.index') }}" class="group-show-action group-show-action--warning">
                            <span class="group-show-action__icon"><i class="fe fe-edit-3"></i></span>
                            <span class="group-show-action__text">لوحة التصحيح</span>
                        </a>
                        <a href="{{ route('quizzes.index') }}" class="group-show-action group-show-action--primary">
                            <span class="group-show-action__icon"><i class="fe fe-clipboard"></i></span>
                            <span class="group-show-action__text">إدارة الاختبارات</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4 exam-page-animate">
            @include('admin.pages.analytics.partials.stats', ['stats' => $stats])
        </div>

        <div class="row g-4 exam-page-animate">
            <div class="col-lg-6">
                <div class="card custom-card group-show-members-card dashboard-fade-in h-100">
                    <div class="card-header border-0 pb-0">
                        <h6 class="group-show-members-card__title mb-0">
                            <i class="fe fe-award text-warning me-2"></i>أفضل الطلاب
                        </h6>
                    </div>
                    <div class="card-body pt-3">
                        @if($topStudents->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>الطالب</th>
                                            <th class="text-center">المحاولات</th>
                                            <th class="text-center">متوسط الدرجة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topStudents as $studentData)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-primary-transparent rounded-pill px-2">{{ $loop->iteration }}</span>
                                                        <span class="fw-semibold">{{ $studentData->name }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $studentData->attempts_count }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-success-transparent text-success">
                                                        {{ number_format($studentData->average_score, 1) }}%
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="admin-stats-card__icon-wrap mx-auto mb-3" style="width:3.5rem;height:3.5rem;">
                                    <i class="fe fe-award admin-stats-card__icon"></i>
                                </div>
                                <h6 class="mb-1">لا توجد بيانات متاحة</h6>
                                <p class="text-muted fs-13 mb-0">ستظهر أفضل الطلاب عند اكتمال محاولات الاختبارات.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card custom-card group-show-members-card dashboard-fade-in h-100">
                    <div class="card-header border-0 pb-0">
                        <h6 class="group-show-members-card__title mb-0">
                            <i class="fe fe-alert-triangle text-danger me-2"></i>الاختبارات الأكثر صعوبة
                        </h6>
                    </div>
                    <div class="card-body pt-3">
                        @if($difficultQuizzes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>الاختبار</th>
                                            <th class="text-center">المحاولات</th>
                                            <th class="text-center">متوسط الدرجة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($difficultQuizzes as $quizData)
                                            <tr>
                                                <td class="fw-semibold">{{ $quizData->title }}</td>
                                                <td class="text-center">{{ $quizData->attempts_count }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-danger-transparent text-danger">
                                                        {{ number_format($quizData->average_score, 1) }}%
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="admin-stats-card__icon-wrap mx-auto mb-3" style="width:3.5rem;height:3.5rem;">
                                    <i class="fe fe-alert-triangle admin-stats-card__icon"></i>
                                </div>
                                <h6 class="mb-1">لا توجد بيانات متاحة</h6>
                                <p class="text-muted fs-13 mb-0">ستظهر الاختبارات الصعبة بعد تسجيل محاولات كافية.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card custom-card group-show-members-card dashboard-fade-in exam-page-animate mt-4">
            <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="group-show-members-card__title mb-0">
                    <i class="fe fe-clock text-info me-2"></i>آخر المحاولات
                </h6>
            </div>
            <div class="card-body pt-3">
                @if($recentAttempts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>الطالب</th>
                                    <th>الاختبار / الوحدة</th>
                                    <th class="text-center">الدرجة</th>
                                    <th class="text-center">الحالة</th>
                                    <th class="text-center">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttempts as $attempt)
                                    <tr>
                                        <td class="fw-semibold">{{ $attempt['student']->name ?? '—' }}</td>
                                        <td>
                                            {{ $attempt['title'] }}
                                            @if($attempt['type'] === 'module')
                                                <span class="badge bg-info-transparent text-info ms-1">وحدة</span>
                                            @else
                                                <span class="badge bg-secondary-transparent ms-1">اختبار</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($attempt['is_completed'] && $attempt['score'] !== null)
                                                <span class="badge bg-{{ $attempt['score'] >= 60 ? 'success' : 'danger' }}-transparent text-{{ $attempt['score'] >= 60 ? 'success' : 'danger' }}">
                                                    {{ number_format($attempt['score'], 1) }}%
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($attempt['is_completed'])
                                                <span class="badge bg-success-transparent text-success">مكتمل</span>
                                            @else
                                                <span class="badge bg-warning-transparent text-warning">جاري</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-muted fs-13">
                                            {{ $attempt['started_at']->format('Y-m-d H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="admin-stats-card__icon-wrap mx-auto mb-3" style="width:3.5rem;height:3.5rem;">
                            <i class="fe fe-clock admin-stats-card__icon"></i>
                        </div>
                        <h6 class="mb-1">لا توجد محاولات حديثة</h6>
                        <p class="text-muted fs-13 mb-0">ستظهر آخر محاولات الطلاب هنا عند بدء الاختبارات.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@stop

@section('script')
    @include('admin.partials.ui.stats-countup')
@stop
