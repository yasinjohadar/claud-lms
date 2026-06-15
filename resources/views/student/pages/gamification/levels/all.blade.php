@extends('student.layouts.master')
@section('page-title') جميع المستويات @stop
@section('content')
<div class="main-content app-content student-gamification-dashboard">
    <div class="container-fluid pb-3">
        <div class="d-md-flex align-items-center justify-content-between my-4">
            <h4 class="mb-0">جميع المستويات</h4>
            <a href="{{ route('gamification.levels.index') }}" class="btn btn-light border btn-sm">رجوع</a>
        </div>
        <div class="row g-3">
            @foreach($levels as $level)
                <div class="col-lg-3 col-md-4 col-6">
                    <a href="{{ route('gamification.levels.show', $level) }}" class="text-decoration-none">
                        <div class="card custom-card h-100 {{ ($currentLevel ?? 1) >= $level->level ? 'border-success' : 'opacity-75' }}">
                            <div class="card-body text-center">
                                <div class="fw-bold fs-24 text-primary mb-1">{{ $level->level }}</div>
                                <h6 class="mb-1">{{ $level->name }}</h6>
                                <p class="text-muted fs-12 mb-0">{{ number_format($level->xp_required) }} XP</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@stop
