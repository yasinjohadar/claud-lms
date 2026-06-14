@extends('admin.pages.question-bank.layouts.type-form', [
    'pageTitle' => 'إنشاء سؤال رقمي',
    'typeTitle' => 'سؤال رقمي',
    'typeSubtitle' => 'حدد الإجابة الرقمية الصحيحة مع هامش خطأ اختياري.',
    'typeIcon' => 'ri-calculator-line',
    'typeIconColor' => 'success',
    'breadcrumbActive' => 'رقمي',
    'showMedia' => false,
    'questionTextPlaceholder' => 'مثال: ما هو ناتج 15 × 8 ؟',
    'tagsPlaceholder' => 'مثال: رياضيات، حساب',
])

@section('type-main-content')
    <div class="card custom-card form-card qb-type-card mb-4">
        @include('admin.pages.question-bank.partials.type-form-section-title', [
            'icon' => 'ri-calculator-line',
            'color' => 'success',
            'title' => 'الإجابة الرقمية',
            'subtitle' => 'القيمة الصحيحة وهامش الخطأ المسموح',
        ])
        <div class="card-body pt-2">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">الإجابة الصحيحة <span class="text-danger">*</span></label>
                    <input type="number" name="correct_answer" class="form-control form-input-enhanced"
                           step="any" placeholder="مثال: 120" required value="{{ old('correct_answer') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">هامش الخطأ المسموح</label>
                    <input type="number" name="tolerance" class="form-control form-input-enhanced"
                           step="any" min="0" placeholder="مثال: 0.5" value="{{ old('tolerance', 0) }}">
                    <small class="text-muted">اترك 0 للمطابقة التامة</small>
                </div>
                <div class="col-12">
                    @include('admin.pages.question-bank.partials.type-form-tip', [
                        'text' => 'مثال: إذا كانت الإجابة 100 وهامش الخطأ 5، فإن الإجابات من 95 إلى 105 ستكون مقبولة.',
                    ])
                </div>
            </div>
        </div>
    </div>

    <div class="card custom-card form-card qb-type-card mb-4">
        @include('admin.pages.question-bank.partials.type-form-section-title', [
            'icon' => 'ri-ruler-line',
            'color' => 'info',
            'title' => 'الوحدة (اختياري)',
        ])
        <div class="card-body pt-2">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">وحدة القياس</label>
                    <input type="text" name="unit" class="form-control form-input-enhanced"
                           placeholder="مثال: متر، كجم، ثانية" value="{{ old('unit') }}">
                </div>
                <div class="col-md-6">
                    <div class="form-check mt-md-4">
                        <input class="form-check-input" type="checkbox" name="unit_required" id="unit_required"
                               {{ old('unit_required') ? 'checked' : '' }}>
                        <label class="form-check-label" for="unit_required">الوحدة مطلوبة في الإجابة</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
