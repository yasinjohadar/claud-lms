@extends('admin.layouts.master')

@section('page-title')
    تعديل تصنيف كورس
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
                ['label' => 'التصنيفات', 'url' => route('admin.courses.categories.index')],
                ['label' => 'تعديل تصنيف'],
            ],
            'title' => 'تعديل التصنيف',
            'subtitle' => $category->name,
            'actions' => '<a href="' . route('admin.courses.categories.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع للقائمة</a>',
        ])

        <form action="{{ route('admin.courses.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.courses.categories._form', ['category' => $category])
        </form>
    </div>
</div>
@endsection
