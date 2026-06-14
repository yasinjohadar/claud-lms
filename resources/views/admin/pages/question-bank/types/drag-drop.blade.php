@extends('admin.pages.question-bank.layouts.type-form', [
    'pageTitle' => 'إنشاء سؤال سحب وإفلات',
    'typeTitle' => 'سؤال سحب وإفلات',
    'typeSubtitle' => 'حدد مناطق الإفلات والعناصر الصحيحة مع مشتتات اختيارية.',
    'typeIcon' => 'ri-drag-drop-line',
    'typeIconColor' => 'purple',
    'breadcrumbActive' => 'سحب وإفلات',
    'showMedia' => false,
    'questionTextLabel' => 'تعليمات السؤال',
    'questionTextPlaceholder' => 'مثال: اسحب الكلمات إلى أماكنها الصحيحة...',
    'questionTextRows' => 3,
    'tagsPlaceholder' => 'مثال: لغة عربية، قواعد',
])

@section('type-css')
<style>
    .drop-zone {
        min-height: 60px;
        border: 2px dashed #e2e8f0;
        border-radius: 0.75rem;
        padding: 15px;
        background: #f8fafc;
        transition: all 0.3s ease;
    }
    .drop-zone:hover {
        border-color: #4f46e5;
        background: rgba(79, 70, 229, 0.05);
    }
    .drag-item { cursor: move; user-select: none; }
    .drop-target-preview {
        background: rgba(14, 165, 233, 0.1);
        border: 2px dashed #0ea5e9;
        border-radius: 0.5rem;
        padding: 8px 12px;
        margin: 4px;
        display: inline-block;
        min-width: 100px;
        text-align: center;
        color: #64748b;
        font-style: italic;
    }
</style>
@endsection

@section('type-sidebar-settings')
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="shuffle_items"
               id="shuffle_items" {{ old('shuffle_items', true) ? 'checked' : '' }}>
        <label class="form-check-label" for="shuffle_items">خلط ترتيب العناصر</label>
    </div>
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="instant_feedback" id="instant_feedback"
               {{ old('instant_feedback') ? 'checked' : '' }}>
        <label class="form-check-label" for="instant_feedback">إظهار التغذية الراجعة الفورية</label>
    </div>
@endsection

@section('type-sidebar-extra')
    <div class="card custom-card form-card qb-type-card mb-4">
        @include('admin.pages.question-bank.partials.type-form-section-title', [
            'icon' => 'ri-star-line',
            'color' => 'warning',
            'title' => 'طريقة التقييم',
        ])
        <div class="card-body pt-2">
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="scoring_method"
                       value="all_or_nothing" id="scoring_all" {{ old('scoring_method', 'all_or_nothing') == 'all_or_nothing' ? 'checked' : '' }}>
                <label class="form-check-label" for="scoring_all">
                    <strong>الكل أو لا شيء</strong>
                    <br><small class="text-muted">الدرجة الكاملة فقط إذا كانت جميع الإجابات صحيحة</small>
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="scoring_method"
                       value="partial" id="scoring_partial" {{ old('scoring_method') == 'partial' ? 'checked' : '' }}>
                <label class="form-check-label" for="scoring_partial">
                    <strong>درجات جزئية</strong>
                    <br><small class="text-muted">درجة لكل إجابة صحيحة</small>
                </label>
            </div>
        </div>
    </div>
@endsection

@section('type-main-content')
    <div class="card custom-card form-card qb-type-card mb-4">
        @include('admin.pages.question-bank.partials.type-form-section-title', [
            'icon' => 'ri-focus-3-line',
            'color' => 'danger',
            'title' => 'مناطق الإفلات (الأهداف)',
            'subtitle' => 'كل منطقة لها إجابة صحيحة واحدة',
            'headerActions' => '<button type="button" class="btn btn-sm btn-danger btn-wave" id="add-zone-btn"><i class="ri-add-line me-1"></i>إضافة منطقة</button>',
        ])
        <div class="card-body pt-2">
            @include('admin.pages.question-bank.partials.type-form-tip', [
                'text' => 'أضف المناطق التي سيقوم الطالب بإفلات العناصر فيها. كل منطقة لها إجابة صحيحة واحدة.',
            ])
            <div id="zones-container" class="d-flex flex-column gap-3">
                @for($z = 1; $z <= 3; $z++)
                    <div class="zone-item qb-pair-row">
                        <div class="row g-2 align-items-center">
                            <div class="col-auto">
                                <span class="qb-option-row__letter zone-number" style="background:rgba(239,68,68,0.1);color:#dc2626;">{{ $z }}</span>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="drop_zones[{{ $z }}][label]" class="form-control form-input-enhanced"
                                       placeholder="تسمية المنطقة (مثال: الفاعل)" required>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="drop_zones[{{ $z }}][correct_item]" class="form-control form-input-enhanced"
                                       placeholder="العنصر الصحيح لهذه المنطقة" required>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-zone-btn btn-wave">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <div class="card custom-card form-card qb-type-card mb-4">
        @include('admin.pages.question-bank.partials.type-form-section-title', [
            'icon' => 'ri-hand-coin-line',
            'color' => 'primary',
            'title' => 'عناصر إضافية (مشتتات)',
            'subtitle' => 'اختياري — لزيادة صعوبة السؤال',
            'headerActions' => '<button type="button" class="btn btn-sm btn-primary btn-wave" id="add-distractor-btn"><i class="ri-add-line me-1"></i>إضافة مشتت</button>',
        ])
        <div class="card-body pt-2">
            @include('admin.pages.question-bank.partials.type-form-tip', [
                'text' => 'أضف عناصر إضافية خاطئة لزيادة صعوبة السؤال. العناصر الصحيحة تُضاف تلقائياً من مناطق الإفلات.',
            ])
            <div id="distractors-container" class="d-flex flex-column gap-2"></div>
        </div>
    </div>

    <div class="card custom-card form-card qb-type-card mb-4">
        @include('admin.pages.question-bank.partials.type-form-section-title', [
            'icon' => 'ri-eye-line',
            'color' => 'info',
            'title' => 'معاينة السؤال',
        ])
        <div class="card-body pt-2">
            <div class="mb-3">
                <strong>العناصر القابلة للسحب:</strong>
                <div class="drop-zone mt-2" id="preview-items">
                    <span class="text-muted">ستظهر العناصر هنا...</span>
                </div>
            </div>
            <div>
                <strong>مناطق الإفلات:</strong>
                <div class="row mt-2" id="preview-zones">
                    <span class="text-muted">ستظهر المناطق هنا...</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('type-scripts')
<script>
let zoneCount = 3;
let distractorCount = 0;

$(document).ready(function() {
    $('#add-zone-btn').click(function() {
        zoneCount++;
        const zoneHtml = `
            <div class="zone-item qb-pair-row">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <span class="qb-option-row__letter zone-number" style="background:rgba(239,68,68,0.1);color:#dc2626;">${zoneCount}</span>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="drop_zones[${zoneCount}][label]" class="form-control form-input-enhanced" placeholder="تسمية المنطقة" required>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="drop_zones[${zoneCount}][correct_item]" class="form-control form-input-enhanced" placeholder="العنصر الصحيح لهذه المنطقة" required>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-zone-btn btn-wave">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#zones-container').append(zoneHtml);
        updatePreview();
    });

    $(document).on('click', '.remove-zone-btn', function() {
        if ($('.zone-item').length > 2) {
            $(this).closest('.zone-item').remove();
            updateZoneNumbers();
            updatePreview();
        } else {
            alert('يجب أن يكون هناك منطقتان على الأقل');
        }
    });

    $('#add-distractor-btn').click(function() {
        distractorCount++;
        const distractorHtml = `
            <div class="distractor-item qb-option-row py-2">
                <div class="row g-2 align-items-center">
                    <div class="col-auto"><i class="ri-close-circle-line text-secondary"></i></div>
                    <div class="col">
                        <input type="text" name="distractors[]" class="form-control form-control-sm form-input-enhanced"
                               placeholder="عنصر مشتت (إجابة خاطئة)" required>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-distractor-btn btn-wave">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#distractors-container').append(distractorHtml);
        updatePreview();
    });

    $(document).on('click', '.remove-distractor-btn', function() {
        $(this).closest('.distractor-item').remove();
        updatePreview();
    });

    $(document).on('input', 'input[name^="drop_zones"], input[name="distractors[]"]', function() {
        updatePreview();
    });

    updatePreview();
});

function updateZoneNumbers() {
    $('.zone-item').each(function(index) {
        $(this).find('.zone-number').text(index + 1);
    });
}

function updatePreview() {
    let items = [];
    $('input[name$="[correct_item]"]').each(function() {
        const val = $(this).val().trim();
        if (val) items.push(`<span class="badge bg-primary me-2 mb-2 p-2 drag-item">${val}</span>`);
    });
    $('input[name="distractors[]"]').each(function() {
        const val = $(this).val().trim();
        if (val) items.push(`<span class="badge bg-secondary me-2 mb-2 p-2 drag-item">${val}</span>`);
    });

    $('#preview-items').html(items.length > 0 ? items.join('') : '<span class="text-muted">ستظهر العناصر هنا...</span>');

    let zones = [];
    $('input[name$="[label]"]').each(function() {
        const val = $(this).val().trim();
        if (val) {
            zones.push(`
                <div class="col-md-4 mb-2">
                    <div class="drop-zone text-center">
                        <strong class="d-block mb-2">${val}</strong>
                        <div class="drop-target-preview">اسحب هنا</div>
                    </div>
                </div>
            `);
        }
    });

    $('#preview-zones').html(zones.length > 0 ? zones.join('') : '<span class="text-muted">ستظهر المناطق هنا...</span>');
}
</script>
@endpush
