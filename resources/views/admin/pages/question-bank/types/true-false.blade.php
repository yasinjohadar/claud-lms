@extends('admin.pages.question-bank.layouts.type-form', [
    'pageTitle' => 'إنشاء سؤال صح وخطأ',
    'typeTitle' => 'سؤال صح وخطأ',
    'typeSubtitle' => 'اكتب العبارة وحدد ما إذا كانت صحيحة أم خاطئة.',
    'typeIcon' => 'ri-toggle-line',
    'typeIconColor' => 'warning',
    'breadcrumbActive' => 'صح وخطأ',
    'showMedia' => false,
    'questionTextLabel' => 'العبارة',
    'questionTextPlaceholder' => 'اكتب العبارة التي سيحدد الطالب إذا كانت صحيحة أم خاطئة...',
])

@section('type-main-content')
    <div class="card custom-card form-card qb-type-card mb-4">
        @include('admin.pages.question-bank.partials.type-form-section-title', [
            'icon' => 'ri-checkbox-circle-line',
            'color' => 'success',
            'title' => 'الإجابة الصحيحة',
            'subtitle' => 'انقر على البطاقة لتحديد الإجابة',
        ])
        <div class="card-body pt-2">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 qb-tf-choice {{ old('correct_answer', 'true') == 'true' ? 'is-selected-true' : '' }}"
                         id="true-card">
                        <div class="card-body text-center py-4">
                            <input type="radio" name="correct_answer" value="true" class="d-none"
                                   id="answer-true" {{ old('correct_answer', 'true') == 'true' ? 'checked' : '' }}>
                            <i class="ri-checkbox-circle-fill text-success" style="font-size: 3rem;"></i>
                            <h4 class="mt-3 mb-0">صح</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 qb-tf-choice {{ old('correct_answer') == 'false' ? 'is-selected-false' : '' }}"
                         id="false-card">
                        <div class="card-body text-center py-4">
                            <input type="radio" name="correct_answer" value="false" class="d-none"
                                   id="answer-false" {{ old('correct_answer') == 'false' ? 'checked' : '' }}>
                            <i class="ri-close-circle-fill text-danger" style="font-size: 3rem;"></i>
                            <h4 class="mt-3 mb-0">خطأ</h4>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="options[1][option_text]" value="صح">
            <input type="hidden" name="options[1][option_order]" value="1">
            <input type="hidden" name="options[2][option_text]" value="خطأ">
            <input type="hidden" name="options[2][option_order]" value="2">
        </div>
    </div>
@endsection

@push('type-scripts')
<script>
$(document).ready(function() {
    $('#true-card').click(function() {
        $('#answer-true').prop('checked', true);
        $(this).addClass('is-selected-true').removeClass('is-selected-false');
        $('#false-card').removeClass('is-selected-false is-selected-true');
    });

    $('#false-card').click(function() {
        $('#answer-false').prop('checked', true);
        $(this).addClass('is-selected-false').removeClass('is-selected-true');
        $('#true-card').removeClass('is-selected-true is-selected-false');
    });
});
</script>
@endpush
