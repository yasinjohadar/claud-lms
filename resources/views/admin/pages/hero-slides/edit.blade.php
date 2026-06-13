@extends('admin.layouts.master')

@section('page-title')
    تعديل شريحة
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')
        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'سلايدر الرئيسية', 'url' => route('admin.hero-slides.index')],
                ['label' => $slide->admin_title],
            ],
            'title' => 'تعديل: ' . $slide->admin_title,
            'actions' => '<a href="' . route('admin.hero-slides.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <form method="POST" action="{{ route('admin.hero-slides.update', $slide) }}" enctype="multipart/form-data" id="heroSlideForm">
            @csrf
            @method('PUT')
            @include('admin.pages.hero-slides._form', ['slide' => $slide])
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin-hero-slides.js') }}"></script>
@endpush
