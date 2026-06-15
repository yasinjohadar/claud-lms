@extends('student.layouts.master')

@section('page-title')
    لوحة التلعيب
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب'],
            ],
            'title' => 'لوحة التلعيب',
            'subtitle' => 'تابع نقاطك ومستواك وتحدياتك وترتيبك',
            'actions' => '
                <div class="d-flex flex-wrap gap-2">
                    <a href="' . route('gamification.profile') . '" class="btn btn-light border btn-wave">
                        <i class="ri-user-line me-1"></i>ملفي
                    </a>
                    <a href="' . route('gamification.statistics') . '" class="btn btn-primary btn-wave">
                        <i class="ri-bar-chart-2-line me-1"></i>إحصائياتي
                    </a>
                </div>
            ',
        ])

        @include('student.pages.gamification.partials.dashboard-kpi', compact(
            'dashboard', 'levelInfo', 'streakInfo', 'userStats'
        ))

        <div class="card custom-card mb-4">
            <div class="card-header border-0 pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h5 class="card-title mb-1">
                        <i class="ri-line-chart-line text-success me-1"></i>
                        تقدم المستوى
                    </h5>
                    <p class="text-muted fs-12 mb-0">
                        من المستوى {{ $levelInfo['current_level'] ?? 1 }} إلى {{ ($levelInfo['current_level'] ?? 1) + 1 }}
                    </p>
                </div>
                <a href="{{ route('gamification.levels.index') }}" class="btn btn-sm btn-success-light btn-wave">
                    <i class="ri-stack-line me-1"></i>كل المستويات
                </a>
            </div>
            <div class="card-body pt-3">
                @php $progress = min(100, max(0, (float) ($levelInfo['level_progress'] ?? 0))); @endphp
                <div class="d-flex justify-content-between align-items-center mb-2 fs-13">
                    <span class="fw-semibold">المستوى {{ $levelInfo['current_level'] ?? 1 }}</span>
                    <span class="text-muted">{{ number_format($progress, 0) }}%</span>
                    <span class="fw-semibold">المستوى {{ ($levelInfo['current_level'] ?? 1) + 1 }}</span>
                </div>
                <div class="progress rounded-pill" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                </div>
                <p class="text-muted fs-12 text-center mt-3 mb-0">
                    @if($levelInfo['is_max_level'] ?? false)
                        <i class="ri-award-line me-1"></i>وصلت إلى أعلى مستوى!
                    @else
                        تحتاج <strong class="text-primary">{{ number_format($levelInfo['xp_needed'] ?? 0) }} XP</strong> للمستوى التالي
                    @endif
                </p>
            </div>
        </div>

        @include('student.pages.gamification.partials.dashboard-quick-links')

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card custom-card h-100">
                    <div class="card-header border-0 pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h5 class="card-title mb-1">
                                <i class="ri-medal-line text-warning me-1"></i>
                                آخر الشارات
                            </h5>
                            <p class="text-muted fs-12 mb-0">أحدث إنجازاتك المكتسبة</p>
                        </div>
                        @if($latestBadges->count() > 0)
                            <a href="{{ route('gamification.badges.index') }}" class="btn btn-sm btn-light border btn-wave">
                                عرض الكل
                            </a>
                        @endif
                    </div>
                    <div class="card-body pt-3">
                        @forelse($latestBadges as $userBadge)
                            <div class="d-flex align-items-center gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <span class="avatar avatar-md bg-warning-transparent rounded-circle flex-shrink-0 fs-20">
                                    {{ $userBadge->badge->icon ?? '🏅' }}
                                </span>
                                <div class="min-w-0 flex-fill">
                                    <p class="fw-semibold mb-0 fs-13">{{ $userBadge->badge->name ?? 'شارة' }}</p>
                                    <small class="text-muted">
                                        <i class="ri-time-line me-1"></i>{{ $userBadge->awarded_at?->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <div class="empty-state-icon mx-auto mb-3"><i class="ri-medal-line"></i></div>
                                <p class="text-muted mb-2">لم تحصل على شارات بعد</p>
                                <p class="text-muted fs-12 mb-3">أكمل الأنشطة والتحديات لكسب الشارات</p>
                                <a href="{{ route('gamification.badges.index') }}" class="btn btn-sm btn-warning-light btn-wave">
                                    استكشف الشارات
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card custom-card h-100">
                    <div class="card-header border-0 pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h5 class="card-title mb-1">
                                <i class="ri-focus-3-line text-primary me-1"></i>
                                التحديات النشطة
                            </h5>
                            <p class="text-muted fs-12 mb-0">تابع تقدمك في التحديات الحالية</p>
                        </div>
                        @if($activeChallenges->count() > 0)
                            <a href="{{ route('gamification.challenges.index') }}" class="btn btn-sm btn-light border btn-wave">
                                عرض الكل
                            </a>
                        @endif
                    </div>
                    <div class="card-body pt-3">
                        @forelse($activeChallenges as $userChallenge)
                            @php
                                $challenge = $userChallenge->challenge;
                                $progressPct = min(100, max(0, (float) ($userChallenge->progress_percentage ?? 0)));
                            @endphp
                            <div class="mb-3 {{ !$loop->last ? 'pb-3 border-bottom' : '' }}">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                        <span class="fs-18 flex-shrink-0">{{ $challenge->icon ?? '🎯' }}</span>
                                        <span class="fw-semibold fs-13 text-truncate">{{ $challenge->name ?? 'تحدي' }}</span>
                                    </div>
                                    @if($challenge?->points_reward)
                                        <span class="badge bg-primary-transparent text-primary flex-shrink-0">
                                            +{{ number_format($challenge->points_reward) }} نقطة
                                        </span>
                                    @endif
                                </div>
                                <div class="progress rounded-pill" style="height: 6px;">
                                    <div class="progress-bar bg-primary" style="width: {{ $progressPct }}%"></div>
                                </div>
                                <small class="text-muted">{{ number_format($progressPct, 0) }}% مكتمل</small>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <div class="empty-state-icon mx-auto mb-3"><i class="ri-focus-3-line"></i></div>
                                <p class="text-muted mb-2">لا توجد تحديات نشطة</p>
                                <p class="text-muted fs-12 mb-3">انضم لتحدي جديد وابدأ جمع النقاط</p>
                                <a href="{{ route('gamification.challenges.index') }}" class="btn btn-sm btn-primary-light btn-wave">
                                    تصفح التحديات
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        @if(($inProgressAchievements ?? collect())->isNotEmpty())
            <div class="card custom-card mb-4">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title mb-1">
                        <i class="ri-flag-line text-purple me-1"></i>
                        إنجازات قيد التقدم
                    </h5>
                    <p class="text-muted fs-12 mb-0">أهدافك التي تعمل عليها حالياً</p>
                </div>
                <div class="card-body pt-3">
                    <div class="row g-3">
                        @foreach($inProgressAchievements as $index => $ua)
                            @include('student.pages.gamification.partials.achievement-card', [
                                'achievement' => $ua->achievement,
                                'userAchievement' => $ua,
                                'index' => $index,
                            ])
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4 mb-4">
            @if(!empty($recentActivity['transactions']) && count($recentActivity['transactions']))
                <div class="col-lg-8">
                    <div class="card custom-card h-100">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-1">
                                <i class="ri-history-line text-info me-1"></i>
                                آخر النشاط
                            </h5>
                            <p class="text-muted fs-12 mb-0">أحدث معاملات النقاط والمكافآت</p>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <tbody>
                                        @foreach($recentActivity['transactions']->take(8) as $tx)
                                            <tr>
                                                <td class="fs-13">{{ $tx->description ?? $tx->source }}</td>
                                                <td class="text-end">
                                                    <span class="badge {{ $tx->points >= 0 ? 'bg-success-transparent text-success' : 'bg-danger-transparent text-danger' }}">
                                                        {{ $tx->points >= 0 ? '+' : '' }}{{ number_format($tx->points) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="{{ !empty($recentActivity['transactions']) && count($recentActivity['transactions']) ? 'col-lg-4' : 'col-12' }}">
                <div class="card custom-card h-100">
                    <div class="card-body text-center py-4">
                        <div class="avatar avatar-lg bg-primary-transparent mx-auto mb-3">
                            <i class="ri-trophy-line text-primary fs-24"></i>
                        </div>
                        <h5 class="card-title mb-1">ترتيبك في لوحة المتصدرين</h5>
                        <p class="text-muted fs-12 mb-3">مقارنة أدائك مع بقية الطلاب</p>
                        <h2 class="fw-bold text-primary mb-2">
                            @if($leaderboardRank)
                                #{{ number_format($leaderboardRank) }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </h2>
                        <p class="text-muted fs-13 mb-3">من بين {{ number_format($leaderboardTotal) }} طالب</p>
                        <a href="{{ route('gamification.leaderboards.index') }}" class="btn btn-primary btn-wave">
                            <i class="ri-bar-chart-grouped-line me-1"></i>عرض لوحة المتصدرين
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop
