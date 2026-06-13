@extends('admin.layouts.master')

@section('page-title')
    تعديل تاغ
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
                ['label' => 'تعديل تاغ'],
            ],
            'title' => 'تعديل التاغ',
            'subtitle' => $tag->name,
            'actions' => '<a href="' . route('admin.courses.tags.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع للقائمة</a>',
        ])

        <form action="{{ route('admin.courses.tags.update', $tag) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.courses.tags._form', ['tag' => $tag])
        </form>
    </div>
</div>
@endsection
