@extends('student.layouts.master')

@section('page-title', 'لوحة الطالب')

@section('content')
<div class="mb-4">
    <h1 class="h3 text-white mb-1">مرحباً، {{ auth()->user()->name }}</h1>
    <p class="text-secondary mb-0">رمز الطالب: {{ $student->student_code }}</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="student-stat-card">
            <div class="text-secondary small">كورسات نشطة</div>
            <div class="value">{{ $stats['active_courses'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="student-stat-card">
            <div class="text-secondary small">متوسط التقدم</div>
            <div class="value">{{ $stats['avg_progress'] }}%</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="student-stat-card">
            <div class="text-secondary small">كورسات مكتملة</div>
            <div class="value">{{ $stats['completed_courses'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="student-stat-card">
            <div class="text-secondary small">الطلبات</div>
            <div class="value">{{ $stats['orders_count'] }}</div>
        </div>
    </div>
</div>

<div class="card border-secondary bg-dark text-white">
    <div class="card-header border-secondary d-flex justify-content-between align-items-center">
        <span class="fw-bold">آخر التسجيلات</span>
        <a href="{{ route('student.courses.index') }}" class="btn btn-sm btn-outline-info">عرض الكل</a>
    </div>
    <div class="card-body p-0">
        @if($recentEnrollments->isEmpty())
            <p class="text-secondary text-center py-4 mb-0">لم تسجّل في أي كورس بعد.</p>
        @else
            <div class="list-group list-group-flush">
                @foreach($recentEnrollments as $enrollment)
                    <div class="list-group-item bg-dark text-white border-secondary d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $enrollment->course?->title }}</div>
                            <small class="text-secondary">{{ $enrollment->enrolled_at?->locale('ar')->diffForHumans() }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-info">{{ $enrollment->progress_percent }}%</span>
                            @if($enrollment->course)
                                <a href="{{ route('courses.show', $enrollment->course->slug) }}" class="btn btn-sm btn-outline-light ms-2">فتح</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<p class="text-secondary small mt-4 mb-0">
    <i class="ri-information-line"></i>
    هذه لوحة أولية — سيتم توسيعها لاحقاً بميزات التعلم الكاملة.
</p>
@endsection
