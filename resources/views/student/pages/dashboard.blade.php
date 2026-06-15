@extends('student.layouts.master')

@section('page-title')
    لوحة التحكم
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.dashboard-welcome', ['roleLabel' => 'طالب'])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple',
                'icon' => 'ri-graduation-cap-line',
                'label' => 'الكورسات النشطة',
                'value' => number_format($stats['active_courses']),
                'hint' => 'مسجّل فيها حالياً',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green',
                'icon' => 'ri-line-chart-line',
                'label' => 'متوسط التقدم',
                'value' => $stats['avg_progress'] . '%',
                'hint' => 'عبر الكورسات النشطة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan',
                'icon' => 'ri-shopping-bag-line',
                'label' => 'الطلبات',
                'value' => number_format($stats['orders_count']),
                'hint' => 'إجمالي طلباتك',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange',
                'icon' => 'ri-award-line',
                'label' => 'كورسات مكتملة',
                'value' => number_format($stats['completed_courses']),
                'hint' => 'أتممتها بنجاح',
            ])
        </div>

        @if(!empty($gamification))
            <div class="card custom-card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <span class="fw-bold"><i class="ri-trophy-line text-warning me-1"></i> ملخص التحفيز</span>
                    <a href="{{ route('gamification.dashboard') }}" class="btn btn-sm btn-warning-light">لوحة التلعيب</a>
                </div>
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3 col-6 text-center">
                            <div class="fw-bold fs-22 text-primary">{{ number_format($gamification['available_points'] ?? 0) }}</div>
                            <div class="text-muted fs-12">نقاط متاحة</div>
                        </div>
                        <div class="col-md-3 col-6 text-center">
                            <div class="fw-bold fs-22 text-warning">{{ $gamification['current_level'] ?? 1 }}</div>
                            <div class="text-muted fs-12">المستوى</div>
                        </div>
                        <div class="col-md-3 col-6 text-center">
                            <div class="fw-bold fs-22 text-success">{{ $gamification['current_streak'] ?? 0 }}</div>
                            <div class="text-muted fs-12">سلسلة الأيام</div>
                        </div>
                        <div class="col-md-3 col-6 text-center">
                            <div class="fw-bold fs-22 text-info">{{ $gamification['total_badges'] ?? 0 }}</div>
                            <div class="text-muted fs-12">شارة</div>
                        </div>
                    </div>
                    @if(isset($gamification['level_progress']))
                        <div class="progress rounded-pill mt-3" style="height:8px;">
                            <div class="progress-bar bg-warning" style="width:{{ min(100, (float) $gamification['level_progress']) }}%"></div>
                        </div>
                        <p class="text-muted fs-12 text-center mt-2 mb-0">تقدم المستوى {{ round((float) $gamification['level_progress']) }}%</p>
                    @endif
                </div>
            </div>
        @endif

        <div class="shortcut-section mb-4">
            <div class="shortcut-section__header mb-3">
                <h5 class="shortcut-section__title mb-1">
                    <i class="ri-flashlight-line text-warning"></i>
                    اختصارات سريعة
                </h5>
                <p class="shortcut-section__subtitle mb-0">انتقل مباشرة إلى أقسام التعلم</p>
            </div>
            <div class="row g-3 shortcut-grid">
                @include('admin.partials.ui.shortcut-card', [
                    'url' => route('student.courses.index'),
                    'title' => 'كورساتي',
                    'description' => 'عرض الكورسات المسجّل فيها',
                    'icon' => 'ri-graduation-cap-line',
                    'icon_color' => 'primary',
                ])
                @include('admin.partials.ui.shortcut-card', [
                    'url' => route('student.quizzes.index'),
                    'title' => 'الاختبارات',
                    'description' => 'الاختبارات المتاحة والمحاولات',
                    'icon' => 'ri-questionnaire-line',
                    'icon_color' => 'success',
                ])
                @include('admin.partials.ui.shortcut-card', [
                    'url' => route('gamification.dashboard'),
                    'title' => 'التلعيب',
                    'description' => 'النقاط والشارات والتحديات',
                    'icon' => 'ri-trophy-line',
                    'icon_color' => 'warning',
                ])
                @include('admin.partials.ui.shortcut-card', [
                    'url' => route('courses'),
                    'title' => 'تصفح الكورسات',
                    'description' => 'اكتشف كورسات جديدة',
                    'icon' => 'ri-compass-3-line',
                    'icon_color' => 'info',
                ])
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card custom-card">
                    <div class="card-header border-0 pb-0">
                        <h5 class="card-title mb-1">آخر التسجيلات</h5>
                        <p class="text-muted fs-12 mb-0">أحدث الكورسات التي سجّلت فيها</p>
                    </div>
                    <div class="card-body pt-3">
                        @if($recentEnrollments->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>الكورس</th>
                                            <th>التقدم</th>
                                            <th>تاريخ التسجيل</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentEnrollments as $enrollment)
                                            @php $course = $enrollment->course; @endphp
                                            <tr>
                                                <td class="fw-semibold">{{ $course?->title ?? '—' }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="progress flex-fill" style="height: 6px; min-width: 80px;">
                                                            <div class="progress-bar bg-success" style="width: {{ $enrollment->progress_percent ?? 0 }}%"></div>
                                                        </div>
                                                        <span class="fs-12 text-muted">{{ $enrollment->progress_percent ?? 0 }}%</span>
                                                    </div>
                                                </td>
                                                <td class="text-muted fs-13">
                                                    {{ $enrollment->enrolled_at?->format('Y-m-d') ?? '—' }}
                                                </td>
                                                <td>
                                                    @if($course?->slug)
                                                        <a href="{{ route('student.courses.show', $course->slug) }}" class="btn btn-sm btn-primary-light">
                                                            متابعة
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ri-book-open-line fs-2 text-muted d-block mb-2"></i>
                                <p class="text-muted mb-2">لم تسجّل في أي كورس بعد</p>
                                <a href="{{ route('courses') }}" class="btn btn-sm btn-primary">تصفح الكورسات</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card custom-card h-100">
                    <div class="card-header border-0 pb-0">
                        <h5 class="card-title mb-1">ملخص سريع</h5>
                    </div>
                    <div class="card-body pt-3">
                        <ul class="list-unstyled mb-0 fs-13">
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">الاسم</span>
                                <span class="fw-semibold">{{ $student->user?->name ?? auth()->user()->name }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">البريد</span>
                                <span class="fw-semibold text-truncate ms-2" style="max-width: 160px;">{{ auth()->user()->email }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2">
                                <span class="text-muted">الحالة</span>
                                <span class="badge bg-success-transparent text-success">نشط</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop
