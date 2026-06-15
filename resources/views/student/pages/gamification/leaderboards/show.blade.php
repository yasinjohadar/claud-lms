@extends('student.layouts.master')

@section('page-title')
    {{ $leaderboard->name }}
@stop

@section('content')
<div class="main-content app-content student-leaderboards-page">
    <div class="container-fluid">
        @php
            $catalog = app(\App\Services\Gamification\LeaderboardCatalog::class);
            $topByRank = $topThree->keyBy('rank');
            $typeLabel = $catalog->getTypeLabel($leaderboard->type);
            $periodLabel = $catalog->getPeriodLabel($leaderboard->period);
        @endphp

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب', 'url' => route('gamification.dashboard')],
                ['label' => 'لوحات المتصدرين', 'url' => route('gamification.leaderboards.index')],
                ['label' => $leaderboard->name],
            ],
            'title' => ($leaderboard->icon ?? '🏆') . ' ' . $leaderboard->name,
            'subtitle' => $leaderboard->description
                ?: 'ترتيب الطلاب حسب ' . $catalog->getMetricLabel($catalog->resolveMetric($leaderboard)) . ' — ' . $typeLabel . ' · ' . $periodLabel,
            'actions' => '
                <div class="d-flex flex-wrap gap-2">
                    <a href="' . route('gamification.leaderboards.index') . '" class="btn btn-light border btn-wave">
                        <i class="ri-arrow-right-line me-1"></i>كل اللوحات
                    </a>
                    <a href="' . route('gamification.leaderboards.my-rank') . '" class="btn btn-primary btn-wave">
                        <i class="ri-user-star-line me-1"></i>ترتيبي
                    </a>
                </div>
            ',
        ])

        @include('student.pages.gamification.leaderboards.partials.show-stats', [
            'stats' => $stats,
            'userRank' => $userRank,
            'leaderboard' => $leaderboard,
            'catalog' => $catalog,
        ])

        @if ($topThree->isNotEmpty())
            <div class="card custom-card mb-4">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title mb-1">
                        <i class="ri-medal-line text-warning me-1"></i>
                        منصة الشرف
                    </h5>
                    <p class="text-muted fs-12 mb-0">أفضل 3 متصدرين — اضغط لعرض التفاصيل</p>
                </div>
                <div class="card-body pt-3">
                    <div class="student-leaderboard-podium-wrap">
                        <div class="student-leaderboard-podium">
                            @foreach ([2, 1, 3] as $rank)
                                @php $entry = $topByRank->get($rank); @endphp
                                <div class="student-leaderboard-podium__slot student-leaderboard-podium__slot--rank-{{ $rank }}">
                                    @if ($entry)
                                        @include('student.pages.gamification.leaderboards.partials.podium-entry', [
                                            'entry' => $entry,
                                            'catalog' => $catalog,
                                            'leaderboard' => $leaderboard,
                                            'currentUser' => $user,
                                        ])
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card custom-card mb-4">
            <div class="card-header border-0 pb-0">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                    <div>
                        <h5 class="card-title mb-1">
                            <i class="ri-trophy-line text-primary me-1"></i>
                            قائمة المتصدرين
                        </h5>
                        <p class="text-muted fs-12 mb-0">فلترة حسب الفئة أو اعرض الجميع</p>
                    </div>
                    <div class="student-leaderboard-division-tabs">
                        <button type="button" class="student-leaderboard-division-tab is-active" data-division-filter="all">
                            <span class="student-leaderboard-division-tab__icon student-leaderboard-division-tab__icon--all" aria-hidden="true"><i class="ri ri-apps-line"></i></span>
                            <span>الكل</span>
                        </button>
                        @foreach (['bronze', 'silver', 'gold', 'platinum', 'diamond'] as $div)
                            @include('student.pages.gamification.leaderboards.partials.division-tab', [
                                'division' => $div,
                                'catalog' => $catalog,
                            ])
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card-body pt-3">
                <div class="student-leaderboard-list">
                    @forelse ($entries as $entry)
                        @include('student.pages.gamification.leaderboards.partials.list-entry', [
                            'entry' => $entry,
                            'catalog' => $catalog,
                            'leaderboard' => $leaderboard,
                            'currentUser' => $user,
                        ])
                    @empty
                        <div class="empty-state py-5">
                            <div class="empty-state-icon mx-auto mb-3"><i class="ri-trophy-line"></i></div>
                            <p class="text-muted mb-1">لا يوجد مشاركون بعد</p>
                            <p class="text-muted fs-12 mb-0">كن أول من يظهر في هذه اللوحة بكسب النقاط والمشاركة</p>
                        </div>
                    @endforelse
                    <div class="empty-state py-4 js-leaderboard-filter-empty" hidden>
                        <div class="empty-state-icon mx-auto mb-3"><i class="ri-filter-off-line"></i></div>
                        <p class="text-muted mb-0">لا يوجد طلاب في هذه الفئة</p>
                    </div>
                </div>
            </div>
        </div>

        @if ($surroundingUsers->isNotEmpty())
            <div class="card custom-card">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title mb-1">
                        <i class="ri-group-line text-info me-1"></i>
                        محيطك في الترتيب
                    </h5>
                    <p class="text-muted fs-12 mb-0">الطلاب القريبون من ترتيبك</p>
                </div>
                <div class="card-body pt-3">
                    <div class="student-leaderboard-list">
                        @foreach ($surroundingUsers as $entry)
                            @include('student.pages.gamification.leaderboards.partials.list-entry', [
                                'entry' => $entry,
                                'catalog' => $catalog,
                                'leaderboard' => $leaderboard,
                                'currentUser' => $user,
                                'compact' => true,
                            ])
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@include('student.pages.gamification.leaderboards.partials.player-modal')
@include('student.pages.gamification.leaderboards.partials.interaction-scripts')
@stop
