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
            <div class="col-md-4">
                <div class="card custom-card form-card h-100">
                    <div class="card-body">
                        <div class="text-muted fs-12 mb-1">الأقسام</div>
                        <div class="fw-bold fs-20" id="curriculumSectionsCount">{{ $course->sections->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card custom-card form-card h-100">
                    <div class="card-body">
                        <div class="text-muted fs-12 mb-1">الدروس</div>
                        <div class="fw-bold fs-20" id="curriculumLessonsCount">{{ $course->lessons_count }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card custom-card form-card h-100">
                    <div class="card-body">
                        <div class="text-muted fs-12 mb-1">المدة التقريبية</div>
                        <div class="fw-bold fs-20" id="curriculumDurationHours">{{ $course->duration_hours }} <span class="fs-14 fw-normal text-muted">ساعة</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card custom-card form-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-list-ordered me-1 text-primary"></i> أقسام المنهاج</h6>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#sectionModal" data-section-mode="create">
                    <i class="ri-add-line me-1"></i> إضافة قسم
                </button>
            </div>
            <div class="card-body" id="curriculumSectionsTarget">
                @include('admin.courses.curriculum.partials.sections')
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
                reorder: @json(route('admin.courses.curriculum.reorder', $course)),
            }
        });
    }
});
</script>
@endpush
