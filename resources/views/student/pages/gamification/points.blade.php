@extends('student.layouts.master')

@section('page-title')
    النقاط
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب', 'url' => route('gamification.dashboard')],
                ['label' => 'النقاط'],
            ],
            'title' => 'النقاط',
            'subtitle' => 'تابع رصيدك وطرق كسب النقاط واستخدمها في المتجر',
            'actions' => '
                <div class="d-flex flex-wrap gap-2">
                    <a href="' . route('gamification.points.history') . '" class="btn btn-light border btn-wave">
                        <i class="ri-history-line me-1"></i>سجل النقاط
                    </a>
                    <a href="' . route('gamification.points.how-to-earn') . '" class="btn btn-primary btn-wave">
                        <i class="ri-lightbulb-line me-1"></i>كيف أكسب نقاط؟
                    </a>
                </div>
            ',
        ])

        @include('student.pages.gamification.points.partials.stats', compact(
            'totalPoints', 'availablePoints', 'spentPoints', 'monthlyEarned'
        ))

        <div class="gamification-points-balance-banner mb-4">
            <div class="gamification-points-balance-banner__content">
                <span class="gamification-points-balance-banner__label">رصيدك المتاح للشراء</span>
                <strong class="gamification-points-balance-banner__value">{{ number_format($availablePoints ?? 0) }}</strong>
                <span class="gamification-points-balance-banner__hint">نقطة · إجمالي مكتسب {{ number_format($totalPoints ?? 0) }}</span>
            </div>
            <div class="gamification-points-balance-banner__actions">
                <a href="{{ route('gamification.shop.index') }}" class="btn btn-light btn-wave">
                    <i class="ri-store-2-line me-1"></i>المتجر
                </a>
                <a href="{{ route('gamification.points.how-to-earn') }}" class="btn btn-primary btn-wave">
                    <i class="ri-add-line me-1"></i>اكسب المزيد
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card custom-card mb-4">
                    <div class="card-header border-0 pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h5 class="card-title mb-1">
                                <i class="ri-coin-line text-primary me-1"></i>
                                طرق كسب النقاط
                            </h5>
                            <p class="text-muted fs-12 mb-0">أهم الأنشطة التي تمنحك نقاطاً — اضغط للانتقال</p>
                        </div>
                        <a href="{{ route('gamification.points.how-to-earn') }}" class="btn btn-sm btn-light border btn-wave">عرض الكل</a>
                    </div>
                    <div class="card-body pt-3">
                        @include('student.pages.gamification.points.partials.earning-methods', ['earningMethods' => $earningMethods])
                    </div>
                </div>

                <div class="card custom-card">
                    <div class="card-header border-0 pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h5 class="card-title mb-1">
                                <i class="ri-history-line text-info me-1"></i>
                                آخر المعاملات
                            </h5>
                            <p class="text-muted fs-12 mb-0">أحدث حركات النقاط في حسابك</p>
                        </div>
                        @if(($recentTransactions ?? collect())->isNotEmpty())
                            <a href="{{ route('gamification.points.history') }}" class="btn btn-sm btn-light border btn-wave">السجل الكامل</a>
                        @endif
                    </div>
                    <div class="card-body pt-3">
                        @if(($recentTransactions ?? collect())->isNotEmpty())
                            <div class="gamification-points-tx-list">
                                @foreach($recentTransactions as $tx)
                                    @include('student.pages.gamification.points.partials.transaction-item', ['transaction' => $tx])
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state py-4">
                                <div class="empty-state-icon mx-auto mb-3"><i class="ri-coin-line"></i></div>
                                <p class="text-muted mb-2">لا توجد معاملات بعد</p>
                                <p class="text-muted fs-12 mb-3">ابدأ التعلم لكسب نقاطك الأولى</p>
                                <a href="{{ route('gamification.points.how-to-earn') }}" class="btn btn-sm btn-primary btn-wave">اكتشف طرق الكسب</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="shortcut-section mb-4">
                    <div class="shortcut-section__header mb-3">
                        <h5 class="shortcut-section__title mb-1">
                            <i class="ri-flashlight-line text-warning"></i>
                            إجراءات سريعة
                        </h5>
                        <p class="shortcut-section__subtitle mb-0">استخدم نقاطك أو اكسب المزيد</p>
                    </div>
                    <div class="row g-3 shortcut-grid">
                        @include('admin.partials.ui.shortcut-card', [
                            'url' => route('gamification.shop.index'),
                            'title' => 'زيارة المتجر',
                            'description' => 'معززات ومظهر ومكافآت',
                            'icon' => 'ri-store-2-line',
                            'icon_color' => 'warning',
                            'col' => 'col-12',
                        ])
                        @include('admin.partials.ui.shortcut-card', [
                            'url' => route('student.courses.index'),
                            'title' => 'تابع التعلم',
                            'description' => 'أكمل دروساً وكورسات',
                            'icon' => 'ri-book-open-line',
                            'icon_color' => 'primary',
                            'col' => 'col-12',
                        ])
                        @include('admin.partials.ui.shortcut-card', [
                            'url' => route('gamification.streak.index'),
                            'title' => 'السلسلة اليومية',
                            'description' => 'مضاعفات يومية إضافية',
                            'icon' => 'ri-fire-line',
                            'icon_color' => 'danger',
                            'col' => 'col-12',
                        ])
                        @include('admin.partials.ui.shortcut-card', [
                            'url' => route('gamification.challenges.index'),
                            'title' => 'التحديات',
                            'description' => 'مهام بمكافآت سريعة',
                            'icon' => 'ri-focus-3-line',
                            'icon_color' => 'success',
                            'col' => 'col-12',
                        ])
                    </div>
                </div>

                <div class="card custom-card gamification-points-referral-card">
                    <div class="card-body">
                        <h5 class="card-title mb-1">
                            <i class="ri-user-add-line text-success me-1"></i>
                            ادعُ صديقاً
                        </h5>
                        <p class="text-muted fs-13 mb-3">شارك رابط الدعوة واحصل على نقاط عند تسجيل صديقك.</p>
                        <div class="gamification-points-referral-card__input-wrap">
                            <input type="text" class="form-control form-control-sm" id="referral-link-input" value="{{ $referralLink }}" readonly>
                            <button type="button" class="btn btn-primary btn-sm btn-wave" id="copy-referral-btn">
                                <i class="ri-file-copy-line"></i>
                            </button>
                        </div>
                        <p class="text-muted fs-11 mb-0 mt-2">انسخ الرابط وشاركه مع أصدقائك</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop

@section('scripts')
<script>
document.getElementById('copy-referral-btn')?.addEventListener('click', function () {
    var input = document.getElementById('referral-link-input');
    if (!input) return;
    navigator.clipboard.writeText(input.value).then(function () {
        var btn = document.getElementById('copy-referral-btn');
        var original = btn.innerHTML;
        btn.innerHTML = '<i class="ri-check-line"></i>';
        setTimeout(function () { btn.innerHTML = original; }, 2000);
    });
});
</script>
@stop
