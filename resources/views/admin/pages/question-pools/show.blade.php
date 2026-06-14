@extends('admin.layouts.master')

@section('page-title')
    {{ $pool->name }}
@stop

@section('styles')
    @include('admin.pages.assignments.partials.page-styles')
    @include('admin.pages.question-pools.partials.page-styles')
@stop

@php
    $totalQuestions = $stats['total_questions'] ?? $pool->questions->count();
    $difficultyCounts = collect($stats['by_difficulty'] ?? [])->keyBy('difficulty');
    $easyCount = $difficultyCounts->get('easy')?->count ?? $pool->questions->where('difficulty_level', 'easy')->count();
    $mediumCount = $difficultyCounts->get('medium')?->count ?? $pool->questions->where('difficulty_level', 'medium')->count();
    $hardCount = $difficultyCounts->get('hard')?->count ?? $pool->questions->where('difficulty_level', 'hard')->count();
@endphp

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            @include('admin.partials.ui.alerts')

            <div class="my-4 page-header-breadcrumb exam-page-animate dashboard-fade-in">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('question-pools.index') }}">مجموعات الأسئلة</a></li>
                        <li class="breadcrumb-item active">{{ Str::limit($pool->name, 40) }}</li>
                    </ol>
                </nav>
            </div>

            <div class="group-show-hero dashboard-fade-in exam-page-animate mb-4">
                <div class="row align-items-start g-3">
                    <div class="col-lg-8">
                        <span class="group-show-hero__eyebrow">
                            <i class="fe fe-layers me-1"></i>
                            مجموعة أسئلة
                        </span>
                        <h2 class="group-show-hero__title mb-2">{{ $pool->name }}</h2>
                        @if($pool->description)
                            <p class="group-show-hero__desc mb-3">{{ $pool->description }}</p>
                        @else
                            <p class="group-show-hero__desc mb-3">عرض تفاصيل المجموعة والأسئلة المرتبطة بها.</p>
                        @endif
                        <div class="d-flex flex-wrap gap-2">
                            @if($pool->course)
                                <span class="badge bg-info-transparent text-info rounded-pill px-3 py-2">
                                    <i class="fe fe-book me-1"></i>{{ $pool->course->title }}
                                </span>
                            @endif
                            <span class="badge bg-primary-transparent text-primary rounded-pill px-3 py-2">
                                <i class="fe fe-help-circle me-1"></i>{{ $totalQuestions }} سؤال
                            </span>
                            @if($pool->questions_per_quiz)
                                <span class="badge bg-warning-transparent text-warning rounded-pill px-3 py-2">
                                    <i class="fe fe-shuffle me-1"></i>{{ $pool->questions_per_quiz }} سؤال/اختبار
                                </span>
                            @endif
                            @if($pool->creator)
                                <span class="badge bg-secondary-transparent text-secondary rounded-pill px-3 py-2">
                                    <i class="fe fe-user me-1"></i>{{ $pool->creator->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="group-show-actions">
                            <a href="{{ route('question-pools.edit', $pool->id) }}" class="group-show-action group-show-action--warning">
                                <span class="group-show-action__icon"><i class="fe fe-edit-2"></i></span>
                                <span class="group-show-action__text">تعديل المجموعة</span>
                            </a>
                            <a href="{{ route('question-bank.index') }}" class="group-show-action group-show-action--info">
                                <span class="group-show-action__icon"><i class="fe fe-database"></i></span>
                                <span class="group-show-action__text">بنك الأسئلة</span>
                            </a>
                            <a href="{{ route('question-pools.index') }}" class="group-show-action">
                                <span class="group-show-action__icon"><i class="fe fe-arrow-right"></i></span>
                                <span class="group-show-action__text">العودة للقائمة</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4 exam-page-animate">
                @include('admin.pages.question-pools.partials.show-stats', ['pool' => $pool, 'stats' => $stats])
            </div>

            <div class="row g-4 exam-page-animate">
                <div class="col-lg-8">
                    {{-- معلومات المجموعة --}}
                    <div class="card custom-card group-show-members-card dashboard-fade-in mb-4">
                        <div class="card-header border-0 pb-0">
                            <h4 class="card-title mb-1 d-flex align-items-center gap-2">
                                <span class="assignments-section-icon"><i class="fe fe-info"></i></span>
                                معلومات المجموعة
                            </h4>
                        </div>
                        <div class="card-body pt-3">
                            <div class="assignments-info-grid">
                                <div class="assignments-info-item">
                                    <div class="assignments-info-item__label">الكورس</div>
                                    <div class="assignments-info-item__value">
                                        <span class="assignments-course-chip">{{ $pool->course->title ?? '—' }}</span>
                                    </div>
                                </div>
                                <div class="assignments-info-item">
                                    <div class="assignments-info-item__label">عدد الأسئلة لكل اختبار</div>
                                    <div class="assignments-info-item__value fw-semibold">
                                        {{ $pool->questions_per_quiz ?? 'جميع الأسئلة' }}
                                    </div>
                                </div>
                                <div class="assignments-info-item">
                                    <div class="assignments-info-item__label">إجمالي الدرجات</div>
                                    <div class="assignments-info-item__value">
                                        <span class="assignments-grade-chip">{{ number_format($stats['total_points'] ?? 0, 0) }}</span>
                                    </div>
                                </div>
                                <div class="assignments-info-item">
                                    <div class="assignments-info-item__label">تاريخ الإنشاء</div>
                                    <div class="assignments-info-item__value">
                                        {{ $pool->created_at->format('Y-m-d') }}
                                        <small class="text-muted d-block">{{ $pool->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- جدول الأسئلة --}}
                    <div class="card custom-card group-show-members-card dashboard-fade-in mb-4">
                        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 border-0 pb-0">
                            <h6 class="group-show-members-card__title mb-0">
                                الأسئلة
                                <span class="group-show-members-card__count">{{ $totalQuestions }}</span>
                            </h6>
                            <div class="d-flex align-items-center gap-2">
                                <select id="filter-difficulty" class="form-select form-select-sm" style="min-width: 140px;">
                                    <option value="">جميع المستويات</option>
                                    <option value="easy">سهل</option>
                                    <option value="medium">متوسط</option>
                                    <option value="hard">صعب</option>
                                </select>
                                <a href="{{ route('question-pools.edit', $pool->id) }}" class="btn btn-success-light btn-sm">
                                    <i class="fe fe-plus me-1"></i>إدارة الأسئلة
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-3 p-0">
                            @if($totalQuestions > 0)
                                <div class="table-responsive px-3 pb-3">
                                    <table class="table table-hover text-nowrap dashboard-table mb-0">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th>نص السؤال</th>
                                                <th>النوع</th>
                                                <th>الصعوبة</th>
                                                <th>الدرجة</th>
                                                <th>الحالة</th>
                                                <th width="90">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pool->questions->sortBy('pivot.order') as $index => $question)
                                                <tr class="question-row" data-difficulty="{{ $question->difficulty_level }}">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <a href="{{ route('question-bank.show', $question->id) }}" class="qp-question-link">
                                                            {{ Str::limit(strip_tags($question->question_text), 70) }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info-transparent">
                                                            {{ $question->questionType->display_name ?? '—' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($question->difficulty_level === 'easy')
                                                            <span class="badge bg-success-transparent text-success">سهل</span>
                                                        @elseif($question->difficulty_level === 'medium')
                                                            <span class="badge bg-warning-transparent text-warning">متوسط</span>
                                                        @else
                                                            <span class="badge bg-danger-transparent text-danger">صعب</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary-transparent">{{ $question->default_grade }}</span>
                                                    </td>
                                                    <td>
                                                        @if($question->is_active)
                                                            <span class="assignments-status-chip assignments-status-chip--published">نشط</span>
                                                        @else
                                                            <span class="assignments-status-chip assignments-status-chip--draft">غير نشط</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <a href="{{ route('question-bank.show', $question->id) }}"
                                                               class="btn btn-icon btn-sm btn-info-light"
                                                               data-bs-toggle="tooltip"
                                                               title="عرض">
                                                                <i class="fe fe-eye"></i>
                                                            </a>
                                                            <a href="{{ route('question-bank.edit', $question->id) }}"
                                                               class="btn btn-icon btn-sm btn-warning-light"
                                                               data-bs-toggle="tooltip"
                                                               title="تعديل">
                                                                <i class="fe fe-edit-2"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5 px-3">
                                    <div class="admin-stats-card__icon-wrap mx-auto mb-3" style="width:4rem;height:4rem;">
                                        <i class="fe fe-help-circle admin-stats-card__icon"></i>
                                    </div>
                                    <h6 class="mb-1">لا توجد أسئلة في هذه المجموعة</h6>
                                    <p class="text-muted fs-13 mb-3">أضف أسئلة من بنك الأسئلة عبر صفحة التعديل.</p>
                                    <a href="{{ route('question-pools.edit', $pool->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fe fe-plus me-1"></i>إضافة أسئلة
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- توزيع حسب النوع --}}
                    @if($totalQuestions > 0)
                        <div class="card custom-card group-show-members-card dashboard-fade-in mb-4">
                            <div class="card-header border-0 pb-0">
                                <h6 class="group-show-members-card__title mb-0">
                                    <i class="fe fe-pie-chart text-primary me-2"></i>توزيع الأسئلة حسب النوع
                                </h6>
                            </div>
                            <div class="card-body pt-3">
                                <div class="row g-3">
                                    @foreach(($stats['by_type'] ?? collect()) as $typeRow)
                                        @php
                                            $pct = $totalQuestions > 0 ? ($typeRow->count / $totalQuestions) * 100 : 0;
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="qp-type-stat">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div>
                                                        <p class="mb-0 fw-semibold fs-13">{{ $typeRow->display_name }}</p>
                                                        <small class="text-muted">{{ $typeRow->count }} سؤال · {{ number_format($typeRow->total_points ?? 0, 0) }} درجة</small>
                                                    </div>
                                                    <span class="badge bg-info-transparent">{{ number_format($pct, 0) }}%</span>
                                                </div>
                                                <div class="progress rounded-pill" style="height: 8px;">
                                                    <div class="progress-bar bg-info rounded-pill" style="width: {{ max($pct, $typeRow->count > 0 ? 4 : 0) }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- الشريط الجانبي --}}
                <div class="col-lg-4">
                    {{-- توزيع الصعوبة --}}
                    <div class="card custom-card group-show-members-card dashboard-fade-in mb-4">
                        <div class="card-header border-0 pb-0">
                            <h6 class="group-show-members-card__title mb-0">
                                <i class="fe fe-bar-chart-2 text-success me-2"></i>توزيع الصعوبة
                            </h6>
                        </div>
                        <div class="card-body pt-3">
                            @foreach([
                                ['key' => 'easy', 'label' => 'سهل', 'count' => $easyCount, 'bar' => 'bg-success', 'text' => 'text-success'],
                                ['key' => 'medium', 'label' => 'متوسط', 'count' => $mediumCount, 'bar' => 'bg-warning', 'text' => 'text-warning'],
                                ['key' => 'hard', 'label' => 'صعب', 'count' => $hardCount, 'bar' => 'bg-danger', 'text' => 'text-danger'],
                            ] as $diff)
                                @php $pct = $totalQuestions > 0 ? ($diff['count'] / $totalQuestions) * 100 : 0; @endphp
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold fs-13 {{ $diff['text'] }}">{{ $diff['label'] }}</span>
                                        <span class="text-muted fs-12">{{ $diff['count'] }} · {{ number_format($pct, 0) }}%</span>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 8px;">
                                        <div class="progress-bar {{ $diff['bar'] }} rounded-pill" style="width: {{ max($pct, $diff['count'] > 0 ? 4 : 0) }}%"></div>
                                    </div>
                                </div>
                            @endforeach

                            <hr class="my-3">

                            <div class="d-flex flex-column gap-2 fs-13">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">أقل درجة</span>
                                    <strong>{{ $pool->questions->min('default_grade') ?? 0 }}</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">أعلى درجة</span>
                                    <strong>{{ $pool->questions->max('default_grade') ?? 0 }}</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">متوسط الدرجات</span>
                                    <strong>{{ number_format($stats['average_points'] ?? ($pool->questions->avg('default_grade') ?? 0), 1) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- الاختبارات المستخدمة --}}
                    @if(($pool->quizzes?->count() ?? 0) > 0)
                        <div class="card custom-card group-show-members-card dashboard-fade-in mb-4">
                            <div class="card-header border-0 pb-0">
                                <h6 class="group-show-members-card__title mb-0">
                                    <i class="fe fe-clipboard text-warning me-2"></i>الاختبارات المستخدمة
                                    <span class="group-show-members-card__count">{{ $pool->quizzes->count() }}</span>
                                </h6>
                            </div>
                            <div class="card-body pt-3">
                                <div class="list-group list-group-flush qp-quiz-list">
                                    @foreach(($pool->quizzes ?? collect())->take(5) as $quiz)
                                        <a href="{{ route('quizzes.show', $quiz->id) }}" class="list-group-item list-group-item-action px-0 border-0">
                                            <div class="d-flex justify-content-between align-items-center gap-2">
                                                <span class="text-truncate">{{ $quiz->title }}</span>
                                                <span class="badge bg-secondary-transparent flex-shrink-0">{{ $quiz->max_score }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                @if(($pool->quizzes?->count() ?? 0) > 5)
                                    <p class="text-center text-muted fs-12 mb-0 mt-2">
                                        و {{ ($pool->quizzes?->count() ?? 0) - 5 }} اختبار آخر
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- الإجراءات --}}
                    <div class="card custom-card group-show-members-card dashboard-fade-in">
                        <div class="card-header border-0 pb-0">
                            <h6 class="group-show-members-card__title mb-0">
                                <i class="fe fe-settings text-danger me-2"></i>الإجراءات
                            </h6>
                        </div>
                        <div class="card-body pt-3">
                            <div class="d-grid gap-2">
                                <a href="{{ route('question-pools.edit', $pool->id) }}" class="btn btn-warning-light">
                                    <i class="fe fe-edit-2 me-2"></i>تعديل المجموعة
                                </a>
                                <button type="button" class="btn btn-info-light" id="export-pool-btn">
                                    <i class="fe fe-download me-2"></i>تصدير الأسئلة
                                </button>
                                <form action="{{ route('question-pools.destroy', $pool->id) }}" method="POST"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه المجموعة؟ سيتم الاحتفاظ بالأسئلة في بنك الأسئلة.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger-light w-100">
                                        <i class="fe fe-trash-2 me-2"></i>حذف المجموعة
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (el) {
        new bootstrap.Tooltip(el);
    });

    var filterEl = document.getElementById('filter-difficulty');
    if (filterEl) {
        filterEl.addEventListener('change', function () {
            var difficulty = this.value;
            document.querySelectorAll('.question-row').forEach(function (row) {
                if (!difficulty || row.dataset.difficulty === difficulty) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    var exportBtn = document.getElementById('export-pool-btn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function () {
            alert('ميزة التصدير قيد التطوير');
        });
    }
});
</script>
@stop
