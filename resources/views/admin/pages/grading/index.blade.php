@extends('admin.layouts.master')

@section('page-title')
    لوحة التصحيح
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            @include('admin.partials.ui.alerts')

            <div class="my-4 page-header-breadcrumb exam-page-animate dashboard-fade-in">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active">التصحيح</li>
                    </ol>
                </nav>
            </div>

            <div class="group-show-hero dashboard-fade-in exam-page-animate mb-4">
                <div class="row align-items-start g-3">
                    <div class="col-lg-8">
                        <span class="group-show-hero__eyebrow">
                            <i class="fe fe-edit-3 me-1"></i>
                            مراجعة الإجابات
                        </span>
                        <h2 class="group-show-hero__title mb-2">لوحة التصحيح</h2>
                        <p class="group-show-hero__desc mb-0">
                            مراجعة وتصحيح محاولات الاختبارات المُسلمة — الأسئلة المقالية واليدوية تحتاج تصحيحاً من المدرس.
                        </p>
                    </div>
                    <div class="col-lg-4">
                        <div class="group-show-actions">
                            <a href="{{ route('quiz-analytics.index') }}" class="group-show-action group-show-action--info">
                                <span class="group-show-action__icon"><i class="fe fe-bar-chart-2"></i></span>
                                <span class="group-show-action__text">تحليلات الاختبارات</span>
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
                @include('admin.pages.grading.partials.stats', ['stats' => $stats, 'attempts' => $attempts])
            </div>

            <div class="card custom-card group-show-members-card dashboard-fade-in exam-page-animate mb-4">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title mb-1">تصفية المحاولات</h4>
                    <p class="fs-12 text-muted mb-0">ابحث عن طالب أو فلتر حسب الاختبار وحالة التصحيح.</p>
                </div>
                <div class="card-body pt-3">
                    <form method="GET" action="{{ route('grading.index') }}" id="filterForm" class="group-show-filters mb-0">
                        <div class="row g-3 align-items-end">
                            <div class="col-xl-4 col-lg-4 col-md-6">
                                <label class="form-label">البحث عن طالب</label>
                                <input type="text" name="search" class="form-control"
                                       placeholder="ابحث باسم الطالب أو البريد الإلكتروني..."
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6">
                                <label class="form-label">الاختبار</label>
                                <select name="quiz_id" class="form-select">
                                    <option value="">جميع الاختبارات</option>
                                    @foreach($quizzes as $quiz)
                                        <option value="{{ $quiz->id }}" {{ request('quiz_id') == $quiz->id ? 'selected' : '' }}>
                                            {{ $quiz->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6">
                                <label class="form-label">حالة التصحيح</label>
                                <select name="grade_status" class="form-select">
                                    <option value="">الكل</option>
                                    <option value="not_graded" {{ request('grade_status') == 'not_graded' ? 'selected' : '' }}>لم يُصحح</option>
                                    <option value="partially_graded" {{ request('grade_status') == 'partially_graded' ? 'selected' : '' }}>مُصحح جزئياً</option>
                                    <option value="fully_graded" {{ request('grade_status') == 'fully_graded' ? 'selected' : '' }}>مُصحح بالكامل</option>
                                </select>
                            </div>
                            <div class="col-xl-12">
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fe fe-search me-1"></i>بحث
                                    </button>
                                    <a href="{{ route('grading.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fe fe-rotate-cw me-1"></i>إعادة تعيين
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card custom-card group-show-members-card dashboard-fade-in exam-page-animate">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 border-0 pb-0">
                    <h6 class="group-show-members-card__title mb-0">
                        المحاولات المُسلمة
                        <span class="group-show-members-card__count">{{ $attempts->total() }}</span>
                    </h6>
                </div>
                <div class="card-body pt-3 p-0">
                    <div class="table-responsive px-3 pb-3">
                        <table class="table table-hover align-middle text-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">الطالب</th>
                                    <th width="20%">الاختبار</th>
                                    <th width="8%">المحاولة</th>
                                    <th width="12%">تاريخ التسليم</th>
                                    <th width="10%">الدرجة</th>
                                    <th width="13%">حالة التصحيح</th>
                                    <th width="10%">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attempts as $attempt)
                                    <tr class="{{ $attempt->grade_status == 'not_graded' ? 'bg-danger-transparent' : '' }}">
                                        <td class="text-muted">{{ $loop->iteration + ($attempts->currentPage() - 1) * $attempts->perPage() }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="avatar avatar-sm bg-primary-transparent text-primary">
                                                    <i class="fe fe-user"></i>
                                                </span>
                                                <div>
                                                    <span class="fw-semibold d-block">{{ $attempt->student?->name ?? '—' }}</span>
                                                    <small class="text-muted">{{ $attempt->student?->email ?? '—' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($attempt->quiz)
                                                <span class="fw-semibold d-block">{{ $attempt->quiz->title }}</span>
                                                <small class="badge bg-primary-transparent">{{ $attempt->quiz->course?->title ?? '—' }}</small>
                                            @else
                                                <span class="fw-semibold text-muted">اختبار محذوف</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-transparent">#{{ $attempt->attempt_number }}</span>
                                        </td>
                                        <td class="text-muted fs-13">
                                            {{ $attempt->submitted_at ? $attempt->submitted_at->format('Y-m-d H:i') : '—' }}
                                        </td>
                                        <td>
                                            @if($attempt->total_score !== null)
                                                <span class="badge bg-{{ $attempt->passed ? 'success' : 'danger' }}-transparent text-{{ $attempt->passed ? 'success' : 'danger' }}">
                                                    {{ number_format($attempt->percentage_score, 1) }}%
                                                </span>
                                                <small class="text-muted d-block">{{ number_format($attempt->total_score, 1) }}/{{ $attempt->max_score }}</small>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attempt->grade_status == 'not_graded')
                                                <span class="badge bg-danger-transparent text-danger">
                                                    <i class="fe fe-alert-circle me-1"></i>لم يُصحح
                                                </span>
                                            @elseif($attempt->grade_status == 'partially_graded')
                                                <span class="badge bg-warning-transparent text-warning">
                                                    <i class="fe fe-layers me-1"></i>جزئي
                                                </span>
                                            @else
                                                <span class="badge bg-success-transparent text-success">
                                                    <i class="fe fe-check-circle me-1"></i>مُصحح
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('grading.show', $attempt->id) }}"
                                                   class="btn btn-sm btn-{{ $attempt->grade_status == 'not_graded' ? 'danger' : 'primary' }}"
                                                   title="تصحيح">
                                                    <i class="fe fe-edit-2"></i>
                                                </a>
                                                @if($attempt->grade_status == 'fully_graded')
                                                    <form action="{{ route('grading.regrade', $attempt->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning-light" title="إعادة تصحيح"
                                                                onclick="return confirm('هل تريد إعادة تصحيح هذه المحاولة؟')">
                                                            <i class="fe fe-rotate-cw"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="text-center py-5">
                                                <div class="admin-stats-card__icon-wrap mx-auto mb-3" style="width:4rem;height:4rem;">
                                                    <i class="fe fe-inbox admin-stats-card__icon"></i>
                                                </div>
                                                <h6 class="mb-1">لا توجد محاولات للتصحيح</h6>
                                                <p class="text-muted fs-13 mb-0">ستظهر المحاولات المُسلمة هنا عندما يكمل الطلاب الاختبارات.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($attempts->hasPages())
                        <div class="card-footer border-top">
                            {{ $attempts->links() }}
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
