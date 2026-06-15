@extends('student.layouts.master')

@section('page-title')
    إنجازات موصى بها
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب', 'url' => route('gamification.dashboard')],
                ['label' => 'إنجازاتي', 'url' => route('gamification.achievements.index')],
                ['label' => 'موصى بها'],
            ],
            'title' => 'إنجازات قيد التقدم',
            'subtitle' => 'إنجازات يمكنك إتمامها قريباً بناءً على نشاطك الحالي',
            'actions' => '
                <a href="' . route('gamification.achievements.index') . '" class="btn btn-primary btn-wave">
                    <i class="ri-arrow-right-line me-1"></i>كل الإنجازات
                </a>
            ',
        ])

        <div class="row g-3">
            @forelse($recommended as $index => $userAchievement)
                @include('student.pages.gamification.partials.achievement-card', [
                    'achievement' => $userAchievement->achievement,
                    'userAchievement' => $userAchievement,
                    'index' => $index,
                ])
            @empty
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-body text-center py-5">
                            <div class="empty-state-icon mx-auto mb-3"><i class="ri-flag-line"></i></div>
                            <p class="text-muted mb-2">لا توجد إنجازات موصى بها</p>
                            <p class="text-muted fs-12 mb-3">ابدأ التعلم ليظهر تقدّمك هنا</p>
                            <a href="{{ route('gamification.achievements.index') }}" class="btn btn-sm btn-primary-light btn-wave">
                                عرض كل الإنجازات
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>
@stop
