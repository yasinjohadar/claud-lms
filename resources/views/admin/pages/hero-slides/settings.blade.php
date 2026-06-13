@extends('admin.layouts.master')

@section('page-title')
    إعدادات السلايدر
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')
        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'سلايدر الرئيسية', 'url' => route('admin.hero-slides.index')],
                ['label' => 'الإعدادات'],
            ],
            'title' => 'إعدادات Swiper العامة',
            'actions' => '<a href="' . route('admin.hero-slides.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <form method="POST" action="{{ route('admin.hero-slider.settings.update') }}">
            @csrf
            @method('PUT')
            <div class="card custom-card"><div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">مدة العرض (ms)</label>
                    <input type="number" name="autoplay_delay_ms" class="form-control" value="{{ old('autoplay_delay_ms', $settings->autoplay_delay_ms) }}" min="1000" max="60000" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">سرعة الانتقال (ms)</label>
                    <input type="number" name="transition_speed_ms" class="form-control" value="{{ old('transition_speed_ms', $settings->transition_speed_ms) }}" min="100" max="5000" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">التأثير</label>
                    <select name="effect" class="form-select">
                        <option value="fade" @selected($settings->effect === 'fade')>fade</option>
                        <option value="slide" @selected($settings->effect === 'slide')>slide</option>
                    </select>
                </div>
                <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="autoplay_enabled" value="1" @checked($settings->autoplay_enabled)><label>تشغيل تلقائي</label></div></div>
                <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="loop" value="1" @checked($settings->loop)><label>loop</label></div></div>
                <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="pause_on_hover" value="1" @checked($settings->pause_on_hover)><label>إيقاف عند المرور</label></div></div>
                <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="show_navigation" value="1" @checked($settings->show_navigation)><label>الأسهم</label></div></div>
                <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="show_pagination" value="1" @checked($settings->show_pagination)><label>النقاط</label></div></div>
                <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="show_progress_bar" value="1" @checked($settings->show_progress_bar)><label>شريط التقدم</label></div></div>
            </div></div>
            <button type="submit" class="btn btn-primary mt-3"><i class="ri-save-line me-1"></i> حفظ الإعدادات</button>
        </form>
    </div>
</div>
@endsection
