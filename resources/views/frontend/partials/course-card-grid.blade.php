@php
    $colClass = $colClass ?? 'col-md-6 col-lg-4';
    $showCart = $showCart ?? true;
@endphp
<div class="{{ $colClass }}">
    @include('frontend.partials.course-card', ['course' => $course, 'showCart' => $showCart])
</div>
