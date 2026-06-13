@extends('admin.layouts.master')

@section('page-title')
    إضافة طالب
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')
        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الطلاب', 'url' => route('admin.students.index')],
                ['label' => 'إضافة طالب'],
            ],
            'title' => 'إضافة طالب جديد',
            'subtitle' => 'إنشاء مستخدم جديد أو ربط مستخدم موجود',
            'actions' => '<a href="' . route('admin.students.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <form method="POST" action="{{ route('admin.students.store') }}">
            @csrf
            @include('admin.pages.students._form')
        </form>
    </div>
</div>
@endsection
