@extends('student.layouts.master')

@section('page-title')
    إنجازاتي
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب', 'url' => route('gamification.dashboard')],
                ['label' => 'إنجازاتي'],
            ],
            'title' => 'إنجازاتي',
            'subtitle' => 'تابع تقدّمك، افتح الإنجازات، واجمع نقاط المكافآت مع كل خطوة في رحلة التعلم',
            'actions' => '
                <div class="d-flex flex-wrap gap-2">
                    <a href="' . route('gamification.achievements.recommended') . '" class="btn btn-light border btn-wave">
                        <i class="ri-flashlight-line me-1"></i>الأقرب للإكمال
                    </a>
                    <a href="' . route('gamification.dashboard') . '" class="btn btn-primary btn-wave">
                        <i class="ri-trophy-line me-1"></i>لوحة التلعيب
                    </a>
                </div>
            ',
        ])

        @include('student.pages.gamification.partials.achievements-stats', ['stats' => $stats])

        @if(($recommended ?? collect())->isNotEmpty())
            @php $spotlightIds = $recommended->pluck('id')->all(); @endphp
            <div class="card custom-card mb-4">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title mb-1">
                        <i class="ri-flashlight-line text-warning me-1"></i>
                        أقرب للإكمال
                    </h5>
                    <p class="text-muted fs-12 mb-0">إنجازات يمكنك إتمامها قريباً</p>
                </div>
                <div class="card-body pt-3">
                    <div class="row g-3">
                        @foreach($recommended as $index => $userAchievement)
                            @include('student.pages.gamification.partials.achievement-card', [
                                'userAchievement' => $userAchievement,
                                'isCompleted' => false,
                                'index' => $index,
                            ])
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            @php $spotlightIds = []; @endphp
        @endif

        @include('student.pages.gamification.partials.achievements-filters')

        @php
            $gridAchievements = isset($spotlightIds) && count($spotlightIds)
                ? $userAchievements->reject(fn ($ua) => in_array($ua->id, $spotlightIds))
                : $userAchievements;
        @endphp

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <h5 class="mb-0 fw-semibold">
                جميع الإنجازات
                <span class="text-muted fw-normal fs-13" id="achievementVisibleCount">({{ $gridAchievements->count() }})</span>
            </h5>
        </div>

        <div class="row g-3" id="achievementGrid">
            @forelse($gridAchievements as $index => $userAchievement)
                @php
                    $isCompleted = in_array($userAchievement->status, ['completed', 'claimed'], true);
                    $isLocked = !$isCompleted && ($userAchievement->progress_percentage ?? 0) <= 0;
                @endphp
                @include('student.pages.gamification.partials.achievement-card', [
                    'userAchievement' => $userAchievement,
                    'isCompleted' => $isCompleted,
                    'isLocked' => $isLocked,
                    'index' => $index,
                ])
            @empty
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-body text-center py-5">
                            <div class="empty-state-icon mx-auto mb-3"><i class="ri-flag-line"></i></div>
                            <p class="text-muted mb-2">لا توجد إنجازات</p>
                            <p class="text-muted fs-12 mb-0">ستظهر الإنجازات هنا عند تفعيلها من الإدارة</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <div id="achievementEmptyFiltered" class="d-none">
            <div class="card custom-card mt-3">
                <div class="card-body text-center py-5">
                    <div class="empty-state-icon mx-auto mb-3"><i class="ri-filter-3-line"></i></div>
                    <p class="text-muted mb-2">لا توجد إنجازات مطابقة</p>
                    <p class="text-muted fs-12 mb-3">جرّب تغيير الفلتر أو اختر «الكل» لعرض جميع الإنجازات</p>
                    <button type="button" class="btn btn-sm btn-primary-light btn-wave" id="achievementResetFilters">
                        <i class="ri-refresh-line me-1"></i>إظهار الكل
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

@include('student.pages.gamification.partials.achievement-modal')
@stop

@section('scripts')
@include('student.pages.gamification.partials.achievement-interaction-scripts')
@stop
