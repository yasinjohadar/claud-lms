@php
    $category = $post->category;
    $categoryName = $category?->name ?? 'عام';
    $categoryColor = $category?->color;
    $imageUrl = $post->featured_image
        ? blog_image_url($post->featured_image)
        : 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=800&q=80';
    $publishedDate = $post->published_at?->translatedFormat('d M Y') ?? '';
    $readingMinutes = $post->reading_time ? $post->reading_time . ' د' : '5 د';
    $authorName = $post->author?->name ?? 'فريق إديوماتيك';
@endphp
<article class="blog-card">
    <a href="{{ route('blog.show', $post->slug) }}" class="blog-card-image-link">
        <div class="blog-image-wrapper">
            <span class="blog-category-badge" @if($categoryColor) style="--badge-color: {{ $categoryColor }};" @endif>{{ $categoryName }}</span>
            <span class="blog-read-time"><i class="far fa-clock"></i> {{ $readingMinutes }}</span>
            <img src="{{ $imageUrl }}" alt="{{ $post->featured_image_alt ?? $post->title }}" class="blog-card-img">
        </div>
    </a>
    <div class="blog-card-body">
        <div class="blog-meta">
            @if($publishedDate)
                <span><i class="far fa-calendar-alt"></i> {{ $publishedDate }}</span>
            @endif
            <span><i class="far fa-comment"></i> {{ $post->comments_count ?? 0 }}</span>
        </div>
        <h3 class="blog-title"><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
        <p class="blog-excerpt">{{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content ?? ''), 120) }}</p>
        <div class="blog-card-footer">
            <div class="blog-author">
                <span class="blog-author-avatar"><i class="fas fa-user-edit"></i></span>
                <span class="blog-author-name">{{ $authorName }}</span>
            </div>
            <a href="{{ route('blog.show', $post->slug) }}" class="read-more-link">اقرأ المزيد <i class="fas fa-arrow-left"></i></a>
        </div>
    </div>
</article>
