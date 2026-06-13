@extends('admin.layouts.master')

@section('page-title')
    تعديل الكورس
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
                ['label' => 'تعديل كورس'],
            ],
            'title' => 'تعديل الكورس',
            'subtitle' => $course->title,
            'actions' => '<a href="' . route('admin.courses.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع للقائمة</a>',
        ])

        <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data" data-course-post-form>
            @csrf
            @method('PUT')
            @include('admin.courses._form', ['course' => $course])
        </form>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css" rel="stylesheet" />
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js"></script>
<script src="{{ asset('assets/js/admin-blog-post-form.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.AdminBlogPostForm) {
        window.AdminBlogPostForm.init({ contentSelector: '#description' });
    }
});
</script>
@endpush
