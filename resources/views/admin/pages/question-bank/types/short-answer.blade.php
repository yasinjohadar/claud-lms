@extends('admin.pages.question-bank.layouts.type-form', [
    'pageTitle' => 'إنشاء سؤال إجابة قصيرة',
    'typeTitle' => 'سؤال إجابة قصيرة',
    'typeSubtitle' => 'أضف جميع الصيغ المقبولة للإجابة الصحيحة.',
    'typeIcon' => 'ri-edit-2-line',
    'typeIconColor' => 'danger',
    'breadcrumbActive' => 'إجابة قصيرة',
    'showMedia' => false,
])

@section('type-main-content')
    <div class="card custom-card form-card qb-type-card mb-4">
        @include('admin.pages.question-bank.partials.type-form-section-title', [
            'icon' => 'ri-check-double-line',
            'color' => 'success',
            'title' => 'الإجابات المقبولة',
            'subtitle' => 'يمكن إضافة عدة صيغ للإجابة الصحيحة',
            'headerActions' => '<button type="button" class="btn btn-sm btn-primary btn-wave" id="add-answer-btn"><i class="ri-add-line me-1"></i>إضافة إجابة</button>',
        ])
        <div class="card-body pt-2">
            @include('admin.pages.question-bank.partials.type-form-tip', [
                'text' => 'أضف جميع الإجابات المقبولة. يمكن إضافة عدة صيغ للإجابة الصحيحة.',
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
                        <input type="text" name="correct_answers[]" class="form-control form-input-enhanced"
                               placeholder="إجابة بديلة صحيحة..." required>
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
