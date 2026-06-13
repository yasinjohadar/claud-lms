@extends('student.layouts.master')

@section('page-title', 'كورساتي')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-white mb-0">كورساتي</h1>
    <a href="{{ route('courses') }}" class="btn btn-outline-info btn-sm">تصفح الكورسات</a>
</div>

<div class="row g-3">
    @forelse($enrollments as $enrollment)
        @php $course = $enrollment->course; @endphp
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-secondary bg-dark text-white">
                <div class="card-body">
                    <span class="badge bg-secondary mb-2">{{ $enrollment->status_label }}</span>
                    <h5 class="card-title">{{ $course?->title ?? 'كورس' }}</h5>
                    <p class="text-secondary small mb-3">{{ $enrollment->source_label }} • {{ $enrollment->enrolled_at?->locale('ar')->translatedFormat('j M Y') }}</p>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: {{ $enrollment->progress_percent }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small">{{ $enrollment->progress_percent }}% مكتمل</span>
                        @if($course)
                            <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-sm btn-primary">متابعة</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center text-secondary py-5">
                <i class="ri-book-open-line fs-1 d-block mb-2"></i>
                لا توجد كورسات مسجّلة بعد.
            </div>
        </div>
    @endforelse
</div>

@if($enrollments->hasPages())
    <div class="mt-4">{{ $enrollments->links() }}</div>
@endif
@endsection
