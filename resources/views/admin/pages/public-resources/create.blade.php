@extends('admin.layouts.master')

@section('page-title')
    إضافة مورد عام
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الموارد العامة', 'url' => route('admin.public-resources.index')],
                ['label' => 'إضافة مورد'],
            ],
            'title' => 'إضافة مورد عام جديد',
            'subtitle' => 'مورد مستقل على مستوى الموقع — بدون ارتباط بكورس',
            'actions' => '<a href="' . route('admin.public-resources.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <form method="POST" action="{{ route('admin.public-resources.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.pages.public-resources._form')
        </form>

    </div>
</div>
@endsection
