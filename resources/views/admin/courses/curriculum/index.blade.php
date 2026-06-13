@extends('admin.layouts.master')

@section('page-title')
    منهج الكورس — {{ $course->title }}
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الكورسات', 'url' => route('admin.courses.index')],
                ['label' => Str::limit($course->title, 40), 'url' => route('admin.courses.edit', $course)],
                ['label' => 'المنهاج'],
            ],
            'title' => 'إدارة منهج الكورس',
            'subtitle' => $course->title,
            'actions' => '<a href="' . route('admin.courses.edit', $course) . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع للتعديل</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-list-ordered',
                'label' => 'الأقسام', 'value' => $course->sections->count(),
                'valueId' => 'curriculumSectionsCount',
                'hint' => 'أقسام المنهاج',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-play-circle-line',
                'label' => 'الدروس', 'value' => $course->lessons_count,
                'valueId' => 'curriculumLessonsCount',
                'hint' => 'إجمالي الدروس',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-time-line',
                'label' => 'المدة التقريبية', 'value' => $course->duration_hours . ' ساعة',
                'valueId' => 'curriculumDurationHours',
                'hint' => 'مدة الفيديوهات',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-folder-open-line',
                'label' => 'الموارد', 'value' => $course->resources()->count(),
                'valueId' => 'curriculumResourcesCount',
                'hint' => 'روابط وملفات',
            ])
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold fs-16">أقسام المنهاج</span>
                    <span class="table-count-badge">{{ $course->sections->count() }}</span>
                </div>
                <button type="button" class="btn btn-primary btn-sm btn-wave" data-bs-toggle="modal" data-bs-target="#sectionModal" data-section-mode="create">
                    <i class="ri-add-line me-1"></i> إضافة قسم
                </button>
            </div>
            <div class="card-body p-0" id="curriculumSectionsTarget">
                @include('admin.courses.curriculum.partials.sections')
            </div>
        </div>

        @php
            $globalResources = $course->resources->whereNull('course_section_id');
        @endphp

        <div class="card custom-card data-table-card mt-3">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold fs-16">الموارد العامة للكورس</span>
                    <span class="table-count-badge">{{ $globalResources->count() }}</span>
                </div>
                <div class="d-flex gap-2">
                    @if($globalResources->where('is_published', true)->isNotEmpty())
                        <a href="{{ route('courses.resources', $course->slug) }}" target="_blank" class="btn btn-light border btn-sm btn-wave">
                            <i class="ri-external-link-line me-1"></i> صفحة الموارد
                        </a>
                    @endif
                    <button type="button" class="btn btn-primary btn-sm btn-wave"
                            data-bs-toggle="modal" data-bs-target="#resourceModal"
                            data-resource-mode="create"
                            data-resource-scope="global">
                        <i class="ri-add-line me-1"></i> إضافة مورد
                    </button>
                </div>
            </div>
            <div id="curriculumGlobalResourcesTarget">
                @include('admin.courses.curriculum.partials.global-resources', ['globalResources' => $globalResources])
            </div>
        </div>

    </div>
</div>

@include('admin.courses.curriculum.partials.modals')
@stop

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script src="{{ asset('assets/js/admin-course-curriculum.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.AdminCourseCurriculum) {
        window.AdminCourseCurriculum.init({
            courseId: {{ $course->id }},
            routes: {
                sectionsStore: @json(route('admin.courses.curriculum.sections.store', $course)),
                sectionsUpdate: @json(url('/admin/courses/' . $course->id . '/curriculum/sections')),
                sectionsDestroy: @json(url('/admin/courses/' . $course->id . '/curriculum/sections')),
                lessonsStore: @json(url('/admin/courses/' . $course->id . '/curriculum/sections')),
                lessonsUpdate: @json(url('/admin/courses/' . $course->id . '/curriculum/lessons')),
                lessonsDestroy: @json(url('/admin/courses/' . $course->id . '/curriculum/lessons')),
                resourcesStore: @json(route('admin.courses.curriculum.resources.store', $course)),
                resourcesUpdate: @json(url('/admin/courses/' . $course->id . '/curriculum/resources')),
                resourcesDestroy: @json(url('/admin/courses/' . $course->id . '/curriculum/resources')),
                reorder: @json(route('admin.courses.curriculum.reorder', $course)),
            }
        });
    }
});
</script>
@endpush
