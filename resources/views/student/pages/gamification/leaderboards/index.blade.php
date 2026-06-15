@extends('student.layouts.master')

@section('page-title')
    لوحات المتصدرين
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @php $catalog = app(\App\Services\Gamification\LeaderboardCatalog::class); @endphp

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب', 'url' => route('gamification.dashboard')],
                ['label' => 'لوحات المتصدرين'],
            ],
            'title' => 'لوحات المتصدرين',
            'subtitle' => 'تنافس مع زملائك، تابع ترتيبك، واكتشف من يتصدر كل لوحة',
            'actions' => '
                <div class="d-flex flex-wrap gap-2">
                    <a href="' . route('gamification.leaderboards.my-rank') . '" class="btn btn-light border btn-wave">
                        <i class="ri-user-star-line me-1"></i>ترتيبي
                    </a>
                    <a href="' . route('gamification.dashboard') . '" class="btn btn-primary btn-wave">
                        <i class="ri-trophy-line me-1"></i>لوحة التلعيب
                    </a>
                </div>
            ',
        ])

        @include('student.pages.gamification.leaderboards.partials.index-stats', ['indexStats' => $indexStats ?? []])

        <div class="d-flex align-items-center gap-2 mb-3">
            <h5 class="mb-0 fw-semibold">
                <i class="ri-trophy-line text-warning me-1"></i>
                اللوحات النشطة
                <span class="text-muted fw-normal fs-13">({{ $leaderboards->count() }})</span>
            </h5>
        </div>

        <div class="row g-3">
            @forelse ($leaderboards as $index => $board)
                @include('student.pages.gamification.leaderboards.partials.board-card', [
                    'board' => $board,
                    'catalog' => $catalog,
                    'index' => $index,
                ])
            @empty
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-body text-center py-5">
                            <div class="empty-state-icon mx-auto mb-3"><i class="ri-trophy-line"></i></div>
                            <p class="text-muted mb-1">لا توجد لوحات متاحة حالياً</p>
                            <p class="text-muted fs-12 mb-0">ستظهر لوحات المتصدرين هنا عند تفعيلها من الإدارة</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>
@stop
