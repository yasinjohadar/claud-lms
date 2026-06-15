@extends('student.layouts.master')

@section('page-title')
    المسابقات
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid pb-3">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب', 'url' => route('gamification.dashboard')],
                ['label' => 'المسابقات'],
            ],
            'title' => 'المسابقات',
            'subtitle' => 'تنافس مع أصدقائك في تحديات النقاط والدروس والاختبارات',
            'actions' => '
                <div class="d-flex flex-wrap gap-2">
                    <a href="' . route('gamification.friends.index') . '" class="btn btn-light border btn-wave">
                        <i class="ri-group-line me-1"></i>الأصدقاء
                    </a>
                    <a href="' . route('gamification.dashboard') . '" class="btn btn-primary btn-wave">
                        <i class="ri-trophy-line me-1"></i>لوحة التلعيب
                    </a>
                </div>
            ',
        ])

        @include('student.pages.gamification.competitions.partials.stats', compact('stats'))

        <div class="card custom-card mb-4">
            <div class="card-header border-0 pb-0">
                <h5 class="card-title mb-1">
                    <i class="ri-flashlight-line text-warning me-1"></i>
                    مسابقات نشطة
                </h5>
                <p class="text-muted fs-12 mb-0">المنافسات الجارية التي تشارك فيها</p>
            </div>
            <div class="card-body pt-3">
                <div class="row g-3">
                    @forelse($activeCompetitions as $index => $comp)
                        @include('student.pages.gamification.competitions.partials.competition-card', [
                            'competition' => $comp,
                            'index' => $index,
                            'state' => 'active',
                            'variant' => 'active',
                        ])
                    @empty
                        <div class="col-12">
                            <div class="empty-state py-5">
                                <div class="empty-state-icon mx-auto mb-3"><i class="ri-sword-line"></i></div>
                                <p class="text-muted mb-1">لا توجد مسابقات نشطة</p>
                                <p class="text-muted fs-12 mb-0">ادعُ أصدقاءك لبدء منافسة جديدة</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card custom-card">
            <div class="card-header border-0 pb-0">
                <h5 class="card-title mb-1">
                    <i class="ri-history-line text-info me-1"></i>
                    مسابقات مكتملة
                </h5>
                <p class="text-muted fs-12 mb-0">سجل منافساتك السابقة ونتائجك</p>
            </div>
            <div class="card-body pt-3">
                <div class="row g-3">
                    @forelse($completedCompetitions as $index => $comp)
                        @include('student.pages.gamification.competitions.partials.competition-card', [
                            'competition' => $comp,
                            'index' => $index,
                            'state' => 'completed',
                            'variant' => 'completed',
                        ])
                    @empty
                        <div class="col-12">
                            <div class="empty-state py-4">
                                <div class="empty-state-icon mx-auto mb-3"><i class="ri-flag-line"></i></div>
                                <p class="text-muted mb-0">لا يوجد سجل مسابقات بعد</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@stop
