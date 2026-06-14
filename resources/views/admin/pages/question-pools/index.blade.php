@extends('admin.layouts.master')

@section('page-title')
    مجموعات الأسئلة
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            @include('admin.partials.ui.alerts')

            <div class="my-4 page-header-breadcrumb exam-page-animate dashboard-fade-in">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active">مجموعات الأسئلة</li>
                    </ol>
                </nav>
            </div>

            <div class="group-show-hero dashboard-fade-in exam-page-animate mb-4">
                <div class="row align-items-start g-3">
                    <div class="col-lg-7">
                        <span class="group-show-hero__eyebrow">
                            <i class="fe fe-layers me-1"></i>
                            تنظيم الأسئلة
                        </span>
                        <h2 class="group-show-hero__title mb-2">مجموعات الأسئلة</h2>
                        <p class="group-show-hero__desc mb-0">
                            تجميع الأسئلة في مجموعات حسب الكورس لاستخدامها في الاختبارات والوحدات التدريبية.
                        </p>
                    </div>
                    <div class="col-lg-5">
                        <div class="group-show-actions">
                            <a href="{{ route('question-pools.create') }}" class="group-show-action group-show-action--primary">
                                <span class="group-show-action__icon"><i class="fe fe-plus"></i></span>
                                <span class="group-show-action__text">إضافة مجموعة جديدة</span>
                            </a>
                            <a href="{{ route('question-bank.index') }}" class="group-show-action group-show-action--info">
                                <span class="group-show-action__icon"><i class="fe fe-database"></i></span>
                                <span class="group-show-action__text">بنك الأسئلة</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4 exam-page-animate">
                @include('admin.pages.question-pools.partials.stats', ['stats' => $stats ?? []])
            </div>

            <div class="card custom-card group-show-members-card dashboard-fade-in exam-page-animate mb-4">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title mb-1">تصفية المجموعات</h4>
                    <p class="fs-12 text-muted mb-0">ابحث بالاسم أو فلتر حسب الكورس والحالة.</p>
                </div>
                <div class="card-body pt-3">
                    <form action="{{ route('question-pools.index') }}" method="GET" class="group-show-filters mb-0">
                        <div class="row g-3 align-items-end">
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label">الكورس</label>
                                <select name="course_id" class="form-select">
                                    <option value="">جميع الكورسات</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-6">
                                <label class="form-label">الحالة</label>
                                <select name="status" class="form-select">
                                    <option value="">جميع الحالات</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                </select>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <label class="form-label">البحث</label>
                                <input type="text" name="search" class="form-control" placeholder="ابحث عن مجموعة..." value="{{ request('search') }}">
                            </div>
                            <div class="col-xl-12">
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fe fe-search me-1"></i>بحث
                                    </button>
                                    <a href="{{ route('question-pools.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fe fe-rotate-cw me-1"></i>إعادة تعيين
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card custom-card group-show-members-card dashboard-fade-in exam-page-animate">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 border-0 pb-0">
                    <h6 class="group-show-members-card__title mb-0">
                        قائمة المجموعات
                        <span class="group-show-members-card__count">{{ $pools->total() }}</span>
                    </h6>
                </div>
                <div class="card-body pt-3 p-0">
                    @if($pools->count() > 0)
                        <div class="table-responsive px-3 pb-3">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>اسم المجموعة</th>
                                        <th>الكورس</th>
                                        <th>الوصف</th>
                                        <th>عدد الأسئلة</th>
                                        <th>الحالة</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pools as $pool)
                                        <tr>
                                            <td class="text-muted">{{ $pool->id }}</td>
                                            <td>
                                                <a href="{{ route('question-pools.show', $pool->id) }}" class="fw-semibold text-primary">
                                                    {{ $pool->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary-transparent">{{ $pool->course->title }}</span>
                                            </td>
                                            <td><span class="text-muted fs-13">{{ Str::limit($pool->description, 50) ?: '—' }}</span></td>
                                            <td>
                                                <span class="badge bg-info-transparent text-info">
                                                    <i class="fe fe-help-circle me-1"></i>{{ $pool->questions_count ?? 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($pool->is_active)
                                                    <span class="badge bg-success-transparent text-success">نشط</span>
                                                @else
                                                    <span class="badge bg-danger-transparent text-danger">غير نشط</span>
                                                @endif
                                            </td>
                                            <td class="text-muted fs-13">{{ $pool->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('question-pools.show', $pool->id) }}" class="btn btn-sm btn-info-light" title="عرض">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                    <a href="{{ route('question-pools.edit', $pool->id) }}" class="btn btn-sm btn-warning-light" title="تعديل">
                                                        <i class="fe fe-edit-2"></i>
                                                    </a>
                                                    <form action="{{ route('question-pools.destroy', $pool->id) }}" method="POST" class="d-inline"
                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه المجموعة؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger-light" title="حذف">
                                                            <i class="fe fe-trash-2"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer border-top">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <span class="text-muted fs-13">
                                    عرض {{ $pools->firstItem() }} إلى {{ $pools->lastItem() }} من {{ $pools->total() }} مجموعة
                                </span>
                                {{ $pools->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5 px-3">
                            <div class="admin-stats-card__icon-wrap mx-auto mb-3" style="width:4rem;height:4rem;">
                                <i class="fe fe-layers admin-stats-card__icon"></i>
                            </div>
                            <h5 class="mb-2">لا توجد مجموعات أسئلة</h5>
                            <p class="text-muted mb-3">ابدأ بإنشاء أول مجموعة لتنظيم الأسئلة حسب الكورس.</p>
                            <a href="{{ route('question-pools.create') }}" class="btn btn-primary btn-sm">
                                <i class="fe fe-plus me-1"></i>إضافة مجموعة جديدة
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@stop

@section('script')
    @include('admin.partials.ui.stats-countup')
@stop
