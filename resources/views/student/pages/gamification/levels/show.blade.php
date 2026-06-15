@extends('student.layouts.master')
@section('page-title') المستوى {{ $level->level }} @stop
@section('content')
<div class="main-content app-content student-gamification-dashboard">
    <div class="container-fluid pb-3">
        <div class="d-md-flex align-items-center justify-content-between my-4">
            <div>
                <h4 class="mb-1">المستوى {{ $level->level }} — {{ $level->name }}</h4>
                <p class="text-muted fs-13 mb-0">{{ $level->description }}</p>
            </div>
            <a href="{{ route('gamification.levels.all') }}" class="btn btn-light border btn-sm">كل المستويات</a>
        </div>
        <div class="card custom-card">
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><span class="text-muted">XP المطلوب:</span> <strong>{{ number_format($level->xp_required) }}</strong></li>
                    <li class="mb-2"><span class="text-muted">مكافأة النقاط:</span> <strong>{{ number_format($level->points_reward) }}</strong></li>
                    <li class="mb-2"><span class="text-muted">حالتك:</span>
                        @if($isUnlocked ?? false)
                            <span class="badge bg-success-transparent text-success">مفتوح</span>
                        @else
                            <span class="badge bg-secondary-transparent">مقفل — تحتاج {{ number_format($xpNeeded ?? 0) }} XP</span>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@stop
