@extends('admin.layouts.master')

@section('page-title')
    وحدات الأسئلة
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            @include('admin.partials.ui.alerts')

            <div class="my-4 page-header-breadcrumb exam-page-animate dashboard-fade-in">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active">وحدات الأسئلة</li>
                    </ol>
                </nav>
            </div>

            <div class="group-show-hero dashboard-fade-in exam-page-animate mb-4">
                <div class="row align-items-start g-3">
                    <div class="col-lg-7">
                        <span class="group-show-hero__eyebrow">
                            <i class="fe fe-grid me-1"></i>
                            التدريب والممارسة
                        </span>
                        <h2 class="group-show-hero__title mb-2">وحدات الأسئلة</h2>
                        <p class="group-show-hero__desc mb-0">
                            إنشاء وحدات تدريبية مستقلة، إدارة أسئلتها، ونشرها للطلاب ضمن الكورسات.
                        </p>
                    </div>
                    <div class="col-lg-5">
                        <div class="group-show-actions">
                            <a href="{{ route('question-modules.create') }}" class="group-show-action group-show-action--primary">
                                <span class="group-show-action__icon"><i class="fe fe-plus"></i></span>
                                <span class="group-show-action__text">إضافة وحدة جديدة</span>
                            </a>
                            <a href="{{ route('admin.question-module-grading.index') }}" class="group-show-action group-show-action--warning">
                                <span class="group-show-action__icon"><i class="fe fe-edit-3"></i></span>
                                <span class="group-show-action__text">تصحيح الوحدات</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4 exam-page-animate">
                @include('admin.pages.question-modules.partials.stats', ['questionModules' => $questionModules])
            </div>

            <div class="card custom-card group-show-members-card dashboard-fade-in exam-page-animate">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 border-0 pb-0">
                    <h6 class="group-show-members-card__title mb-0">
                        قائمة وحدات الأسئلة
                        <span class="group-show-members-card__count">{{ $questionModules->total() }}</span>
                    </h6>
                </div>
                <div class="card-body pt-3">
                    @if($questionModules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>العنوان</th>
                                        <th>عدد الأسئلة</th>
                                        <th>المنشئ</th>
                                        <th>الحالة</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th width="180">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($questionModules as $module)
                                        <tr>
                                            <td class="text-muted">{{ $loop->iteration + ($questionModules->currentPage() - 1) * $questionModules->perPage() }}</td>
                                            <td>
                                                <h6 class="mb-0 fw-semibold">{{ $module->title }}</h6>
                                                @if($module->description)
                                                    <small class="text-muted">{{ Str::limit($module->description, 60) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-primary-transparent">
                                                    <i class="fe fe-help-circle me-1"></i>{{ $module->questions->count() }} سؤال
                                                </span>
                                            </td>
                                            <td>
                                                @if($module->creator)
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="avatar avatar-sm bg-primary-transparent text-primary">
                                                            {{ mb_substr($module->creator->name, 0, 1) }}
                                                        </span>
                                                        <span class="fs-13">{{ $module->creator->name }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted">غير محدد</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @if($module->is_published)
                                                        <span class="badge bg-success-transparent text-success">منشور</span>
                                                    @else
                                                        <span class="badge bg-secondary-transparent">مسودة</span>
                                                    @endif
                                                    @if($module->is_visible)
                                                        <span class="badge bg-info-transparent text-info">مرئي</span>
                                                    @else
                                                        <span class="badge bg-warning-transparent text-warning">مخفي</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted d-block">{{ $module->created_at->format('Y-m-d') }}</small>
                                                <small class="text-muted">{{ $module->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('question-modules.show', $module->id) }}" class="btn btn-sm btn-info-light" title="عرض">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                    <a href="{{ route('question-modules.manage-questions', $module->id) }}" class="btn btn-sm btn-primary-light" title="إدارة الأسئلة">
                                                        <i class="fe fe-settings"></i>
                                                    </a>
                                                    <a href="{{ route('question-modules.edit', $module->id) }}" class="btn btn-sm btn-warning-light" title="تعديل">
                                                        <i class="fe fe-edit-2"></i>
                                                    </a>
                                                    <form action="{{ route('question-modules.destroy', $module->id) }}" method="POST" class="d-inline"
                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الوحدة؟')">
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
                        <div class="mt-3">
                            {{ $questionModules->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="admin-stats-card__icon-wrap mx-auto mb-3" style="width:4rem;height:4rem;">
                                <i class="fe fe-grid admin-stats-card__icon"></i>
                            </div>
                            <h5 class="mb-2">لا توجد وحدات أسئلة</h5>
                            <p class="text-muted mb-3">أنشئ وحدة تدريبية لجمع الأسئلة وتقديمها للطلاب.</p>
                            <a href="{{ route('question-modules.create') }}" class="btn btn-primary btn-sm">
                                <i class="fe fe-plus me-1"></i>إضافة وحدة جديدة
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
