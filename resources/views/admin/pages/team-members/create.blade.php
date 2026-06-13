@extends('admin.layouts.master')

@section('page-title')
    إضافة عضو فريق
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'فريق العمل', 'url' => route('admin.team-members.index')],
                ['label' => 'إضافة عضو'],
            ],
            'title' => 'إضافة عضو فريق جديد',
            'subtitle' => 'اختر مستخدماً موجوداً أو أدخل بيانات يدوية',
            'actions' => '<a href="' . route('admin.team-members.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <form method="POST" action="{{ route('admin.team-members.store') }}" enctype="multipart/form-data" id="teamMemberForm">
            @csrf
            @include('admin.pages.team-members._form')
        </form>
    </div>
</div>
@endsection
