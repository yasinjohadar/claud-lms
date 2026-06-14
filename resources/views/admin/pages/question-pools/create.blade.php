@extends('admin.layouts.master')

@section('page-title')
    إضافة مجموعة أسئلة جديدة
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
                        <li class="breadcrumb-item active">إضافة مجموعة</li>
                    </ol>
                </nav>
            </div>

            <div class="group-show-hero dashboard-fade-in exam-page-animate mb-4">
                <div class="row align-items-start g-3">
                    <div class="col-lg-8">
                        <span class="group-show-hero__eyebrow">
                            <i class="fe fe-layers me-1"></i>
                            مجموعة جديدة
                        </span>
                        <h2 class="group-show-hero__title mb-2">إضافة مجموعة أسئلة</h2>
                        <p class="group-show-hero__desc mb-0">
                            أنشئ مجموعة أسئلة مرتبطة بكورس، ثم اختر الأسئلة من بنك الأسئلة لاستخدامها في الاختبارات.
                        </p>
                    </div>
                    <div class="col-lg-4">
                        <div class="group-show-actions">
                            <a href="{{ route('question-pools.index') }}" class="group-show-action">
                                <span class="group-show-action__icon"><i class="fe fe-arrow-right"></i></span>
                                <span class="group-show-action__text">العودة للقائمة</span>
                            </a>
                            <a href="{{ route('question-bank.index') }}" class="group-show-action group-show-action--info">
                                <span class="group-show-action__icon"><i class="fe fe-database"></i></span>
                                <span class="group-show-action__text">بنك الأسئلة</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('question-pools.store') }}" method="POST">
                @csrf

                <div class="card custom-card group-show-members-card dashboard-fade-in exam-page-animate mb-4">
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title mb-1 d-flex align-items-center gap-2">
                            <span class="assignments-section-icon"><i class="fe fe-info"></i></span>
                            المعلومات الأساسية
                        </h4>
                        <p class="fs-12 text-muted mb-0">اسم المجموعة والكورس المرتبط بها.</p>
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">اسم المجموعة <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" placeholder="مثال: أسئلة Laravel — الوحدة الأولى" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">الكورس <span class="text-danger">*</span></label>
                                <select name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                                    <option value="">اختر الكورس</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">الوصف <span class="text-muted fs-12">(اختياري)</span></label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                          rows="3" placeholder="وصف مختصر عن محتوى المجموعة واستخدامها...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card custom-card group-show-members-card dashboard-fade-in exam-page-animate mb-4">
                    <div class="card-header border-0 pb-0 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h4 class="card-title mb-1 d-flex align-items-center gap-2">
                                <span class="assignments-section-icon"><i class="fe fe-check-square"></i></span>
                                اختيار الأسئلة
                            </h4>
                            <p class="fs-12 text-muted mb-0">حدّد الأسئلة التي تريد إضافتها لهذه المجموعة.</p>
                        </div>
                        <span class="qp-picker-summary">
                            <i class="fe fe-layers"></i>
                            محدّد: <strong id="selected-count">0</strong>
                        </span>
                    </div>
                    <div class="card-body pt-3">
                        <div class="qp-picker-toolbar mb-4">
                            <div class="row g-3 align-items-end group-show-filters">
                                <div class="col-xl-3 col-lg-4 col-md-6">
                                    <label class="form-label">نوع السؤال</label>
                                    <select id="filter-type" class="form-select">
                                        <option value="">جميع الأنواع</option>
                                        @foreach($questionTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->display_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-md-6">
                                    <label class="form-label">الصعوبة</label>
                                    <select id="filter-difficulty" class="form-select">
                                        <option value="">جميع المستويات</option>
                                        <option value="easy">سهل</option>
                                        <option value="medium">متوسط</option>
                                        <option value="hard">صعب</option>
                                    </select>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-8">
                                    <label class="form-label">البحث</label>
                                    <input type="text" id="filter-search" class="form-control" placeholder="ابحث في نص السؤال...">
                                </div>
                                <div class="col-xl-2 col-lg-12">
                                    <button type="button" class="btn btn-primary btn-sm w-100" id="apply-filters">
                                        <i class="fe fe-filter me-1"></i>تطبيق الفلتر
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 group-show-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 48px;">
                                            <input type="checkbox" id="select-all" class="form-check-input">
                                        </th>
                                        <th>نص السؤال</th>
                                        <th>النوع</th>
                                        <th class="text-center">الصعوبة</th>
                                        <th class="text-center">الدرجة</th>
                                    </tr>
                                </thead>
                                <tbody id="questions-table-body">
                                    @forelse($questions as $question)
                                        @php
                                            $plainText = strip_tags($question->question_text ?? '');
                                            $difficulty = $question->difficulty_level ?? 'medium';
                                        @endphp
                                        <tr class="question-row"
                                            data-type="{{ $question->question_type_id }}"
                                            data-difficulty="{{ $difficulty }}"
                                            data-text="{{ strtolower($plainText) }}">
                                            <td>
                                                <input type="checkbox" name="question_ids[]"
                                                       value="{{ $question->id }}"
                                                       class="form-check-input question-checkbox">
                                            </td>
                                            <td>
                                                <div class="qp-question-text fw-semibold fs-13">
                                                    {{ Str::limit($plainText, 90) }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info-transparent text-info rounded-pill">
                                                    {{ $question->questionType->display_name ?? '—' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($difficulty === 'easy')
                                                    <span class="badge bg-success-transparent text-success rounded-pill">سهل</span>
                                                @elseif($difficulty === 'medium')
                                                    <span class="badge bg-warning-transparent text-warning rounded-pill">متوسط</span>
                                                @else
                                                    <span class="badge bg-danger-transparent text-danger rounded-pill">صعب</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary-transparent text-primary rounded-pill">
                                                    {{ $question->default_grade ?? 1 }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="qp-empty-row">
                                            <td colspan="5" class="text-center">
                                                <div class="admin-stats-card__icon-wrap mx-auto mb-3" style="width:3.5rem;height:3.5rem;">
                                                    <i class="fe fe-inbox admin-stats-card__icon"></i>
                                                </div>
                                                <h6 class="mb-1">لا توجد أسئلة</h6>
                                                <p class="text-muted fs-13 mb-0">أضف أسئلة إلى بنك الأسئلة أولاً.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card custom-card group-show-members-card dashboard-fade-in exam-page-animate">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <a href="{{ route('question-pools.index') }}" class="btn btn-light">
                                <i class="fe fe-x me-1"></i>إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fe fe-save me-1"></i>حفظ المجموعة
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
@stop

@section('script')
    @include('admin.pages.question-pools.partials.question-picker-script')
@stop
