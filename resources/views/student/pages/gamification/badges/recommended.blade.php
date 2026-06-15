@extends('student.layouts.master')

@section('page-title')
    شارات موصى بها
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
                ['label' => 'موصى بها'],
            ],
            'title' => 'شارات قريبة من الإنجاز',
            'subtitle' => 'شارات يمكنك الحصول عليها قريباً بناءً على تقدّمك الحالي',
            'actions' => '
                <a href="' . route('gamification.badges.index') . '" class="btn btn-primary btn-wave">
                    <i class="ri-arrow-right-line me-1"></i>كل الشارات
                </a>
            ',
        ])

        <div class="row g-3">
            @forelse($recommendations as $index => $item)
                @php
                    $badge = $item['badge'] ?? $item;
                    $progress = $item['progress'] ?? ['progress' => 0];
                @endphp
                @include('student.pages.gamification.partials.badge-card', [
                    'badge' => $badge,
                    'isEarned' => false,
                    'progress' => $progress,
                    'index' => $index,
                ])
            @empty
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            @include('student.pages.gamification.partials.badges-empty', [
                                'title' => 'لا توجد توصيات حالياً',
                                'message' => 'استمر في التعلم وستظهر الشارات القريبة هنا',
                            ])
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>
@stop
