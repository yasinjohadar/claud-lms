@extends('student.layouts.master')

@section('page-title')
    السلسلة اليومية
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب', 'url' => route('gamification.dashboard')],
                ['label' => 'السلسلة اليومية'],
            ],
            'title' => 'السلسلة اليومية',
            'subtitle' => 'حافظ على نشاطك اليومي لكسب مكافآت إضافية ومضاعف نقاط أعلى',
            'actions' => '
                <div class="d-flex flex-wrap gap-2">
                    <a href="' . route('gamification.streak.calendar') . '" class="btn btn-light border btn-wave">
                        <i class="ri-calendar-2-line me-1"></i>التقويم
                    </a>
                    <a href="' . route('gamification.streak.history') . '" class="btn btn-primary btn-wave">
                        <i class="ri-history-line me-1"></i>السجل
                    </a>
                </div>
            ',
        ])

        @include('student.pages.gamification.streak.partials.stats', compact('streakInfo', 'monthlyStats'))

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card custom-card mb-4">
                    <div class="card-header border-0 pb-0">
                        <h5 class="card-title mb-1">
                            <i class="ri-fire-line text-danger me-1"></i>
                            معلومات السلسلة
                        </h5>
                        <p class="text-muted fs-12 mb-0">تقدّمك اليومي ومسارك نحو الهدف القادم</p>
                    </div>
                    <div class="card-body pt-3">
                        @if(($streakInfo['current_streak'] ?? 0) > 0)
                            <div class="alert alert-success border-0 mb-3">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="ri-fire-fill fs-18 mt-1"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">سلسلة نشطة!</h6>
                                        <p class="mb-0 fs-13">
                                            أنت في سلسلة من <strong>{{ $streakInfo['current_streak'] }}</strong> يوم متتالي.
                                            استمر في التعلم اليوم للحفاظ عليها.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning border-0 mb-3">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="ri-error-warning-line fs-18 mt-1"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">لا توجد سلسلة نشطة</h6>
                                        <p class="mb-0 fs-13">أكمل درساً أو اختباراً اليوم لبدء سلسلة جديدة.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @include('student.pages.gamification.streak.partials.week-activity', compact('streakInfo'))

                        @if(!empty($streakInfo['last_activity_date']))
                            <div class="gamification-streak-info-tile mb-3">
                                <div>
                                    <span class="gamification-streak-info-tile__label">آخر نشاط</span>
                                    <strong>{{ \Carbon\Carbon::parse($streakInfo['last_activity_date'])->diffForHumans() }}</strong>
                                </div>
                                <span class="gamification-streak-info-tile__icon"><i class="ri-time-line"></i></span>
                            </div>
                        @endif

                        @if(!empty($streakInfo['next_milestone']))
                            <div class="gamification-streak-next-goal">
                                <div class="gamification-streak-next-goal__content">
                                    <span class="gamification-streak-next-goal__label">الهدف القادم</span>
                                    <strong>{{ $streakInfo['next_milestone']['days'] }} يوم متتالي</strong>
                                    @if(!empty($streakInfo['next_milestone']['description']))
                                        <small>{{ $streakInfo['next_milestone']['description'] }}</small>
                                    @endif
                                </div>
                                <div class="gamification-streak-next-goal__reward">
                                    <span>مكافأة</span>
                                    <strong>+{{ number_format($streakInfo['next_milestone']['points'] ?? 0) }}</strong>
                                    <small>نقطة</small>
                                </div>
                            </div>
                        @else
                            <div class="gamification-streak-next-goal gamification-streak-next-goal--complete">
                                <div class="gamification-streak-next-goal__content">
                                    <span class="gamification-streak-next-goal__label">جميع المعالم</span>
                                    <strong>أكملت كل أهداف السلسلة!</strong>
                                    <small>استمر في النشاط اليومي للحفاظ على مضاعفك</small>
                                </div>
                                <div class="gamification-streak-next-goal__reward">
                                    <i class="ri-trophy-fill"></i>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if(isset($monthlyStats))
                    <div class="card custom-card">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-1">
                                <i class="ri-bar-chart-box-line text-success me-1"></i>
                                إحصائيات الشهر الحالي
                            </h5>
                            <p class="text-muted fs-12 mb-0">ملخص نشاطك خلال هذا الشهر</p>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                @include('admin.partials.ui.stat-card-gradient', [
                                    'col' => 'col-md-4',
                                    'variant' => 'green',
                                    'icon' => 'ri-calendar-check-line',
                                    'label' => 'أيام نشطة',
                                    'value' => number_format($monthlyStats['active_days'] ?? 0),
                                    'hint' => 'أيام سجّلت فيها نشاطاً',
                                ])
                                @include('admin.partials.ui.stat-card-gradient', [
                                    'col' => 'col-md-4',
                                    'variant' => 'purple',
                                    'icon' => 'ri-coin-line',
                                    'label' => 'نقاط مكتسبة',
                                    'value' => number_format($monthlyStats['total_points'] ?? 0),
                                    'hint' => 'من النشاط اليومي',
                                ])
                                @include('admin.partials.ui.stat-card-gradient', [
                                    'col' => 'col-md-4',
                                    'variant' => 'cyan',
                                    'icon' => 'ri-flashlight-line',
                                    'label' => 'XP مكتسب',
                                    'value' => number_format($monthlyStats['total_xp'] ?? 0),
                                    'hint' => 'خبرة إضافية',
                                ])
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card custom-card mb-4">
                    <div class="card-header border-0 pb-0">
                        <h5 class="card-title mb-1">
                            <i class="ri-gift-line text-warning me-1"></i>
                            مكافآت السلسلة
                        </h5>
                        <p class="text-muted fs-12 mb-0">معالم يومية ونقاط مكافأة</p>
                    </div>
                    <div class="card-body pt-3">
                        @include('student.pages.gamification.streak.partials.rewards-roadmap', compact('streakInfo', 'streakRewards'))
                    </div>
                </div>

                <div class="shortcut-section">
                    <div class="shortcut-section__header mb-3">
                        <h5 class="shortcut-section__title mb-1">
                            <i class="ri-flashlight-line text-warning"></i>
                            إجراءات سريعة
                        </h5>
                        <p class="shortcut-section__subtitle mb-0">واصل سلسلتك من هنا</p>
                    </div>
                    <div class="row g-3 shortcut-grid">
                        @include('admin.partials.ui.shortcut-card', [
                            'url' => route('student.courses.index'),
                            'title' => 'ابدأ التعلم',
                            'description' => 'تابع دروسك وكورساتك',
                            'icon' => 'ri-book-open-line',
                            'icon_color' => 'primary',
                            'col' => 'col-12',
                        ])
                        @include('admin.partials.ui.shortcut-card', [
                            'url' => route('gamification.streak.calendar'),
                            'title' => 'عرض التقويم',
                            'description' => 'أيام نشاطك الشهرية',
                            'icon' => 'ri-calendar-2-line',
                            'icon_color' => 'info',
                            'col' => 'col-12',
                        ])
                        @include('admin.partials.ui.shortcut-card', [
                            'url' => route('gamification.streak.history'),
                            'title' => 'سجل السلسلة',
                            'description' => 'آخر 90 يوماً من النشاط',
                            'icon' => 'ri-history-line',
                            'icon_color' => 'success',
                            'col' => 'col-12',
                        ])
                        @include('admin.partials.ui.shortcut-card', [
                            'url' => route('gamification.shop.index'),
                            'title' => 'المتجر',
                            'description' => 'اشترِ حماية السلسلة',
                            'icon' => 'ri-store-2-line',
                            'icon_color' => 'warning',
                            'col' => 'col-12',
                        ])
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop
