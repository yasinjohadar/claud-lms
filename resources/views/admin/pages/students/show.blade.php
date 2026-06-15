@extends('admin.layouts.master')

@section('page-title')
    ملف الطالب — {{ $student->user?->name }}
@stop

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')

        @php
            $studentLabel = ($student->user?->name ?? 'طالب') . ($student->student_code ? ' — #' . $student->student_code : '');
            $showHeaderActions = '<a href="' . route('admin.students.edit', $student) . '" class="btn btn-primary btn-wave me-2"><i class="ri-pencil-line me-1"></i> تعديل</a>';
            $showHeaderActions .= '<a href="' . route('admin.students.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>';
            if (auth()->user()?->can('enrollment-manage')) {
                $showHeaderActions = '<button type="button" class="btn btn-success btn-wave me-2" data-open-enrollment-grant data-modal-id="studentEnrollmentGrantModal" data-student-id="' . $student->id . '" data-student-label="' . e($studentLabel) . '"><i class="ri-user-add-line me-1"></i> تسجيل في كورس</button>' . $showHeaderActions;
            }
        @endphp

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الطلاب', 'url' => route('admin.students.index')],
                ['label' => $student->user?->name ?? 'طالب'],
            ],
            'title' => $student->user?->name ?? 'ملف الطالب',
            'subtitle' => $student->student_code . ' • ' . $student->status_label,
            'actions' => $showHeaderActions,
        ])

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card custom-card">
                    <div class="card-header"><span class="fw-bold">معلومات أساسية</span></div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><span class="text-muted">البريد:</span> <span dir="ltr">{{ $student->user?->email }}</span></li>
                            <li class="mb-2"><span class="text-muted">الهاتف:</span> {{ $student->user?->phone ?? '—' }}</li>
                            <li class="mb-2"><span class="text-muted">الدولة:</span> {{ $student->country ?? '—' }}</li>
                            <li class="mb-2"><span class="text-muted">المدينة:</span> {{ $student->city ?? '—' }}</li>
                            <li class="mb-2"><span class="text-muted">التعليم:</span> {{ $student->education_level ?? '—' }}</li>
                            <li><span class="text-muted">مسجّل منذ:</span> {{ $student->created_at?->locale('ar')->translatedFormat('j F Y') }}</li>
                        </ul>
                    </div>
                </div>

                @can('enrollment-manage')
                <div class="card custom-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="fw-bold">تسجيل سريع</span>
                        <span class="badge-soft badge-soft-primary">{{ $student->enrollments->count() }}</span>
                    </div>
                    <div class="card-body">
                        <p class="text-muted fs-13 mb-3">أضف هذا الطالب إلى كورس جديد عبر نافذة التسجيل الموحّدة.</p>
                        <button type="button" class="btn btn-success w-100 btn-wave" data-open-enrollment-grant
                                data-modal-id="studentEnrollmentGrantModal"
                                data-student-id="{{ $student->id }}"
                                data-student-label="{{ $studentLabel }}">
                            <i class="ri-add-circle-line me-1"></i> تسجيل في كورس
                        </button>
                        <a href="{{ route('admin.enrollments.index', ['student_id' => $student->id]) }}"
                           class="btn btn-light border w-100 mt-2 btn-wave">
                            <i class="ri-external-link-line me-1"></i> عرض كل التسجيلات
                        </a>
                    </div>
                </div>
                @endcan
            </div>

            <div class="col-lg-8">
                <div class="card custom-card data-table-card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <span class="fw-bold">التسجيلات في الكورسات</span>
                        <a href="{{ route('admin.enrollments.index', ['student_id' => $student->id]) }}" class="btn btn-sm btn-light border">
                            إدارة التسجيلات
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table data-table mb-0">
                                <thead>
                                    <tr>
                                        <th>الكورس</th>
                                        <th>الحالة</th>
                                        <th>المصدر</th>
                                        <th>التقدم</th>
                                        <th>تاريخ التسجيل</th>
                                        <th>الطلب</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($student->enrollments as $enrollment)
                                        @php
                                            $statusBadge = match ($enrollment->status) {
                                                'active' => 'badge-soft-success',
                                                'pending' => 'badge-soft-warning',
                                                'completed' => 'badge-soft-primary',
                                                'cancelled', 'refunded' => 'badge-soft-danger',
                                                default => 'badge-soft-secondary',
                                            };
                                        @endphp
                                        <tr>
                                            <td class="fw-semibold">{{ $enrollment->course?->title ?? '—' }}</td>
                                            <td><span class="badge-soft {{ $statusBadge }}">{{ $enrollment->status_label }}</span></td>
                                            <td>{{ $enrollment->source_label }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress flex-fill rounded-pill" style="height: 6px; min-width: 50px;">
                                                        <div class="progress-bar bg-success rounded-pill" style="width: {{ max($enrollment->progress_percent, $enrollment->progress_percent > 0 ? 6 : 0) }}%"></div>
                                                    </div>
                                                    <span class="fs-12">{{ $enrollment->progress_percent }}%</span>
                                                </div>
                                            </td>
                                            <td class="text-muted fs-12">{{ $enrollment->enrolled_at?->locale('ar')->translatedFormat('j M Y') }}</td>
                                            <td>
                                                @if($enrollment->order)
                                                    <a href="{{ route('admin.orders.show', $enrollment->order) }}" class="fs-12">{{ $enrollment->order->order_number }}</a>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>
                                                @if($enrollment->status !== 'cancelled')
                                                    <button type="button" class="action-btn action-btn--delete"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#cancelStudentEnrollment{{ $enrollment->id }}"
                                                            title="إلغاء">
                                                        <i class="ri-close-circle-line"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="7" class="text-center text-muted py-4">لا توجد تسجيلات</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card custom-card data-table-card">
                    <div class="card-header"><span class="fw-bold">الطلبات</span></div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table data-table mb-0">
                                <thead>
                                    <tr>
                                        <th>رقم الطلب</th>
                                        <th>الحالة</th>
                                        <th>المبلغ</th>
                                        <th>التاريخ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($student->orders as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->status_label }}</td>
                                            <td>{{ number_format($order->total, 2) }} {{ $order->currency }}</td>
                                            <td>{{ $order->created_at?->locale('ar')->translatedFormat('j M Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-light border">عرض</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-4">لا توجد طلبات</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @foreach($student->enrollments as $enrollment)
            @if($enrollment->status !== 'cancelled')
                <x-admin.confirm-modal
                    :id="'cancelStudentEnrollment' . $enrollment->id"
                    title="إلغاء التسجيل"
                    message="سيتم إلغاء وصول الطالب إلى هذا الكورس."
                    :subject="$enrollment->course?->title"
                    :action="route('admin.enrollments.destroy', $enrollment)"
                    method="DELETE"
                    variant="warning"
                    confirm-text="نعم، ألغِ التسجيل"
                />
            @endif
        @endforeach

        @can('enrollment-manage')
        @include('admin.partials.enrollments.grant-modal', [
            'modalId' => 'studentEnrollmentGrantModal',
            'formAction' => route('admin.enrollments.store'),
            'presetStudentId' => $student->id,
            'presetStudentLabel' => $studentLabel,
            'lockStudent' => true,
            'title' => 'تسجيل الطالب في كورس',
            'subtitle' => 'اختر الكورس المناسب لهذا الطالب',
        ])
        @endcan
    </div>
</div>
@endsection

@push('scripts')
    @include('admin.partials.enrollments.grant-scripts')
@endpush
