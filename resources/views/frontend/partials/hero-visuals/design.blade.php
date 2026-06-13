@php
    $extras = $slide->visual_extras ?? [];
    $centerIcon = $extras['center_icon'] ?? 'fas fa-paint-brush';
    $orbitIcons = $extras['orbit_icons'] ?? [];
@endphp
<div class="hero-visual-card hero-visual-design">
    <div class="hero-design-orbit">
        <div class="hero-orbit-ring"></div>
        @foreach($orbitIcons as $i => $oi)
            <div class="hero-orbit-icon hero-orbit-{{ $oi['position'] ?? ($i + 1) }}"><i class="{{ $oi['icon'] }}"></i></div>
        @endforeach
        <div class="hero-orbit-center"><i class="{{ $centerIcon }}"></i></div>
    </div>
</div>
