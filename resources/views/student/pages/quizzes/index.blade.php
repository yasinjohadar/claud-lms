@extends('student.layouts.master')

@section('page-title')
    الاختبارات المتاحة
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid pb-3">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'الاختبارات المتاحة'],
            ],
            'title' => 'الاختبارات المتاحة',
            'subtitle' => 'اختبارات الكورسات المسجّل بها — ابدأ محاولة جديدة أو راجع نتائجك',
            'actions' => '
                <div class="d-flex flex-wrap gap-2">
                    <a href="' . route('student.quizzes.review.index') . '" class="btn btn-light border btn-wave">
                        <i class="ri-history-line me-1"></i>محاولاتي السابقة
                    </a>
                    <a href="' . route('student.quizzes.review.analytics') . '" class="btn btn-primary btn-wave">
                        <i class="ri-bar-chart-line me-1"></i>تحليلات الأداء
                    </a>
                </div>
            ',
        ])

        @include('student.pages.quizzes.partials.available-stats', ['stats' => $stats ?? []])

        @include('student.pages.quizzes.partials.available-filters', ['courses' => $courses ?? collect()])

        <div class="card custom-card">
            <div class="card-header border-0 pb-0 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <h5 class="card-title mb-1">
                        <i class="ri-clipboard-line text-primary me-1"></i>
                        قائمة الاختبارات
                    </h5>
                    <p class="text-muted fs-12 mb-0">{{ number_format($stats['filtered'] ?? $quizzes->total()) }} اختبار حسب الفلاتر الحالية</p>
                </div>
            </div>
            <div class="card-body pt-3">
                <div class="row g-3">
                    @forelse($quizzes as $index => $quiz)
                        @include('student.pages.quizzes.partials.available-quiz-card', [
                            'quiz' => $quiz,
                            'index' => $index,
                        ])
                    @empty
                        @include('student.pages.quizzes.partials.available-empty')
                    @endforelse
                </div>

                @if($quizzes->hasPages())
                    <div class="d-flex justify-content-center mt-4 pt-2 border-top">
                        {{ $quizzes->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@stop
