@extends('admin.layouts.master')

@section('page-title')
    تحليلات الاختبار: {{ $quiz->title }}
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        <div class="my-4 page-header-breadcrumb exam-page-animate dashboard-fade-in">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('quiz-analytics.index') }}">تحليلات الاختبارات</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($quiz->title, 40) }}</li>
                </ol>
            </nav>
        </div>

        <div class="group-show-hero dashboard-fade-in exam-page-animate mb-4">
            <div class="row align-items-start g-3">
                <div class="col-lg-8">
                    <span class="group-show-hero__eyebrow">
                        <i class="fe fe-bar-chart-2 me-1"></i>
                        تحليل اختبار
                    </span>
                    <h2 class="group-show-hero__title mb-2">{{ $quiz->title }}</h2>
                    @if($quiz->description)
                        <p class="group-show-hero__desc mb-3">{{ Str::limit(strip_tags($quiz->description), 160) }}</p>
                    @else
                        <p class="group-show-hero__desc mb-3">تقرير تفصيلي لأداء الطلاب وتحليل الأسئلة وتوزيع الدرجات.</p>
                    @endif
                    <div class="d-flex flex-wrap gap-2">
                        @if($quiz->course)
                            <span class="badge bg-info-transparent text-info rounded-pill px-3 py-2">
                                <i class="fe fe-book me-1"></i>{{ $quiz->course->title }}
                            </span>
                        @endif
                        <span class="badge bg-primary-transparent text-primary rounded-pill px-3 py-2">
                            <i class="fe fe-help-circle me-1"></i>{{ $quiz->quizQuestions->count() }} أسئلة
                        </span>
                        @if($quiz->time_limit)
                            <span class="badge bg-warning-transparent text-warning rounded-pill px-3 py-2">
                                <i class="fe fe-clock me-1"></i>{{ $quiz->time_limit }} دقيقة
                            </span>
                        @endif
                        @if($quiz->passing_grade)
                            <span class="badge bg-success-transparent text-success rounded-pill px-3 py-2">
                                <i class="fe fe-target me-1"></i>النجاح {{ number_format($quiz->passing_grade, 0) }}%
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="group-show-actions">
                        <a href="{{ route('quizzes.show', $quiz->id) }}" class="group-show-action group-show-action--info">
                            <span class="group-show-action__icon"><i class="fe fe-eye"></i></span>
                            <span class="group-show-action__text">عرض الاختبار</span>
                        </a>
                        <a href="{{ route('grading.index', ['quiz_id' => $quiz->id]) }}" class="group-show-action group-show-action--warning">
                            <span class="group-show-action__icon"><i class="fe fe-edit-3"></i></span>
                            <span class="group-show-action__text">التصحيح</span>
                        </a>
                        <a href="{{ route('quiz-analytics.index') }}" class="group-show-action group-show-action--primary">
                            <span class="group-show-action__icon"><i class="fe fe-arrow-right"></i></span>
                            <span class="group-show-action__text">كل التحليلات</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4 exam-page-animate">
            @include('admin.pages.analytics.partials.quiz-stats', ['stats' => $stats])
        </div>

        <div class="row g-4 exam-page-animate">
            {{-- توزيع الدرجات --}}
            <div class="col-lg-6">
                <div class="card custom-card group-show-members-card dashboard-fade-in h-100">
                    <div class="card-header border-0 pb-0">
                        <h6 class="group-show-members-card__title mb-0">
                            <i class="fe fe-bar-chart text-primary me-2"></i>توزيع الدرجات
                        </h6>
                    </div>
                    <div class="card-body pt-3">
                        @if($scoreDistribution && count($scoreDistribution) > 0 && array_sum($scoreDistribution) > 0)
                            @php $distTotal = array_sum($scoreDistribution); @endphp
                            <div class="d-flex flex-column gap-3">
                                @foreach($scoreDistribution as $range => $count)
                                    @php
                                        $pct = $distTotal > 0 ? ($count / $distTotal) * 100 : 0;
                                        $barClass = match(true) {
                                            str_starts_with($range, '0-') || str_starts_with($range, '21-') => 'bg-danger',
                                            str_starts_with($range, '41-') => 'bg-warning',
                                            str_starts_with($range, '61-') => 'bg-info',
                                            default => 'bg-success',
                                        };
                                    @endphp
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-semibold fs-13">{{ $range }}%</span>
                                            <span class="text-muted fs-12">{{ $count }} طالب · {{ number_format($pct, 1) }}%</span>
                                        </div>
                                        <div class="progress rounded-pill" style="height: 10px;">
                                            <div class="progress-bar {{ $barClass }} rounded-pill" style="width: {{ max($pct, $count > 0 ? 4 : 0) }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="admin-stats-card__icon-wrap mx-auto mb-3" style="width:3.5rem;height:3.5rem;">
                                    <i class="fe fe-bar-chart admin-stats-card__icon"></i>
                                </div>
                                <h6 class="mb-1">لا توجد بيانات</h6>
                                <p class="text-muted fs-13 mb-0">سيظهر توزيع الدرجات بعد اكتمال محاولات الطلاب.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- تحليل الأسئلة --}}
            <div class="col-lg-6">
                <div class="card custom-card group-show-members-card dashboard-fade-in h-100">
                    <div class="card-header border-0 pb-0">
                        <h6 class="group-show-members-card__title mb-0">
                            <i class="fe fe-help-circle text-info me-2"></i>تحليل الأسئلة
                        </h6>
                    </div>
                    <div class="card-body pt-3">
                        @if($questionAnalysis && $questionAnalysis->count() > 0)
                            <div class="table-responsive" style="max-height: 420px;">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th style="min-width: 200px;">السؤال</th>
                                            <th class="text-center" style="width: 120px;">معدل النجاح</th>
                                            <th class="text-center" style="width: 90px;">الإجابات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($questionAnalysis as $index => $analysis)
                                            @php
                                                $successRate = $analysis['success_rate'] ?? 0;
                                                $badgeClass = $successRate >= 70 ? 'bg-success-transparent text-success'
                                                    : ($successRate >= 50 ? 'bg-warning-transparent text-warning' : 'bg-danger-transparent text-danger');
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-start gap-2">
                                                        <span class="badge bg-primary-transparent text-primary rounded-pill flex-shrink-0">{{ $index + 1 }}</span>
                                                        <div class="min-w-0">
                                                            <div class="fw-semibold fs-13 text-truncate" style="max-width: 280px;" title="{{ strip_tags($analysis['question']->question_text ?? '') }}">
                                                                {{ Str::limit(strip_tags($analysis['question']->question_text ?? ''), 55) }}
                                                            </div>
                                                            <small class="text-muted">{{ $analysis['question']->questionType->display_name ?? 'غير معروف' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge {{ $badgeClass }} rounded-pill px-2">
                                                        {{ number_format($successRate, 1) }}%
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="text-muted fs-12">
                                                        {{ $analysis['correct_responses'] ?? 0 }}/{{ $analysis['total_responses'] ?? 0 }}
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
                                    <i class="fe fe-help-circle admin-stats-card__icon"></i>
                                </div>
                                <h6 class="mb-1">لا توجد بيانات</h6>
                                <p class="text-muted fs-13 mb-0">ستظهر نسب نجاح كل سؤال بعد تصحيح المحاولات.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- أداء الطلاب --}}
        <div class="card custom-card group-show-members-card dashboard-fade-in exam-page-animate mt-4">
            <div class="card-header border-0 pb-0">
                <h6 class="group-show-members-card__title mb-0">
                    <i class="fe fe-users text-success me-2"></i>أداء الطلاب
                </h6>
            </div>
            <div class="card-body pt-3">
                @if($studentPerformance && $studentPerformance->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>الطالب</th>
                                    <th class="text-center">المحاولات</th>
                                    <th class="text-center">أفضل درجة</th>
                                    <th class="text-center">متوسط الدرجة</th>
                                    <th class="text-center">التحسن</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($studentPerformance as $performance)
                                    @php
                                        $improvement = $performance['improvement'] ?? 0;
                                        $impClass = $improvement > 0 ? 'text-success' : ($improvement < 0 ? 'text-danger' : 'text-muted');
                                        $impIcon = $improvement > 0 ? 'fe-trending-up' : ($improvement < 0 ? 'fe-trending-down' : 'fe-minus');
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary-transparent text-primary rounded-pill">{{ $loop->iteration }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $performance['student']->name ?? 'غير معروف' }}</span>
                                        </td>
                                        <td class="text-center">{{ $performance['attempts_count'] ?? 0 }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-success-transparent text-success rounded-pill">
                                                {{ number_format($performance['best_score'] ?? 0, 1) }}%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-transparent text-info rounded-pill">
                                                {{ number_format($performance['average_score'] ?? 0, 1) }}%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="{{ $impClass }} fw-semibold fs-13">
                                                <i class="fe {{ $impIcon }} me-1"></i>{{ number_format($improvement, 1) }}%
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
                            <i class="fe fe-users admin-stats-card__icon"></i>
                        </div>
                        <h6 class="mb-1">لا يوجد طلاب بعد</h6>
                        <p class="text-muted fs-13 mb-0">ستظهر بيانات الأداء عندما يكمل الطلاب محاولات هذا الاختبار.</p>
                    </div>
                @endif
            </div>
        </div>

        @if($attemptTrends && $attemptTrends->count() > 0)
        <div class="card custom-card group-show-members-card dashboard-fade-in exam-page-animate mt-4 mb-0">
            <div class="card-header border-0 pb-0">
                <h6 class="group-show-members-card__title mb-0">
                    <i class="fe fe-activity text-warning me-2"></i>اتجاهات المحاولات
                </h6>
            </div>
            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>التاريخ</th>
                                <th class="text-center">عدد المحاولات</th>
                                <th class="text-center">متوسط الدرجة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attemptTrends as $trend)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($trend->date)->translatedFormat('d M Y') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-transparent text-primary rounded-pill">{{ $trend->count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info-transparent text-info rounded-pill">
                                            {{ number_format($trend->avg_score ?? 0, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@section('script')
    @include('admin.partials.ui.stats-countup')
@stop
