@extends('admin.layouts.master')

@section('page-title')
    تعديل مورد عام
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الموارد العامة', 'url' => route('admin.public-resources.index')],
                ['label' => Str::limit($resource->title, 40)],
            ],
            'title' => 'تعديل المورد العام',
            'subtitle' => $resource->title,
            'actions' => '<a href="' . route('admin.public-resources.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <form method="POST" action="{{ route('admin.public-resources.update', $resource) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.pages.public-resources._form', ['resource' => $resource])
        </form>

    </div>
</div>
@endsection
