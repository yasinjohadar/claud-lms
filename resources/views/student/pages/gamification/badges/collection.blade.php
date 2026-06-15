@extends('student.layouts.master')

@section('page-title')
    مجموعة الشارات
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب', 'url' => route('gamification.dashboard')],
                ['label' => 'شاراتي', 'url' => route('gamification.badges.index')],
                ['label' => 'مجموعتي'],
            ],
            'title' => 'مجموعتي',
            'subtitle' => 'عرض معرض لكل الشارات التي حصلت عليها — ' . ($stats['total_earned'] ?? 0) . ' من ' . ($stats['total_available'] ?? 0),
            'actions' => '
                <a href="' . route('gamification.badges.index') . '" class="btn btn-primary btn-wave">
                    <i class="ri-arrow-right-line me-1"></i>كل الشارات
                </a>
            ',
        ])

        <div class="row g-3">
            @forelse($badges as $index => $userBadge)
                @include('student.pages.gamification.partials.badge-card', [
                    'badge' => $userBadge->badge,
                    'isEarned' => true,
                    'awardedAt' => $userBadge->awarded_at,
                    'index' => $index,
                ])
            @empty
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            @include('student.pages.gamification.partials.badges-empty', [
                                'title' => 'لم تحصل على شارات بعد',
                                'message' => 'أكمل الأنشطة لبناء مجموعتك',
                            ])
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>
@stop
