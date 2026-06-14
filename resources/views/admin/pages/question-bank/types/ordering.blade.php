@extends('admin.pages.question-bank.layouts.type-form', [
    'pageTitle' => 'إنشاء سؤال ترتيب',
    'typeTitle' => 'سؤال الترتيب',
    'typeSubtitle' => 'أدخل العناصر بالترتيب الصحيح — سيتم خلطها عند العرض للطالب.',
    'typeIcon' => 'ri-sort-desc',
    'typeIconColor' => 'secondary',
    'breadcrumbActive' => 'ترتيب',
    'showMedia' => false,
    'showReusable' => false,
    'difficultyFieldName' => 'difficulty_level',
    'showExpertDifficulty' => true,
    'questionTextLabel' => 'تعليمات السؤال',
    'questionTextPlaceholder' => 'مثال: رتب الأحداث التالية من الأقدم إلى الأحدث...',
    'questionTextRows' => 3,
    'tagsPlaceholder' => 'مثال: تاريخ، أحداث',
])

@section('type-main-content')
    <div class="card custom-card form-card qb-type-card mb-4">
        @include('admin.pages.question-bank.partials.type-form-section-title', [
            'icon' => 'ri-sort-number-asc',
            'color' => 'secondary',
            'title' => 'العناصر المطلوب ترتيبها',
            'subtitle' => 'أدخلها بالترتيب الصحيح',
            'headerActions' => '<button type="button" class="btn btn-sm btn-primary btn-wave" id="add-item-btn"><i class="ri-add-line me-1"></i>إضافة عنصر</button>',
        ])
        <div class="card-body pt-2">
            @include('admin.pages.question-bank.partials.type-form-tip', [
                'text' => 'أدخل العناصر بالترتيب الصحيح. سيتم خلطها تلقائياً عند عرضها للطالب.',
            ])
            <div id="items-container" class="d-flex flex-column gap-3">
                @for($i = 1; $i <= 4; $i++)
                    <div class="order-item qb-option-row">
                        <div class="row g-2 align-items-center">
                            <div class="col-auto">
                                <span class="qb-option-row__letter order-number">{{ $i }}</span>
                            </div>
                            <div class="col">
                                <input type="text" name="order_items[]" class="form-control form-input-enhanced"
                                       placeholder="العنصر {{ $i }}..." required>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn btn-wave">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection

@push('type-scripts')
<script>
$(document).ready(function() {
    $('#add-item-btn').click(function() {
        const itemCount = $('.order-item').length + 1;
        const itemHtml = `
            <div class="order-item qb-option-row">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <span class="qb-option-row__letter order-number">${itemCount}</span>
                    </div>
                    <div class="col">
                        <input type="text" name="order_items[]" class="form-control form-input-enhanced" placeholder="العنصر ${itemCount}..." required>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn btn-wave">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#items-container').append(itemHtml);
    });

    $(document).on('click', '.remove-item-btn', function() {
        if ($('.order-item').length > 2) {
            $(this).closest('.order-item').remove();
            updateItemNumbers();
        } else {
            alert('يجب أن يكون هناك عنصران على الأقل');
        }
    });

    function updateItemNumbers() {
        $('.order-item').each(function(index) {
            $(this).find('.order-number').text(index + 1);
        });
    }
});
</script>
@endpush
