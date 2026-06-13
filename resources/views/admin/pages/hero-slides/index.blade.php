@extends('admin.layouts.master')

@section('page-title')
    سلايدر الرئيسية
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'سلايدر الرئيسية'],
            ],
            'title' => 'شرائح السلايدر',
            'subtitle' => 'إدارة محتوى السلايدر في الصفحة الرئيسية',
            'actions' => '
                <a href="' . route('admin.hero-slider.settings') . '" class="btn btn-light border btn-sm me-2"><i class="ri-settings-3-line me-1"></i> إعدادات Swiper</a>
                <a href="' . route('admin.hero-slides.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-line me-1"></i> شريحة جديدة</a>
            ',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', ['variant' => 'purple', 'icon' => 'ri-slideshow-line', 'label' => 'إجمالي الشرائح', 'value' => $stats['total']])
            @include('admin.partials.ui.stat-card-gradient', ['variant' => 'green', 'icon' => 'ri-checkbox-circle-line', 'label' => 'نشطة', 'value' => $stats['active']])
            @include('admin.partials.ui.stat-card-gradient', ['variant' => 'cyan', 'icon' => 'ri-calendar-line', 'label' => 'مجدولة', 'value' => $stats['scheduled']])
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header"><span class="fw-bold">الشرائح — اسحب لإعادة الترتيب</span></div>
            <div class="card-body p-0">
                <div id="heroSlidesSortable" class="list-group list-group-flush" data-reorder-url="{{ route('admin.hero-slides.reorder') }}">
                    @forelse($slides as $slide)
                        @include('admin.pages.hero-slides.partials.list-item', ['slide' => $slide])
                    @empty
                        <div class="p-5 text-center text-muted">لا توجد شرائح. <a href="{{ route('admin.hero-slides.create') }}">أنشئ الأولى</a></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script src="{{ asset('assets/js/admin-hero-slides.js') }}"></script>
@endpush
