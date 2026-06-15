@extends('student.layouts.master')

@section('page-title')
    {{ $badge->name }}
@stop

@section('content')
@php
    $rarityMap = [
        'common' => ['class' => 'secondary', 'label' => 'عادية'],
        'rare' => ['class' => 'info', 'label' => 'نادرة'],
        'epic' => ['class' => 'primary', 'label' => 'ملحمية'],
        'legendary' => ['class' => 'warning', 'label' => 'أسطورية'],
        'mythic' => ['class' => 'danger', 'label' => 'خرافية'],
    ];
    $rarity = $rarityMap[$badge->rarity ?? 'common'] ?? $rarityMap['common'];
    $points = $badge->points_value ?? $badge->points_reward ?? 0;
    $icon = $badge->icon ?? '🏅';
    $progressPct = (float) ($progress['progress'] ?? 0);
@endphp

<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب', 'url' => route('gamification.dashboard')],
                ['label' => 'شاراتي', 'url' => route('gamification.badges.index')],
                ['label' => $badge->name],
            ],
            'title' => $badge->name,
            'subtitle' => $badge->description ?: 'تفاصيل الشارة وشروط الحصول عليها',
            'actions' => '
                <a href="' . route('gamification.badges.index') . '" class="btn btn-primary btn-wave">
                    <i class="ri-arrow-right-line me-1"></i>العودة للشارات
                </a>
            ',
        ])

        <div class="row g-4 justify-content-center">
            <div class="col-lg-6">
                <div class="card custom-card border border-{{ $rarity['class'] }} text-center">
                    <div class="card-body py-5">
                        <span class="badge bg-{{ $rarity['class'] }}-transparent text-{{ $rarity['class'] }} mb-3">
                            {{ $rarity['label'] }}
                        </span>

                        <div class="mx-auto mb-4">
                            <span class="avatar avatar-xxl rounded-circle bg-{{ $rarity['class'] }}-transparent d-inline-flex align-items-center justify-content-center {{ !$isEarned ? 'opacity-75' : '' }}"
                                  style="width:96px;height:96px;font-size:2.75rem;">
                                {{ $icon }}
                            </span>
                        </div>

                        <p class="text-muted mb-4 mx-auto" style="max-width:420px;">{{ $badge->description }}</p>

                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                            <span class="badge bg-primary-transparent text-primary">+{{ number_format($points) }} نقطة</span>
                            @if($isEarned)
                                <span class="badge bg-success-transparent text-success">
                                    <i class="ri-checkbox-circle-line me-1"></i>مكتسبة
                                </span>
                            @else
                                <span class="badge bg-secondary-transparent text-secondary">
                                    <i class="ri-lock-line me-1"></i>غير مكتسبة
                                </span>
                            @endif
                        </div>

                        @if($isEarned && $userBadge?->awarded_at)
                            <p class="text-muted fs-13 mb-0">
                                <i class="ri-calendar-line me-1"></i>
                                تاريخ الحصول: {{ $userBadge->awarded_at->locale('ar')->translatedFormat('j F Y') }}
                            </p>
                        @elseif(!$isEarned && $progressPct > 0)
                            <div class="mx-auto" style="max-width:320px;">
                                <div class="d-flex justify-content-between fs-12 mb-1">
                                    <span class="text-muted">التقدم</span>
                                    <span class="fw-semibold">{{ round($progressPct) }}%</span>
                                </div>
                                <div class="progress rounded-pill mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $rarity['class'] }}" style="width: {{ min(100, $progressPct) }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop
