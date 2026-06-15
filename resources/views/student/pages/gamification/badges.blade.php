@extends('student.layouts.master')

@section('page-title')
    شاراتي
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب', 'url' => route('gamification.dashboard')],
                ['label' => 'شاراتي'],
            ],
            'title' => 'شاراتي',
            'subtitle' => 'اجمع الشارات بإكمال الأنشطة والتحديات في رحلة التعلم',
            'actions' => '
                <div class="d-flex flex-wrap gap-2">
                    <a href="' . route('gamification.badges.recommended') . '" class="btn btn-light border btn-wave">
                        <i class="ri-flashlight-line me-1"></i>الأقرب للإنجاز
                    </a>
                    <a href="' . route('gamification.badges.collection') . '" class="btn btn-light border btn-wave">
                        <i class="ri-gallery-line me-1"></i>مجموعتي
                    </a>
                    <a href="' . route('gamification.dashboard') . '" class="btn btn-primary btn-wave">
                        <i class="ri-trophy-line me-1"></i>لوحة التلعيب
                    </a>
                </div>
            ',
        ])

        @include('student.pages.gamification.partials.badges-stats', ['stats' => $stats])

        <div class="card custom-card mb-4">
            <div class="card-header border-0 pb-0">
                <h5 class="card-title mb-1">
                    <i class="ri-medal-line text-success me-1"></i>
                    الشارات المكتسبة
                    <span class="text-muted fw-normal fs-13">({{ count($earnedBadges ?? []) }})</span>
                </h5>
                <p class="text-muted fs-12 mb-0">الشارات التي حصلت عليها بالفعل</p>
            </div>
            <div class="card-body pt-3">
                @if(count($earnedBadges ?? []) > 0)
                    <div class="row g-3">
                        @foreach($earnedBadges as $index => $item)
                            @php
                                $badge = $item->badge ?? $item;
                                $awardedAt = $item->awarded_at ?? optional($item->pivot)->awarded_at ?? null;
                            @endphp
                            @include('student.pages.gamification.partials.badge-card', [
                                'badge' => $badge,
                                'isEarned' => true,
                                'awardedAt' => $awardedAt,
                                'progress' => ['progress' => 100],
                                'index' => $index,
                            ])
                        @endforeach
                    </div>
                @else
                    @include('student.pages.gamification.partials.badges-empty', [
                        'title' => 'لم تحصل على شارات بعد',
                        'message' => 'استمر في التعلم لكسب أول شارة!',
                    ])
                @endif
            </div>
        </div>

        <div class="card custom-card">
            <div class="card-header border-0 pb-0">
                <h5 class="card-title mb-1">
                    <i class="ri-grid-line text-primary me-1"></i>
                    جميع الشارات
                </h5>
                <p class="text-muted fs-12 mb-0">استكشف كل الشارات المتاحة واطّلع على شروط الحصول عليها</p>
            </div>
            <div class="card-body pt-3">
                @include('student.pages.gamification.partials.badges-filters')

                @if(count($allBadges ?? []) > 0)
                    <div class="row g-3" id="badgeGrid">
                        @foreach($allBadges as $index => $badge)
                            @include('student.pages.gamification.partials.badge-card', [
                                'badge' => $badge,
                                'isEarned' => $badge->is_earned ?? false,
                                'progress' => $badge->progress ?? ['progress' => 0],
                                'index' => $index,
                            ])
                        @endforeach
                    </div>
                @else
                    @include('student.pages.gamification.partials.badges-empty', [
                        'title' => 'لا توجد شارات مطابقة',
                        'message' => 'جرّب تغيير الفلتر أو عد لاحقاً.',
                    ])
                @endif
            </div>
        </div>

    </div>
</div>
@stop
