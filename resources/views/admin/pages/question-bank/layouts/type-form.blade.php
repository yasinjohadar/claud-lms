@php
    $pageTitle = $pageTitle ?? ('إنشاء سؤال ' . ($questionType->display_name ?? ''));
    $typeTitle = $typeTitle ?? ($questionType->display_name ?? 'سؤال جديد');
    $typeSubtitle = $typeSubtitle ?? 'أكمل الحقول أدناه ثم احفظ السؤال في بنك الأسئلة.';
    $typeIcon = $typeIcon ?? 'ri-question-line';
    $typeIconColor = $typeIconColor ?? 'primary';
    $breadcrumbActive = $breadcrumbActive ?? ($questionType->display_name ?? '');
    $formEnctype = $formEnctype ?? null;
    $showBasicInfo = $showBasicInfo ?? true;
    $showExplanation = $showExplanation ?? true;
    $showMedia = $showMedia ?? true;
    $showReusable = $showReusable ?? true;
@endphp

@extends('admin.layouts.master')

@section('page-title')
    {{ $pageTitle }}
@stop

@section('css')
    @include('admin.pages.question-bank.partials.type-form-styles')
    @yield('type-css')
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'بنك الأسئلة', 'url' => route('question-bank.index')],
                ['label' => 'اختر النوع', 'url' => route('question-bank.create')],
                ['label' => $breadcrumbActive],
            ],
            'title' => $typeTitle,
            'subtitle' => $typeSubtitle,
            'actions' => '<a href="' . route('question-bank.create') . '" class="btn btn-light border btn-sm btn-wave"><i class="ri-arrow-right-line me-1"></i>تغيير النوع</a>',
        ])

        <div class="qb-type-hero d-flex align-items-center gap-3">
            <span class="qb-type-hero__badge qb-type-card__icon qb-type-card__icon--{{ $typeIconColor }}">
                <i class="{{ $typeIcon }}"></i>
            </span>
            <div>
                <h6 class="mb-1 fw-semibold">{{ $typeTitle }}</h6>
                <p class="mb-0 text-muted fs-13">{{ $typeSubtitle }}</p>
            </div>
        </div>

        <form action="{{ route('question-bank.store') }}" method="POST" @if($formEnctype) enctype="{{ $formEnctype }}" @endif>
            @csrf
            <input type="hidden" name="question_type_id" value="{{ $questionType->id }}">

            <div class="row g-4">
                <div class="col-lg-8">
                    @if($showBasicInfo)
                        @include('admin.pages.question-bank.partials.type-form-basic')
                    @endif

                    @yield('type-main-content')

                    @if($showExplanation)
                        @include('admin.pages.question-bank.partials.type-form-explanation')
                    @endif
                </div>

                <div class="col-lg-4">
                    <div class="qb-type-sidebar-sticky">
                        <div class="card custom-card form-card qb-type-card mb-4">
                            @include('admin.pages.question-bank.partials.type-form-section-title', [
                                'icon' => 'ri-settings-3-line',
                                'color' => 'secondary',
                                'title' => 'الإعدادات',
                            ])
                            <div class="card-body pt-2">
                                @hasSection('type-sidebar-settings')
                                    @yield('type-sidebar-settings')
                                @endif

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                           id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">السؤال نشط</label>
                                </div>

                                @if($showReusable)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_reusable"
                                               id="is_reusable" {{ old('is_reusable', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_reusable">قابل لإعادة الاستخدام</label>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($showMedia)
                            <div class="card custom-card form-card qb-type-card mb-4">
                                @include('admin.pages.question-bank.partials.type-form-section-title', [
                                    'icon' => 'ri-image-line',
                                    'color' => 'info',
                                    'title' => 'وسائط (اختياري)',
                                ])
                                <div class="card-body pt-2">
                                    <div class="mb-3">
                                        <label class="form-label">نوع الوسائط</label>
                                        <select name="media_type" class="form-select form-input-enhanced">
                                            <option value="">لا يوجد</option>
                                            <option value="image" {{ old('media_type') == 'image' ? 'selected' : '' }}>صورة</option>
                                            <option value="audio" {{ old('media_type') == 'audio' ? 'selected' : '' }}>صوت</option>
                                            <option value="video" {{ old('media_type') == 'video' ? 'selected' : '' }}>فيديو</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label">رابط الوسائط</label>
                                        <input type="url" name="media_url" class="form-control form-input-enhanced"
                                               placeholder="https://..." value="{{ old('media_url') }}">
                                    </div>
                                </div>
                            </div>
                        @endif

                        @hasSection('type-sidebar-extra')
                            @yield('type-sidebar-extra')
                        @endif

                        <div class="card custom-card form-card qb-type-card qb-type-actions">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary w-100 mb-2 btn-wave">
                                    <i class="ri-save-line me-2"></i>حفظ السؤال
                                </button>
                                <a href="{{ route('question-bank.create') }}" class="btn btn-light border w-100 btn-wave">
                                    <i class="ri-arrow-right-line me-2"></i>تغيير نوع السؤال
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@stop

@section('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    @include('admin.pages.question-bank.partials.rich-text-editor')
    @stack('type-scripts')
@stop
