@extends('admin.pages.question-bank.layouts.type-form', [
    'pageTitle' => 'إنشاء سؤال مطابقة',
    'typeTitle' => 'سؤال مطابقة',
    'typeSubtitle' => 'اربط كل سؤال بالإجابة الصحيحة المقابلة له.',
    'typeIcon' => 'ri-links-line',
    'typeIconColor' => 'cyan',
    'breadcrumbActive' => 'مطابقة',
    'showMedia' => false,
])

@section('type-sidebar-settings')
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="shuffle_options"
               id="shuffle_options" {{ old('shuffle_options', true) ? 'checked' : '' }}>
        <label class="form-check-label" for="shuffle_options">خلط ترتيب الخيارات</label>
    </div>
@endsection

@section('type-main-content')
    <div class="card custom-card form-card qb-type-card mb-4">
        @include('admin.pages.question-bank.partials.type-form-section-title', [
            'icon' => 'ri-link',
            'color' => 'cyan',
            'title' => 'أزواج المطابقة',
            'subtitle' => 'العمود الأيمن: السؤال — العمود الأيسر: الإجابة',
            'headerActions' => '<button type="button" class="btn btn-sm btn-primary btn-wave" id="add-pair-btn"><i class="ri-add-line me-1"></i>إضافة زوج</button>',
        ])
        <div class="card-body pt-2">
            @include('admin.pages.question-bank.partials.type-form-tip', [
                'text' => 'أضف العناصر المطلوب مطابقتها. العمود الأيمن يحتوي على الأسئلة والعمود الأيسر يحتوي على الإجابات.',
            ])

            <div class="row mb-3 d-none d-md-flex">
                <div class="col-5"><strong class="text-primary"><i class="ri-question-line me-1"></i>السؤال</strong></div>
                <div class="col-2 text-center"><i class="ri-arrow-left-right-line text-muted"></i></div>
                <div class="col-5"><strong class="text-success"><i class="ri-check-line me-1"></i>الإجابة</strong></div>
            </div>

            <div id="pairs-container" class="d-flex flex-column gap-3">
                @for($i = 1; $i <= 4; $i++)
                    <div class="pair-item qb-pair-row">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-5">
                                <input type="text" name="matching_pairs[{{ $i }}][question]" class="form-control form-input-enhanced"
                                       placeholder="السؤال..." required>
                            </div>
                            <div class="col-md-2 text-center d-none d-md-block">
                                <i class="ri-arrow-left-right-line text-primary"></i>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="matching_pairs[{{ $i }}][answer]" class="form-control form-input-enhanced"
                                       placeholder="الإجابة..." required>
                            </div>
                            <div class="col-md-1 col-auto">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-pair-btn btn-wave">
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
let pairCount = 4;

$(document).ready(function() {
    $('#add-pair-btn').click(function() {
        pairCount++;
        const pairHtml = `
            <div class="pair-item qb-pair-row">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <input type="text" name="matching_pairs[${pairCount}][question]" class="form-control form-input-enhanced" placeholder="السؤال..." required>
                    </div>
                    <div class="col-md-2 text-center d-none d-md-block">
                        <i class="ri-arrow-left-right-line text-primary"></i>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="matching_pairs[${pairCount}][answer]" class="form-control form-input-enhanced" placeholder="الإجابة..." required>
                    </div>
                    <div class="col-md-1 col-auto">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-pair-btn btn-wave">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#pairs-container').append(pairHtml);
    });

    $(document).on('click', '.remove-pair-btn', function() {
        if ($('.pair-item').length > 2) {
            $(this).closest('.pair-item').remove();
        } else {
            alert('يجب أن يكون هناك زوجان على الأقل');
        }
    });
});
</script>
@endpush
