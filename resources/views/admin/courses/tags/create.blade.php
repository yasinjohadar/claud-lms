@extends('admin.layouts.master')

@section('page-title')
    إضافة تاغ
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
                ['label' => 'التاغات', 'url' => route('admin.courses.tags.index')],
                ['label' => 'إضافة تاغ'],
            ],
            'title' => 'إضافة تاغ جديد',
            'subtitle' => 'إنشاء تاغ لتصنيف الكورسات',
            'actions' => '<a href="' . route('admin.courses.tags.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع للقائمة</a>',
        ])

        <form action="{{ route('admin.courses.tags.store') }}" method="POST">
            @csrf
            @include('admin.courses.tags._form')
        </form>
    </div>
</div>
@endsection
