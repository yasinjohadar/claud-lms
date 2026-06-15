@php
    $methods = $earningMethods ?? [];
    $preview = array_slice($methods, 0, 6);
@endphp

@if(count($preview) > 0)
    <div class="row g-3">
        @foreach($preview as $index => $method)
            @include('student.pages.gamification.points.partials.earning-method-card', [
                'method' => $method,
                'index' => $index,
            ])
        @endforeach
    </div>
@else
    <div class="empty-state py-4">
        <div class="empty-state-icon mx-auto mb-3"><i class="ri-lightbulb-line"></i></div>
        <p class="text-muted mb-0">لا توجد طرق كسب متاحة حالياً</p>
    </div>
@endif
