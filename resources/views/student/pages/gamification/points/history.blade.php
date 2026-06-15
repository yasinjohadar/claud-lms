@extends('student.layouts.master')

@section('page-title')
    سجل النقاط
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
                ['label' => 'السجل'],
            ],
            'title' => 'سجل النقاط',
            'subtitle' => 'تتبّع كل نقطة كسبتها أو أنفقتها مع مصدرها وتفاصيلها',
            'actions' => '
                <div class="d-flex flex-wrap gap-2">
                    <a href="' . route('gamification.points.how-to-earn') . '" class="btn btn-light border btn-wave">
                        <i class="ri-lightbulb-line me-1"></i>كيف أكسب نقاط؟
                    </a>
                    <a href="' . route('gamification.points.index') . '" class="btn btn-primary btn-wave">
                        <i class="ri-arrow-right-line me-1"></i>العودة للنقاط
                    </a>
                </div>
            ',
        ])

        @include('student.pages.gamification.points.partials.history-stats', ['stats' => $stats ?? []])

        <div class="filter-panel mb-4">
            <div class="filter-panel__title">تصفية السجل</div>
            <div class="filter-panel__subtitle">ابحث حسب النوع أو المصدر أو الفترة الزمنية</div>
            <form method="GET" action="{{ route('gamification.points.history') }}">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-md-3 col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">النوع</label>
                        <select name="type" class="form-select">
                            <option value="">الكل</option>
                            <option value="earn" {{ request('type') === 'earn' ? 'selected' : '' }}>مكتسب</option>
                            <option value="spend" {{ request('type') === 'spend' ? 'selected' : '' }}>مصروف</option>
                            <option value="bonus" {{ request('type') === 'bonus' ? 'selected' : '' }}>مكافأة</option>
                            <option value="penalty" {{ request('type') === 'penalty' ? 'selected' : '' }}>خصم</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">المصدر</label>
                        <select name="source" class="form-select">
                            <option value="">كل المصادر</option>
                            @foreach ($sources ?? [] as $value => $label)
                                <option value="{{ $value }}" {{ request('source') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">من تاريخ</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2 col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">إلى تاريخ</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2 col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill btn-wave">
                            <i class="ri-filter-3-line me-1"></i>تصفية
                        </button>
                        <a href="{{ route('gamification.points.history') }}" class="btn btn-light border btn-wave" title="مسح الفلاتر">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card custom-card">
            <div class="card-header border-0 pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h5 class="card-title mb-1">
                        <i class="ri-history-line text-info me-1"></i>
                        سجل المعاملات
                    </h5>
                    <p class="text-muted fs-12 mb-0">
                        {{ number_format($transactions->total()) }} معاملة
                        @if (request()->hasAny(['type', 'source', 'date_from', 'date_to']))
                            — نتائج مفلترة
                        @endif
                    </p>
                </div>
            </div>
            <div class="card-body pt-3">
                @php $catalog = app(\App\Services\Gamification\PointEarningCatalog::class); @endphp

                @forelse ($transactions as $transaction)
                    @php
                        $sourceLabel = $catalog->getSourceLabel($transaction->source);
                        $isPositive = $transaction->points > 0;
                    @endphp
                    <div class="d-flex align-items-start gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <span class="avatar avatar-sm {{ $isPositive ? 'bg-success-transparent' : 'bg-danger-transparent' }} flex-shrink-0">
                            <i class="ri {{ $catalog->getSourceIcon($transaction->source) }} {{ $isPositive ? 'text-success' : 'text-danger' }}"></i>
                        </span>
                        <div class="flex-fill min-w-0">
                            <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
                                <div class="min-w-0">
                                    <h6 class="fw-semibold mb-1 fs-13">{{ $transaction->description ?: $sourceLabel }}</h6>
                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="badge bg-primary-transparent">{{ $sourceLabel }}</span>
                                        @if($isPositive)
                                            <span class="badge bg-success-transparent">مكتسب</span>
                                        @else
                                            <span class="badge bg-danger-transparent">مصروف</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="fw-bold {{ $isPositive ? 'text-success' : 'text-danger' }} flex-shrink-0">
                                    {{ $isPositive ? '+' : '' }}{{ number_format($transaction->points) }}
                                </span>
                            </div>
                            <div class="d-flex flex-wrap gap-3 mt-2 text-muted fs-12">
                                <span><i class="ri-calendar-line me-1"></i>{{ $transaction->created_at->format('Y/m/d') }}</span>
                                <span><i class="ri-time-line me-1"></i>{{ $transaction->created_at->format('H:i') }}</span>
                                @if ($transaction->balance_after !== null)
                                    <span><i class="ri-wallet-3-line me-1"></i>الرصيد: {{ number_format($transaction->balance_after) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="empty-state-icon mx-auto mb-3"><i class="ri-inbox-line"></i></div>
                        <p class="text-muted mb-2">لا توجد معاملات</p>
                        <p class="text-muted fs-12 mb-3">
                            @if (request()->hasAny(['type', 'source', 'date_from', 'date_to']))
                                لا توجد نتائج مطابقة للتصفية الحالية.
                            @else
                                ابدأ التعلم وأكمل الدروس والاختبارات لكسب نقاطك الأولى!
                            @endif
                        </p>
                        <a href="{{ route('gamification.points.how-to-earn') }}" class="btn btn-sm btn-primary-light btn-wave">
                            اكتشف طرق الكسب
                        </a>
                    </div>
                @endforelse

                @if ($transactions->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@stop
