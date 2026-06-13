@if($slide->visual_image_url)
    <div class="hero-visual-card hero-visual-image">
        <img src="{{ $slide->visual_image_url }}" alt="{{ $slide->visual_image_alt ?? $slide->admin_title }}" class="img-fluid rounded-4">
    </div>
@elseif($slide->visual_icon)
    <div class="hero-visual-card">
        <div class="hero-visual-main"><i class="{{ $slide->visual_icon }}"></i></div>
    </div>
@endif
