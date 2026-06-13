@extends('admin.layouts.master')

@section('page-title')
    تعديل طالب
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')
        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الطلاب', 'url' => route('admin.students.index')],
                ['label' => $student->user?->name ?? 'طالب', 'url' => route('admin.students.show', $student)],
                ['label' => 'تعديل'],
            ],
            'title' => 'تعديل ملف الطالب',
            'actions' => '<a href="' . route('admin.students.show', $student) . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <form method="POST" action="{{ route('admin.students.update', $student) }}">
            @csrf
            @method('PUT')
            @include('admin.pages.students._form', ['student' => $student])
        </form>
    </div>
</div>
@endsection
