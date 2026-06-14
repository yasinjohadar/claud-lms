@extends('admin.layouts.master')

@section('page-title')
    تعديل مجموعة الأسئلة
@stop

@section('styles')
    @include('admin.pages.assignments.partials.page-styles')
    @include('admin.pages.question-pools.partials.page-styles')
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            @include('admin.partials.ui.alerts')

            <div class="my-4 page-header-breadcrumb exam-page-animate dashboard-fade-in">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('question-pools.index') }}">مجموعات الأسئلة</a></li>
                        <li class="breadcrumb-item active">تعديل المجموعة</li>
                    </ol>
                </nav>
            </div>

            <div class="group-show-hero dashboard-fade-in exam-page-animate mb-4">
                <div class="row align-items-start g-3">
                    <div class="col-lg-8">
                        <span class="group-show-hero__eyebrow">
                            <i class="fe fe-edit-2 me-1"></i>
                            تعديل مجموعة
                        </span>
                        <h2 class="group-show-hero__title mb-2">{{ $pool->name }}</h2>
                        <p class="group-show-hero__desc mb-0">
                            عدّل معلومات المجموعة، أزل أسئلة حالية، أو أضف أسئلة جديدة من بنك الأسئلة.
                        </p>
                    </div>
                    <div class="col-lg-4">
                        <div class="group-show-actions">
                            <a href="{{ route('question-pools.show', $pool->id) }}" class="group-show-action group-show-action--info">
                                <span class="group-show-action__icon"><i class="fe fe-eye"></i></span>
                                <span class="group-show-action__text">عرض المجموعة</span>
                            </a>
                            <a href="{{ route('question-pools.index') }}" class="group-show-action">
                                <span class="group-show-action__icon"><i class="fe fe-arrow-right"></i></span>
                                <span class="group-show-action__text">العودة للقائمة</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('question-pools.update', $pool->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card custom-card group-show-members-card dashboard-fade-in exam-page-animate mb-4">
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title mb-1 d-flex align-items-center gap-2">
                            <span class="assignments-section-icon"><i class="fe fe-info"></i></span>
                            المعلومات الأساسية
                        </h4>
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">اسم المجموعة <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $pool->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الكورس <span class="text-danger">*</span></label>
                                <select name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                                    <option value="">اختر الكورس</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id', $pool->course_id) == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">الوصف</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $pool->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                @if($pool->questions->count() > 0)
                <div class="card custom-card group-show-members-card dashboard-fade-in exam-page-animate mb-4">
                    <div class="card-header border-0 pb-0 d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0 d-flex align-items-center gap-2">
                            <span class="assignments-section-icon"><i class="fe fe-check-circle"></i></span>
                            الأسئلة الحالية
                            <span class="group-show-members-card__count">{{ $pool->questions->count() }}</span>
                        </h4>
                        <small class="text-muted">حدّد الأسئلة لإزالتها من المجموعة</small>
                    </div>
                    <div class="card-body pt-3">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 group-show-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:48px;"><input type="checkbox" id="select-all-current" class="form-check-input"></th>
                                        <th>نص السؤال</th>
                                        <th>النوع</th>
                                        <th class="text-center">الصعوبة</th>
                                        <th class="text-center">الدرجة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pool->questions as $question)
                                        @php $difficulty = $question->difficulty_level ?? 'medium'; @endphp
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="remove_question_ids[]" value="{{ $question->id }}"
                                                       class="form-check-input remove-question-checkbox">
                                            </td>
                                            <td>{{ Str::limit(strip_tags($question->question_text ?? ''), 90) }}</td>
                                            <td><span class="badge bg-info-transparent text-info rounded-pill">{{ $question->questionType->display_name ?? '—' }}</span></td>
                                            <td class="text-center">
                                                @if($difficulty === 'easy')<span class="badge bg-success-transparent text-success rounded-pill">سهل</span>
                                                @elseif($difficulty === 'medium')<span class="badge bg-warning-transparent text-warning rounded-pill">متوسط</span>
                                                @else<span class="badge bg-danger-transparent text-danger rounded-pill">صعب</span>@endif
                                            </td>
                                            <td class="text-center"><span class="badge bg-primary-transparent text-primary rounded-pill">{{ $question->default_grade ?? 1 }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex align-items-center gap-2">
                            <span class="qp-picker-summary"><i class="fe fe-trash-2"></i> للإزالة: <strong id="remove-count">0</strong></span>
                        </div>
                    </div>
                </div>
                @endif

                <div class="card custom-card group-show-members-card dashboard-fade-in exam-page-animate mb-4">
                    <div class="card-header border-0 pb-0 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h4 class="card-title mb-1 d-flex align-items-center gap-2">
                                <span class="assignments-section-icon"><i class="fe fe-plus-circle"></i></span>
                                إضافة أسئلة جديدة
                            </h4>
                        </div>
                        <span class="qp-picker-summary"><i class="fe fe-plus"></i> للإضافة: <strong id="add-count">0</strong></span>
                    </div>
                    <div class="card-body pt-3">
                        <div class="qp-picker-toolbar mb-4">
                            <div class="row g-3 align-items-end group-show-filters">
                                <div class="col-xl-3 col-md-6">
                                    <label class="form-label">نوع السؤال</label>
                                    <select id="filter-type" class="form-select">
                                        <option value="">جميع الأنواع</option>
                                        @foreach($questionTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->display_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <label class="form-label">الصعوبة</label>
                                    <select id="filter-difficulty" class="form-select">
                                        <option value="">جميع المستويات</option>
                                        <option value="easy">سهل</option>
                                        <option value="medium">متوسط</option>
                                        <option value="hard">صعب</option>
                                    </select>
                                </div>
                                <div class="col-xl-4 col-md-8">
                                    <label class="form-label">البحث</label>
                                    <input type="text" id="filter-search" class="form-control" placeholder="ابحث في نص السؤال...">
                                </div>
                                <div class="col-xl-2">
                                    <button type="button" class="btn btn-primary btn-sm w-100" id="apply-filters"><i class="fe fe-filter me-1"></i>فلتر</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 group-show-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:48px;"><input type="checkbox" id="select-all-new" class="form-check-input"></th>
                                        <th>نص السؤال</th>
                                        <th>النوع</th>
                                        <th class="text-center">الصعوبة</th>
                                        <th class="text-center">الدرجة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($availableQuestions as $question)
                                        @php
                                            $plainText = strip_tags($question->question_text ?? '');
                                            $difficulty = $question->difficulty_level ?? 'medium';
                                        @endphp
                                        <tr class="question-row"
                                            data-type="{{ $question->question_type_id }}"
                                            data-difficulty="{{ $difficulty }}"
                                            data-text="{{ strtolower($plainText) }}">
                                            <td>
                                                <input type="checkbox" name="add_question_ids[]" value="{{ $question->id }}"
                                                       class="form-check-input add-question-checkbox">
                                            </td>
                                            <td>{{ Str::limit($plainText, 90) }}</td>
                                            <td><span class="badge bg-info-transparent text-info rounded-pill">{{ $question->questionType->display_name ?? '—' }}</span></td>
                                            <td class="text-center">
                                                @if($difficulty === 'easy')<span class="badge bg-success-transparent text-success rounded-pill">سهل</span>
                                                @elseif($difficulty === 'medium')<span class="badge bg-warning-transparent text-warning rounded-pill">متوسط</span>
                                                @else<span class="badge bg-danger-transparent text-danger rounded-pill">صعب</span>@endif
                                            </td>
                                            <td class="text-center"><span class="badge bg-primary-transparent text-primary rounded-pill">{{ $question->default_grade ?? 1 }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-4">لا توجد أسئلة متاحة للإضافة</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card custom-card group-show-members-card dashboard-fade-in exam-page-animate">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between gap-3">
                            <a href="{{ route('question-pools.index') }}" class="btn btn-light"><i class="fe fe-x me-1"></i>إلغاء</a>
                            <button type="submit" class="btn btn-primary"><i class="fe fe-save me-1"></i>حفظ التعديلات</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('script')
<script>
(function () {
    function bindSelectAll(selectId, checkboxClass) {
        const sel = document.getElementById(selectId);
        if (!sel) return;
        sel.addEventListener('change', function () {
            document.querySelectorAll('.' + checkboxClass).forEach(function (cb) {
                if (cb.closest('tr') && cb.closest('tr').style.display !== 'none') {
                    cb.checked = sel.checked;
                }
            });
            updateCounts();
        });
    }

    function updateCounts() {
        const removeCount = document.querySelectorAll('.remove-question-checkbox:checked').length;
        const addCount = document.querySelectorAll('.add-question-checkbox:checked').length;
        const rc = document.getElementById('remove-count');
        const ac = document.getElementById('add-count');
        if (rc) rc.textContent = removeCount;
        if (ac) ac.textContent = addCount;
    }

    document.addEventListener('change', function (e) {
        if (e.target.matches('.remove-question-checkbox, .add-question-checkbox')) {
            updateCounts();
        }
    });

    bindSelectAll('select-all-current', 'remove-question-checkbox');
    bindSelectAll('select-all-new', 'add-question-checkbox');

    const applyBtn = document.getElementById('apply-filters');
    const searchInput = document.getElementById('filter-search');
    const typeFilter = document.getElementById('filter-type');
    const difficultyFilter = document.getElementById('filter-difficulty');

    function applyFilters() {
        const typeVal = typeFilter ? typeFilter.value : '';
        const diffVal = difficultyFilter ? difficultyFilter.value : '';
        const searchVal = searchInput ? searchInput.value.trim().toLowerCase() : '';

        document.querySelectorAll('.question-row').forEach(function (row) {
            let show = true;
            if (typeVal && row.dataset.type !== typeVal) show = false;
            if (diffVal && row.dataset.difficulty !== diffVal) show = false;
            if (searchVal && !(row.dataset.text || '').includes(searchVal)) show = false;
            row.style.display = show ? '' : 'none';
        });
    }

    if (applyBtn) applyBtn.addEventListener('click', applyFilters);
    if (searchInput) searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); applyFilters(); }
    });

    updateCounts();
})();
</script>
@stop
