@extends('admin.pages.question-bank.layouts.type-form', [
    'pageTitle' => 'إنشاء سؤال اختيار من متعدد (إجابة واحدة)',
    'typeTitle' => 'سؤال اختيار من متعدد (إجابة واحدة)',
    'typeSubtitle' => 'أضف الخيارات وحدد إجابة واحدة صحيحة بالنقر على الدائرة.',
    'typeIcon' => 'ri-radio-button-line',
    'typeIconColor' => 'primary',
    'breadcrumbActive' => 'اختيار من متعدد',
    'formEnctype' => 'multipart/form-data',
])

@section('type-sidebar-settings')
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="shuffle_options"
               id="shuffle_options" {{ old('shuffle_options') ? 'checked' : '' }}>
        <label class="form-check-label" for="shuffle_options">خلط ترتيب الخيارات</label>
    </div>
@endsection

@section('type-main-content')
    <div class="card custom-card form-card qb-type-card mb-4">
        @include('admin.pages.question-bank.partials.type-form-section-title', [
            'icon' => 'ri-list-check',
            'color' => 'success',
            'title' => 'خيارات الإجابة',
            'subtitle' => 'اختر إجابة واحدة صحيحة',
            'headerActions' => '<button type="button" class="btn btn-sm btn-primary btn-wave" id="add-option-btn"><i class="ri-add-line me-1"></i>إضافة خيار</button>',
        ])
        <div class="card-body pt-2">
            @include('admin.pages.question-bank.partials.type-form-tip', [
                'text' => 'حدد الإجابة الصحيحة بالنقر على الدائرة بجانب الخيار. يجب توفير خيارين على الأقل.',
            ])
            <div id="options-container" class="d-flex flex-column gap-3"></div>
        </div>
    </div>
@endsection

@push('type-scripts')
<script>
let optionCount = 0;

$(document).ready(function() {
    for (let i = 0; i < 4; i++) {
        addOption();
    }

    $('#add-option-btn').click(function() {
        addOption();
    });

    $(document).on('click', '.remove-option-btn', function() {
        if ($('.option-item').length > 2) {
            $(this).closest('.option-item').remove();
            updateOptionNumbers();
        } else {
            alert('يجب أن يكون هناك خياران على الأقل');
        }
    });

    $(document).on('change', '.correct-radio', function() {
        $('.correct-radio').not(this).prop('checked', false);
    });
});

function addOption() {
    optionCount++;
    const letters = ['أ', 'ب', 'ج', 'د', 'هـ', 'و', 'ز', 'ح'];
    const letter = letters[optionCount - 1] || optionCount;

    const optionHtml = `
        <div class="option-item qb-option-row">
            <div class="d-flex align-items-start gap-3">
                <div class="pt-2">
                    <input class="form-check-input correct-radio" type="radio"
                           name="correct_option" value="${optionCount}"
                           id="correct_${optionCount}">
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="qb-option-row__letter">${letter}</span>
                        <input type="text" name="options[${optionCount}][option_text]"
                               class="form-control form-input-enhanced" placeholder="أدخل نص الخيار..." required>
                        <input type="hidden" name="options[${optionCount}][option_order]" value="${optionCount}">
                    </div>
                    <div class="row g-2 align-items-center">
                        <div class="col-md-8">
                            <input type="text" name="options[${optionCount}][feedback]"
                                   class="form-control form-control-sm form-input-enhanced"
                                   placeholder="ملاحظات عند اختيار هذا الخيار (اختياري)">
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-option-btn btn-wave">
                                <i class="ri-delete-bin-line"></i> حذف
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    $('#options-container').append(optionHtml);
}

function updateOptionNumbers() {
    const letters = ['أ', 'ب', 'ج', 'د', 'هـ', 'و', 'ز', 'ح'];
    $('.option-item').each(function(index) {
        $(this).find('.qb-option-row__letter').text(letters[index] || (index + 1));
    });
}
</script>
@endpush
