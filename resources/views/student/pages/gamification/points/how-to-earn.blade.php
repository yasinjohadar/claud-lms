@extends('student.layouts.master')

@section('page-title')
    كيف أكسب نقاط؟
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب', 'url' => route('gamification.dashboard')],
                ['label' => 'النقاط', 'url' => route('gamification.points.index')],
                ['label' => 'كيف أكسب نقاط؟'],
            ],
            'title' => 'كيف أكسب نقاط؟',
            'subtitle' => 'تعرّف على كل طريقة لكسب النقاط والخبرة في المنصة',
            'actions' => '
                <a href="' . route('gamification.points.index') . '" class="btn btn-primary btn-wave">
                    <i class="ri-arrow-right-line me-1"></i>العودة للنقاط
                </a>
            ',
        ])

        <div class="row g-4">
            <div class="col-lg-8">
                @forelse($earningByCategory ?? [] as $categoryKey => $category)
                    <div class="card custom-card mb-4">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-0">{{ $category['label'] }}</h5>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                @foreach($category['methods'] as $index => $method)
                                    @include('student.pages.gamification.points.partials.earning-method-card', [
                                        'method' => $method,
                                        'index' => $index,
                                    ])
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">لا توجد معلومات متاحة حالياً</div>
                @endforelse

                @if(count($streakMilestones ?? []) > 0)
                    <div class="card custom-card mb-4">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-0">
                                <i class="ri-fire-line text-danger me-1"></i>
                                معالم السلسلة اليومية
                            </h5>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                @foreach($streakMilestones as $milestone)
                                    <div class="col-md-6">
                                        <div class="border rounded p-3">
                                            <h6 class="mb-1 fs-13">{{ $milestone['days'] }} يوم متتالي</h6>
                                            <p class="text-muted fs-12 mb-2">{{ $milestone['description'] }}</p>
                                            <span class="badge bg-danger-transparent">+{{ number_format($milestone['points']) }} نقطة</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if(count($streakMultipliers ?? []) > 0)
                    <div class="card custom-card">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-0">
                                <i class="ri-flashlight-line text-warning me-1"></i>
                                مضاعفات السلسلة
                            </h5>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                @foreach($streakMultipliers as $days => $multiplier)
                                    @if((int) $days > 0)
                                        <div class="col-md-4 col-sm-6">
                                            <div class="border rounded p-3 text-center">
                                                <div class="fw-semibold">{{ $days }} يوم</div>
                                                <span class="badge bg-primary mt-2">×{{ $multiplier }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card custom-card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">نصائح سريعة</h5>
                        <ul class="list-unstyled mb-0 fs-13">
                            <li class="mb-2"><i class="ri-check-line text-success me-1"></i> أكمل الدروس والاختبارات يومياً</li>
                            <li class="mb-2"><i class="ri-check-line text-success me-1"></i> حافظ على سلسلة الدخول للمضاعفات</li>
                            <li class="mb-2"><i class="ri-check-line text-success me-1"></i> شارك الكورسات وادعُ أصدقاءك</li>
                            <li><i class="ri-check-line text-success me-1"></i> استخدم النقاط في المتجر</li>
                        </ul>
                    </div>
                </div>

                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title mb-1">
                            <i class="ri-user-add-line text-success me-1"></i>
                            رابط الدعوة
                        </h5>
                        <p class="text-muted fs-12 mb-3">شارك الرابط واحصل على نقاط عند تسجيل صديقك</p>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control form-control-sm" id="referral-link-howto" value="{{ $referralLink }}" readonly>
                            <button type="button" class="btn btn-outline-primary btn-sm btn-wave" id="copy-referral-howto">نسخ</button>
                        </div>
                        <a href="{{ route('gamification.points.index') }}" class="btn btn-primary btn-wave w-100 mb-2">عرض نقاطي</a>
                        <a href="{{ route('gamification.shop.index') }}" class="btn btn-light border btn-wave w-100">زيارة المتجر</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop

@section('scripts')
<script>
document.getElementById('copy-referral-howto')?.addEventListener('click', function () {
    var input = document.getElementById('referral-link-howto');
    if (!input) return;
    navigator.clipboard.writeText(input.value).then(function () {
        var btn = document.getElementById('copy-referral-howto');
        btn.textContent = 'تم النسخ';
        setTimeout(function () { btn.textContent = 'نسخ'; }, 2000);
    });
});
</script>
@stop
