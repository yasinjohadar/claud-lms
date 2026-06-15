@extends('student.layouts.master')

@section('page-title')
    التحديات
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب', 'url' => route('gamification.dashboard')],
                ['label' => 'التحديات'],
            ],
            'title' => 'التحديات',
            'subtitle' => 'أكمل المهام اليومية والأسبوعية لكسب مكافآت إضافية',
            'actions' => '
                <a href="' . route('gamification.dashboard') . '" class="btn btn-primary btn-wave">
                    <i class="ri-trophy-line me-1"></i>لوحة التلعيب
                </a>
            ',
        ])

        @if(!empty($stats))
            <div class="row g-3 mb-4">
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'purple',
                    'icon' => 'ri-focus-3-line',
                    'label' => 'نشطة',
                    'value' => number_format($stats['active_challenges'] ?? 0),
                    'hint' => 'تحديات جارية الآن',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'green',
                    'icon' => 'ri-checkbox-circle-line',
                    'label' => 'مكتملة',
                    'value' => number_format($stats['total_completed'] ?? 0),
                    'hint' => 'إجمالي ما أنجزته',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'orange',
                    'icon' => 'ri-calendar-check-line',
                    'label' => 'هذا الأسبوع',
                    'value' => number_format($stats['completed_this_week'] ?? 0),
                    'hint' => 'تحديات أُنجزت هذا الأسبوع',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'cyan',
                    'icon' => 'ri-pie-chart-line',
                    'label' => 'نسبة الإنجاز',
                    'value' => round($stats['completion_rate'] ?? 0, 1) . '%',
                    'hint' => 'من التحديات التي بدأتها',
                ])
            </div>
        @endif

        @if(($activeChallenges ?? collect())->isNotEmpty())
            <div class="card custom-card mb-4">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title mb-1">
                        <i class="ri-flashlight-line text-warning me-1"></i>
                        تحدياتي النشطة
                    </h5>
                    <p class="text-muted fs-12 mb-0">تابع تقدّمك في التحديات الجارية</p>
                </div>
                <div class="card-body pt-3">
                    <div class="row g-3">
                        @foreach($activeChallenges as $index => $userChallenge)
                            @php $challenge = $userChallenge->challenge; @endphp
                            @if($challenge)
                                @include('student.pages.gamification.partials.challenge-card', [
                                    'challenge' => $challenge,
                                    'userChallenge' => $userChallenge,
                                    'showActions' => true,
                                    'index' => $index,
                                ])
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @php
            $typeLabels = [
                'daily' => ['label' => 'يومية', 'icon' => 'ri-sun-line', 'color' => 'warning'],
                'weekly' => ['label' => 'أسبوعية', 'icon' => 'ri-calendar-line', 'color' => 'info'],
                'monthly' => ['label' => 'شهرية', 'icon' => 'ri-calendar-2-line', 'color' => 'primary'],
                'special' => ['label' => 'خاصة', 'icon' => 'ri-star-line', 'color' => 'danger'],
            ];
        @endphp

        @foreach($typeLabels as $typeKey => $typeMeta)
            @php $typeChallenges = ($groupedChallenges ?? collect())->get($typeKey, collect()); @endphp
            @if($typeChallenges->isNotEmpty())
                <div class="card custom-card mb-4">
                    <div class="card-header border-0 pb-0">
                        <h5 class="card-title mb-1">
                            <i class="{{ $typeMeta['icon'] }} text-{{ $typeMeta['color'] }} me-1"></i>
                            تحديات {{ $typeMeta['label'] }}
                        </h5>
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            @foreach($typeChallenges as $index => $challenge)
                                @include('student.pages.gamification.partials.challenge-card', [
                                    'challenge' => $challenge,
                                    'userChallenge' => $challenge->user_challenge ?? null,
                                    'showActions' => !($challenge->auto_assign ?? false),
                                    'index' => $index,
                                ])
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        @if(($groupedChallenges ?? collect())->flatten()->isEmpty() && ($activeChallenges ?? collect())->isEmpty())
            <div class="card custom-card">
                <div class="card-body text-center py-5">
                    <div class="empty-state-icon mx-auto mb-3"><i class="ri-focus-3-line"></i></div>
                    <p class="text-muted mb-1">لا توجد تحديات متاحة حالياً</p>
                    <p class="text-muted fs-12 mb-0">عد لاحقاً لاكتشاف تحديات جديدة</p>
                </div>
            </div>
        @endif

    </div>
</div>
@stop

@push('scripts')
<script>
document.querySelectorAll('[data-challenge-accept]').forEach(function (btn) {
    btn.addEventListener('click', async function (e) {
        e.stopPropagation();
        const id = this.dataset.challengeId;
        const res = await fetch(`{{ url('/student/gamification/challenges') }}/${id}/accept`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        const data = await res.json();
        if (data.success) location.reload();
        else alert(data.message || 'تعذّر قبول التحدي');
    });
});
</script>
@endpush
