@extends('admin.pages.question-bank.layouts.type-form', [
    'pageTitle' => 'إنشاء سؤال ملء الفراغات',
    'typeTitle' => 'سؤال ملء الفراغات',
    'typeSubtitle' => 'استخدم [[blank]] أو ___ لتحديد مكان الفراغ في النص.',
    'typeIcon' => 'ri-input-method-line',
    'typeIconColor' => 'info',
    'breadcrumbActive' => 'ملء الفراغات',
    'showMedia' => false,
    'showQuestionText' => false,
])

@section('type-main-content')
    <div class="card custom-card form-card qb-type-card mb-4">
        @include('admin.pages.question-bank.partials.type-form-section-title', [
            'icon' => 'ri-text',
            'color' => 'info',
            'title' => 'نص السؤال مع الفراغات',
            'subtitle' => 'استخدم [[blank]] أو ___ لتحديد الفراغ',
        ])
        <div class="card-body pt-2">
            @include('admin.pages.question-bank.partials.type-form-tip', [
                'text' => 'استخدم [[blank]] أو ___ لتحديد مكان الفراغ في النص. مثال: عاصمة المملكة العربية السعودية هي [[blank]]',
            ])
            <div>
                <label class="form-label">نص السؤال <span class="text-danger">*</span></label>
                <textarea name="question_text" class="form-control form-input-enhanced @error('question_text') is-invalid @enderror qb-rich-text"
                          rows="4" placeholder="اكتب النص مع وضع [[blank]] في مكان الفراغ..." required>{{ old('question_text') }}</textarea>
                @error('question_text')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="card custom-card form-card qb-type-card mb-4">
        @include('admin.pages.question-bank.partials.type-form-section-title', [
            'icon' => 'ri-check-double-line',
            'color' => 'success',
            'title' => 'الإجابات الصحيحة',
            'subtitle' => 'يمكن إضافة عدة إجابات مقبولة',
            'headerActions' => '<button type="button" class="btn btn-sm btn-primary btn-wave" id="add-answer-btn"><i class="ri-add-line me-1"></i>إضافة إجابة بديلة</button>',
        ])
        <div class="card-body pt-2">
            @include('admin.pages.question-bank.partials.type-form-tip', [
                'text' => 'يمكنك إضافة عدة إجابات صحيحة مقبولة للفراغ الواحد.',
            ])
            <div id="answers-container" class="d-flex flex-column gap-3">
                <div class="answer-item qb-option-row">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <input type="text" name="correct_answers[]" class="form-control form-input-enhanced"
                                   placeholder="الإجابة الصحيحة..." required>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-answer-btn btn-wave">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" name="case_sensitive" id="case_sensitive"
                       {{ old('case_sensitive') ? 'checked' : '' }}>
                <label class="form-check-label" for="case_sensitive">مطابقة حالة الأحرف (Case Sensitive)</label>
            </div>
        </div>
    </div>
@endsection

@push('type-scripts')
<script>
$(document).ready(function() {
    $('#add-answer-btn').click(function() {
        const answerHtml = `
            <div class="answer-item qb-option-row">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <input type="text" name="correct_answers[]" class="form-control form-input-enhanced" placeholder="إجابة بديلة صحيحة..." required>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-answer-btn btn-wave">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#answers-container').append(answerHtml);
    });

    $(document).on('click', '.remove-answer-btn', function() {
        if ($('.answer-item').length > 1) {
            $(this).closest('.answer-item').remove();
        } else {
            alert('يجب أن يكون هناك إجابة واحدة على الأقل');
        }
    });
});
</script>
@endpush
