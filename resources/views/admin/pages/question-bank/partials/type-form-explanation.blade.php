@php
    $explanationTitle = $explanationTitle ?? 'شرح الإجابة (اختياري)';
    $explanationPlaceholder = $explanationPlaceholder ?? 'اكتب شرحاً يظهر للطالب بعد الإجابة...';
@endphp

<div class="card custom-card form-card qb-type-card mb-4">
    @include('admin.pages.question-bank.partials.type-form-section-title', [
        'icon' => 'ri-lightbulb-line',
        'color' => 'warning',
        'title' => $explanationTitle,
    ])
    <div class="card-body pt-2">
        <textarea name="explanation" class="form-control form-input-enhanced qb-rich-text" rows="3"
                  placeholder="{{ $explanationPlaceholder }}">{{ old('explanation') }}</textarea>
    </div>
</div>
