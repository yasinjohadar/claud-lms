@php
    $categoryColor = $course->category?->color ?? '#059669';
    $icon = $course->icon ? (str_starts_with($course->icon, 'fa') ? $course->icon : 'fa-' . $course->icon) : ($course->category?->icon ?? 'fa-book');
    if (! str_contains($icon, 'fa-')) {
        $icon = 'fas ' . $icon;
    } elseif (! str_contains($icon, 'fas ') && ! str_contains($icon, 'fab ') && ! str_contains($icon, 'far ')) {
        $icon = 'fas ' . $icon;
    }
@endphp

<header class="course-resources-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb course-detail-breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('courses') }}">الكورسات</a></li>
                <li class="breadcrumb-item"><a href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    @if(!empty($resource))
                        <a href="{{ route('courses.resources', $course->slug) }}">الموارد</a>
                    @else
                        الموارد
                    @endif
                </li>
                @if(!empty($resource))
                    <li class="breadcrumb-item active" aria-current="page">{{ $resource->title }}</li>
                @endif
            </ol>
        </nav>

        <div class="course-resources-hero__body section-fade-up">
            <div class="course-resources-hero__visual" style="--course-color: {{ $categoryColor }};">
                <i class="{{ $icon }}"></i>
            </div>
            <div class="course-resources-hero__content">
                @if(!empty($resource))
                    <span class="course-resources-eyebrow">مورد الكورس</span>
                    <h1 class="course-resources-hero__title">{{ $resource->title }}</h1>
                    <p class="course-resources-hero__subtitle mb-0">
                        {{ $resource->type_label }}
                        @if($resource->isFile() && $resource->formatted_file_size)
                            &bull; {{ $resource->formatted_file_size }}
                        @endif
                        &bull; {{ $course->title }}
                    </p>
                @else
                    <span class="course-resources-eyebrow">صفحة الموارد العامة</span>
                    <h1 class="course-resources-hero__title">موارد {{ $course->title }}</h1>
                    <p class="course-resources-hero__subtitle mb-0">
                        ملفات وروابط إضافية غير مرتبطة بأقسام المنهاج — متاحة للتحميل والاطلاع في صفحات مستقلة.
                    </p>
                    @if(isset($resources) && $resources->isNotEmpty())
                        <p class="course-resources-hero__count mt-2 mb-0">
                            <span class="en-text">{{ $resources->count() }}</span> مورد متاح
                        </p>
                    @endif
                @endif
            </div>
            <div class="course-resources-hero__actions">
                <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-outline-accent">
                    <i class="fas fa-arrow-right me-2"></i> صفحة الكورس
                </a>
                @if(!empty($resource))
                    <a href="{{ route('courses.resources', $course->slug) }}" class="btn btn-accent">
                        <i class="fas fa-folder-open me-2"></i> كل الموارد
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>
