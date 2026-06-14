@extends('admin.layouts.master')

@section('page-title')
    اختر نوع السؤال
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'بنك الأسئلة', 'url' => route('question-bank.index')],
                ['label' => 'اختر نوع السؤال'],
            ],
            'title' => 'إنشاء سؤال جديد',
            'subtitle' => 'اختر نوع السؤال المناسب — كل نوع له نموذج إدخال مخصص مع محرر نصوص غني للسؤال والشرح.',
            'actions' => '<a href="' . route('question-bank.index') . '" class="btn btn-light border btn-sm btn-wave"><i class="ri-arrow-right-line me-1"></i>العودة لبنك الأسئلة</a>',
        ])

        @php
            $autoGradingCount = $questionTypes->where('requires_manual_grading', false)->count();
            $manualGradingCount = $questionTypes->where('requires_manual_grading', true)->count();

            $typeMeta = [
                'multiple_choice_single' => [
                    'icon' => 'ri-radio-button-line',
                    'icon_color' => 'primary',
                    'description' => 'اختيار إجابة واحدة صحيحة من عدة خيارات',
                ],
                'multiple_choice_multiple' => [
                    'icon' => 'ri-checkbox-multiple-line',
                    'icon_color' => 'success',
                    'description' => 'اختيار أكثر من إجابة صحيحة',
                ],
                'true_false' => [
                    'icon' => 'ri-toggle-line',
                    'icon_color' => 'warning',
                    'description' => 'تحديد إذا كانت العبارة صحيحة أم خاطئة',
                ],
                'short_answer' => [
                    'icon' => 'ri-edit-2-line',
                    'icon_color' => 'danger',
                    'description' => 'إجابة نصية قصيرة',
                ],
                'essay' => [
                    'icon' => 'ri-file-text-line',
                    'icon_color' => 'purple',
                    'description' => 'إجابة مقالية طويلة',
                ],
                'matching' => [
                    'icon' => 'ri-links-line',
                    'icon_color' => 'cyan',
                    'description' => 'مطابقة العناصر ببعضها',
                ],
                'ordering' => [
                    'icon' => 'ri-sort-desc',
                    'icon_color' => 'secondary',
                    'description' => 'ترتيب العناصر بالتسلسل الصحيح',
                ],
                'fill_blank' => [
                    'icon' => 'ri-input-method-line',
                    'icon_color' => 'info',
                    'description' => 'ملء الفراغات في النص',
                ],
                'fill_blanks' => [
                    'icon' => 'ri-input-method-line',
                    'icon_color' => 'info',
                    'description' => 'ملء الفراغات في النص',
                ],
                'numerical' => [
                    'icon' => 'ri-calculator-line',
                    'icon_color' => 'success',
                    'description' => 'إجابة رقمية مع هامش خطأ',
                ],
                'calculated' => [
                    'icon' => 'ri-functions',
                    'icon_color' => 'orange',
                    'description' => 'سؤال محسوب بمعادلات',
                ],
                'drag_drop' => [
                    'icon' => 'ri-drag-drop-line',
                    'icon_color' => 'info',
                    'description' => 'سحب العناصر وإفلاتها في أماكنها الصحيحة',
                ],
            ];
        @endphp

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple',
                'icon' => 'ri-questionnaire-line',
                'label' => 'أنواع الأسئلة',
                'value' => number_format($questionTypes->count()),
                'hint' => 'أنواع نشطة متاحة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green',
                'icon' => 'ri-robot-2-line',
                'label' => 'تصحيح تلقائي',
                'value' => number_format($autoGradingCount),
                'hint' => 'يُصحَّح آلياً بعد الإجابة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange',
                'icon' => 'ri-user-star-line',
                'label' => 'تصحيح يدوي',
                'value' => number_format($manualGradingCount),
                'hint' => 'يتطلب مراجعة المدرّس',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan',
                'icon' => 'ri-book-open-line',
                'label' => 'الكورسات المنشورة',
                'value' => number_format($courses->count()),
                'hint' => 'جاهزة لربط الأسئلة',
            ])
        </div>

        <div class="shortcut-section">
            <div class="shortcut-section__header mb-3">
                <h5 class="shortcut-section__title mb-1">
                    <i class="ri-flashlight-line text-warning"></i>
                    اختر نوع السؤال
                </h5>
                <p class="shortcut-section__subtitle mb-0">
                    انقر على البطاقة للانتقال إلى نموذج الإنشاء المخصص لذلك النوع
                </p>
            </div>

            <div class="row g-3 shortcut-grid">
                @foreach($questionTypes as $type)
                    @php
                        $meta = $typeMeta[$type->name] ?? [
                            'icon' => 'ri-question-line',
                            'icon_color' => 'primary',
                            'description' => $type->description ?? 'نوع سؤال مخصص',
                        ];
                        $gradingBadge = $type->requires_manual_grading ? 'يدوي' : 'تلقائي';
                    @endphp
                    @include('admin.partials.ui.shortcut-card', [
                        'url' => route('question-bank.create.type', $type->name),
                        'title' => $type->display_name,
                        'description' => $meta['description'],
                        'icon' => $meta['icon'],
                        'icon_color' => $meta['icon_color'],
                        'badge' => $gradingBadge,
                        'col' => 'col-xl-2 col-lg-3 col-md-4 col-sm-6',
                    ])
                @endforeach
            </div>
        </div>

    </div>
</div>
@stop
