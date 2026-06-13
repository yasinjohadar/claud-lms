@extends('admin.layouts.master')

@section('page-title')
    تعديل عضو فريق
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'فريق العمل', 'url' => route('admin.team-members.index')],
                ['label' => Str::limit($member->display_name, 40)],
            ],
            'title' => 'تعديل عضو الفريق',
            'subtitle' => $member->display_name,
            'actions' => '<a href="' . route('admin.team-members.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <form method="POST" action="{{ route('admin.team-members.update', $member) }}" enctype="multipart/form-data" id="teamMemberForm">
            @csrf
            @method('PUT')
            @include('admin.pages.team-members._form', ['member' => $member])
        </form>
    </div>
</div>
@endsection
