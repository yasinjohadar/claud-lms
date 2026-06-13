<aside class="course-resource-sidebar section-fade-up">
    <div class="course-resource-sidebar__card">
        <h3 class="course-resource-sidebar__title">الكورس</h3>
        <p class="course-resource-sidebar__course-name mb-2">{{ $course->title }}</p>
        @if($course->category)
            <span class="badge bg-glass text-accent">{{ $course->category->name }}</span>
        @endif
    </div>

    @if(isset($resources) && $resources->isNotEmpty())
        <div class="course-resource-sidebar__card">
            <h3 class="course-resource-sidebar__title">الموارد العامة</h3>
            <ul class="course-resource-sidebar__nav list-unstyled mb-0">
                @foreach($resources as $item)
                    <li class="course-resource-sidebar__nav-item">
                        <a href="{{ route('courses.resources.show', [$course->slug, $item->slug]) }}"
                           class="course-resource-sidebar__nav-link {{ !empty($activeResource) && $activeResource->id === $item->id ? 'is-active' : '' }}">
                            <i class="fas {{ $item->file_icon }}"></i>
                            <span>{{ $item->title }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(!empty($activeResource))
        <div class="course-resource-sidebar__card">
            <h3 class="course-resource-sidebar__title">المورد الحالي</h3>
            <ul class="course-resource-sidebar__list list-unstyled mb-0">
                <li>
                    <span class="label">النوع</span>
                    <span class="value">{{ $activeResource->type_label }}</span>
                </li>
                @if($activeResource->isFile() && $activeResource->file_original_name)
                    <li>
                        <span class="label">الملف</span>
                        <span class="value en-text">{{ Str::limit($activeResource->file_original_name, 24) }}</span>
                    </li>
                @endif
                @if($activeResource->isFile() && $activeResource->formatted_file_size)
                    <li>
                        <span class="label">الحجم</span>
                        <span class="value">{{ $activeResource->formatted_file_size }}</span>
                    </li>
                @endif
            </ul>
        </div>
    @endif

    <div class="course-resource-sidebar__card">
        <h3 class="course-resource-sidebar__title">روابط سريعة</h3>
        <div class="d-grid gap-2">
            @if(!request()->routeIs('courses.resources'))
                <a href="{{ route('courses.resources', $course->slug) }}" class="btn btn-accent">
                    <i class="fas fa-folder-open me-2"></i> كل الموارد
                </a>
            @endif
            <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-outline-accent">
                <i class="fas fa-book me-2"></i> صفحة الكورس
            </a>
        </div>
    </div>
</aside>
