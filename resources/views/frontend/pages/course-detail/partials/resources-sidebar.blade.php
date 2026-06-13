@if($globalResources->isNotEmpty())
    <div class="course-purchase-resources">
        <h3 class="course-purchase-resources__title">
            <i class="fas fa-folder-open"></i> الموارد العامة
        </h3>
        <p class="course-purchase-resources__subtitle">
            <span class="en-text">{{ $globalResources->count() }}</span> مورد إضافي في صفحات مستقلة
        </p>
        <ul class="course-purchase-resources__list list-unstyled mb-3">
            @foreach($globalResources->take(4) as $resource)
                <li>
                    <a href="{{ route('courses.resources.show', [$course->slug, $resource->slug]) }}" class="course-purchase-resources__link">
                        <i class="fas {{ $resource->file_icon }}"></i>
                        <span>{{ $resource->title }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
        <a href="{{ route('courses.resources', $course->slug) }}" class="btn btn-outline-accent w-100 course-purchase-resources__btn">
            عرض كل الموارد <i class="fas fa-arrow-left ms-2"></i>
        </a>
    </div>
@endif
