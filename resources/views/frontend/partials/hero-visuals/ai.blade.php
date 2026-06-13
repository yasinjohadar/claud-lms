@php
    $extras = $slide->visual_extras ?? [];
    $centerIcon = $extras['center_icon'] ?? 'fas fa-brain';
    $tags = $extras['ai_tags'] ?? [];
@endphp
<div class="hero-visual-card hero-visual-ai">
    <div class="hero-ai-core">
        <div class="hero-ai-ring hero-ai-ring-1"></div>
        <div class="hero-ai-ring hero-ai-ring-2"></div>
        <div class="hero-ai-ring hero-ai-ring-3"></div>
        <div class="hero-ai-icon"><i class="{{ $centerIcon }}"></i></div>
    </div>
    @if($tags)
    <div class="hero-ai-tags">
        @foreach($tags as $tag)<span>{{ $tag }}</span>@endforeach
    </div>
    @endif
</div>
