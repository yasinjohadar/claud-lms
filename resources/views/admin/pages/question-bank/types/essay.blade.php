@extends('admin.pages.question-bank.layouts.type-form', [
    'pageTitle' => 'إنشاء سؤال مقالي',
    'typeTitle' => 'سؤال مقالي',
    'typeSubtitle' => 'سؤال يتطلب إجابة مطولة وتصحيحاً يدوياً من المدرس.',
    'typeIcon' => 'ri-file-text-line',
    'typeIconColor' => 'purple',
    'breadcrumbActive' => 'سؤال مقالي',
    'showMedia' => false,
    'gradeLabel' => 'الدرجة القصوى',
    'defaultGrade' => 10,
    'gradeMin' => 1,
    'tagsPlaceholder' => 'مثال: كتابة، تحليل',
    'questionTextPlaceholder' => 'اكتب السؤال المقالي هنا...',
    'questionTextRows' => 5,
    'showExplanation' => false,
])

@section('type-main-content')
    <div class="card custom-card form-card qb-type-card mb-4">
        @include('admin.pages.question-bank.partials.type-form-section-title', [
            'icon' => 'ri-edit-line',
            'color' => 'info',
            'title' => 'إعدادات الإجابة',
            'subtitle' => 'حدود الكلمات والمرفقات',
        ])
        <div class="card-body pt-2">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">الحد الأدنى للكلمات</label>
                    <input type="number" name="min_words" class="form-control form-input-enhanced"
                           value="{{ old('min_words', 50) }}" min="0" placeholder="0 = بدون حد">
                </div>
                <div class="col-md-6">
                    <label class="form-label">الحد الأقصى للكلمات</label>
                    <input type="number" name="max_words" class="form-control form-input-enhanced"
                           value="{{ old('max_words', 500) }}" min="0" placeholder="0 = بدون حد">
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="allow_attachments"
                               id="allow_attachments" {{ old('allow_attachments') ? 'checked' : '' }}>
                        <label class="form-check-label" for="allow_attachments">السماح بإرفاق ملفات مع الإجابة</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card custom-card form-card qb-type-card mb-4">
        @include('admin.pages.question-bank.partials.type-form-section-title', [
            'icon' => 'ri-clipboard-line',
            'color' => 'success',
            'title' => 'دليل التصحيح',
            'subtitle' => 'للمدرس أثناء التصحيح اليدوي',
        ])
        <div class="card-body pt-2">
            @include('admin.pages.question-bank.partials.type-form-tip', [
                'text' => 'هذا السؤال يتطلب تصحيح يدوي من المدرس. أضف إجابة نموذجية ومعايير تقييم واضحة.',
            ])
            <div class="mb-3">
                <label class="form-label">الإجابة النموذجية (للمدرس)</label>
                <textarea name="model_answer" class="form-control form-input-enhanced" rows="4"
                          placeholder="اكتب الإجابة النموذجية التي يستعين بها المدرس أثناء التصحيح...">{{ old('model_answer') }}</textarea>
            </div>
            <div>
                <label class="form-label">معايير التقييم</label>
                <textarea name="grading_criteria" class="form-control form-input-enhanced" rows="3"
                          placeholder="مثال: المحتوى 40%، التنظيم 30%، اللغة 30%">{{ old('grading_criteria') }}</textarea>
            </div>
        </div>
    </div>

    @include('admin.pages.question-bank.partials.type-form-explanation', [
        'explanationTitle' => 'ملاحظات للطالب (اختياري)',
        'explanationPlaceholder' => 'نصائح أو إرشادات تظهر للطالب قبل الإجابة...',
    ])
@endsection
