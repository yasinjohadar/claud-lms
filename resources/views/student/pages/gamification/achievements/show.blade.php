@extends('student.layouts.master')

@section('page-title')
    {{ $achievement->name }}
@stop

@section('content')
@php
    $isCompleted = in_array($userAchievement->status, ['completed', 'claimed'], true);
    $progress = (float) $userAchievement->progress_percentage;
    $tier = $achievement->tier ?? 'bronze';
    $tierLabels = [
        'bronze' => 'برونزي', 'silver' => 'فضي', 'gold' => 'ذهبي',
        'platinum' => 'بلاتيني', 'diamond' => 'ماسي',
    ];
    $requirementText = \App\Support\Gamification\AchievementCriteriaMapper::formatForDisplay(
        $achievement->criteria,
        $achievement->target_value
    );
@endphp

<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب', 'url' => route('gamification.dashboard')],
                ['label' => 'إنجازاتي', 'url' => route('gamification.achievements.index')],
                ['label' => $achievement->name],
            ],
            'title' => $achievement->name,
            'subtitle' => $achievement->description ?: 'تفاصيل الإنجاز وتقدّمك الحالي',
            'actions' => '
                <a href="' . route('gamification.achievements.index') . '" class="btn btn-primary btn-wave">
                    <i class="ri-arrow-right-line me-1"></i>العودة للإنجازات
                </a>
            ',
        ])

        <div class="row g-4 justify-content-center">
            <div class="col-lg-8">
                <div class="card custom-card">
                    <div class="card-body text-center py-4">
                        <div class="fs-1 mb-3">{{ $achievement->icon ?? '🏆' }}</div>
                        <span class="badge bg-primary-transparent mb-3">{{ $tierLabels[$tier] ?? $tier }}</span>

                        <div class="row g-3 mb-4 text-start">
                            <div class="col-md-4">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted fs-12 mb-1">المتطلب</div>
                                    <div class="fw-semibold fs-13">{{ $requirementText }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted fs-12 mb-1">المكافأة</div>
                                    <div class="fw-semibold fs-13">{{ number_format($achievement->points_reward ?? 0) }} نقطة</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-muted fs-12 mb-1">الحالة</div>
                                    <div class="fw-semibold fs-13">{{ $isCompleted ? 'مكتمل' : 'قيد التقدم' }}</div>
                                </div>
                            </div>
                        </div>

                        @if($isCompleted)
                            <span class="badge bg-success-transparent fs-13 px-3 py-2 mb-3">
                                <i class="ri-checkbox-circle-line me-1"></i>مكتمل
                            </span>
                            @if($userAchievement->completed_at)
                                <p class="text-muted mb-3">اكتمل في {{ $userAchievement->completed_at->format('Y/m/d H:i') }}</p>
                            @endif
                            @if($userAchievement->status === 'completed' && ($achievement->points_reward ?? 0) > 0)
                                <form action="{{ route('gamification.achievements.claim', $userAchievement) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-wave px-4">
                                        <i class="ri-gift-line me-1"></i>المطالبة بالمكافأة
                                    </button>
                                </form>
                            @elseif($userAchievement->status === 'claimed')
                                <span class="badge bg-info-transparent">تم المطالبة بالمكافأة</span>
                            @endif
                        @else
                            <div class="mx-auto" style="max-width: 480px;">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted fs-13">التقدم</span>
                                    <span class="fw-bold">{{ number_format($progress, 0) }}%</span>
                                </div>
                                <div class="progress rounded-pill mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-primary" style="width: {{ max(0, min(100, $progress)) }}%"></div>
                                </div>
                                <p class="text-muted fs-13 mb-0">{{ $userAchievement->current_value }} / {{ $achievement->target_value }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop
