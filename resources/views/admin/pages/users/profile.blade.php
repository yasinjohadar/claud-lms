@extends('admin.layouts.master')

@section('page-title')
    ملف المستخدم — {{ $user->name }}
@stop

@section('styles')
    @can('enrollment-manage')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    @endcan
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')

        @php
            $initials = mb_strtoupper(mb_substr($user->name, 0, 1));
            $roleLabels = $user->getRoleNames();
            $primaryRole = $roleLabels->first();
            $isStudent = $student !== null;
            $studentLabel = $isStudent
                ? ($user->name . ($student->student_code ? ' — #' . $student->student_code : ''))
                : null;

            $headerActions = '<a href="' . route('admin.users.edit', $user) . '" class="btn btn-primary btn-wave me-2"><i class="ri-pencil-line me-1"></i> تعديل الحساب</a>';
            if ($isStudent) {
                $headerActions = '<a href="' . route('admin.students.edit', $student) . '" class="btn btn-light border btn-wave me-2"><i class="ri-user-settings-line me-1"></i> تعديل ملف الطالب</a>' . $headerActions;
                if (auth()->user()?->can('enrollment-manage')) {
                    $headerActions = '<button type="button" class="btn btn-success btn-wave me-2" data-open-enrollment-grant data-modal-id="userProfileGrantModal" data-student-id="' . $student->id . '" data-student-label="' . e($studentLabel) . '"><i class="ri-user-add-line me-1"></i> تسجيل في كورس</button>' . $headerActions;
                }
            }
            $headerActions .= '<a href="' . route('admin.users.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>';
        @endphp

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'المستخدمون', 'url' => route('admin.users.index')],
                ['label' => $user->name],
            ],
            'title' => $user->name,
            'subtitle' => collect([$user->email, $primaryRole, $isStudent ? $student->status_label : null])->filter()->implode(' • '),
            'actions' => $headerActions,
        ])

        @if($isStudent)
            <div class="row g-3 mb-4">
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'purple', 'icon' => 'ri-book-mark-line',
                    'label' => 'إجمالي التسجيلات', 'value' => number_format($stats['enrollments']),
                    'hint' => 'كل الكورسات',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'green', 'icon' => 'ri-play-circle-line',
                    'label' => 'كورسات نشطة', 'value' => number_format($stats['active']),
                    'hint' => 'حالة active',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'cyan', 'icon' => 'ri-checkbox-circle-line',
                    'label' => 'مكتملة', 'value' => number_format($stats['completed']),
                    'hint' => 'أنهى التعلم',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'orange', 'icon' => 'ri-shopping-bag-line',
                    'label' => 'الطلبات', 'value' => number_format($stats['orders']),
                    'hint' => 'سجل الشراء',
                ])
            </div>

            <div class="card custom-card data-table-card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <span class="fw-bold fs-16"><i class="ri-book-open-line text-primary me-2"></i>الكورسات المسجّلة</span>
                    <div class="d-flex gap-2">
                        @can('enrollment-manage')
                            <button type="button" class="btn btn-sm btn-success btn-wave" data-open-enrollment-grant
                                    data-modal-id="userProfileGrantModal"
                                    data-student-id="{{ $student->id }}"
                                    data-student-label="{{ $studentLabel }}">
                                <i class="ri-add-line me-1"></i> تسجيل جديد
                            </button>
                        @endcan
                        <a href="{{ route('admin.enrollments.index', ['student_id' => $student->id]) }}" class="btn btn-sm btn-light border">
                            إدارة التسجيلات
                        </a>
                    </div>
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
                                    @can('enrollment-manage')
                                        <th style="width: 60px;"></th>
                                    @endcan
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
                                        <td>
                                            <span class="fw-semibold d-block">{{ $enrollment->course?->title ?? '—' }}</span>
                                            @if($enrollment->course?->slug)
                                                <span class="text-muted fs-11">{{ $enrollment->course->slug }}</span>
                                            @endif
                                        </td>
                                        <td><span class="badge-soft {{ $statusBadge }}">{{ $enrollment->status_label }}</span></td>
                                        <td>{{ $enrollment->source_label }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress flex-fill rounded-pill" style="height: 6px; min-width: 60px;">
                                                    <div class="progress-bar bg-success rounded-pill" style="width: {{ max($enrollment->progress_percent, $enrollment->progress_percent > 0 ? 6 : 0) }}%"></div>
                                                </div>
                                                <span class="fs-12 text-muted">{{ $enrollment->progress_percent }}%</span>
                                            </div>
                                        </td>
                                        <td class="text-muted fs-12">{{ $enrollment->enrolled_at?->locale('ar')->translatedFormat('j M Y') ?? '—' }}</td>
                                        <td>
                                            @if($enrollment->order)
                                                <a href="{{ route('admin.orders.show', $enrollment->order) }}" class="fs-12">{{ $enrollment->order->order_number }}</a>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        @can('enrollment-manage')
                                            <td>
                                                @if($enrollment->status !== 'cancelled')
                                                    <button type="button" class="action-btn action-btn--delete"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#cancelUserEnrollment{{ $enrollment->id }}"
                                                            title="إلغاء التسجيل">
                                                        <i class="ri-close-circle-line"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()?->can('enrollment-manage') ? 7 : 6 }}" class="text-center text-muted py-5">
                                            <i class="ri-book-open-line fs-24 d-block mb-2 opacity-50"></i>
                                            لا توجد كورسات مسجّلة بعد
                                            @can('enrollment-manage')
                                                <div class="mt-3">
                                                    <button type="button" class="btn btn-sm btn-success btn-wave" data-open-enrollment-grant
                                                            data-modal-id="userProfileGrantModal"
                                                            data-student-id="{{ $student->id }}"
                                                            data-student-label="{{ $studentLabel }}">
                                                        تسجيل في أول كورس
                                                    </button>
                                                </div>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card custom-card mb-4">
                    <div class="card-body text-center pt-4 pb-3">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" class="rounded-circle mb-3" width="88" height="88" alt="" style="object-fit: cover;">
                        @else
                            <span class="row-avatar row-avatar--lg d-inline-flex mb-3" style="width: 88px; height: 88px; font-size: 2rem;">{{ $initials }}</span>
                        @endif
                        <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                        @if($user->username)
                            <p class="text-muted fs-13 mb-2" dir="ltr">@{{ $user->username }}</p>
                        @endif
                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                            @foreach($roleLabels as $role)
                                <span class="badge-soft badge-soft-primary">{{ $role }}</span>
                            @endforeach
                            @if($user->is_active)
                                <span class="badge-soft badge-soft-success">مفعّل</span>
                            @else
                                <span class="badge-soft badge-soft-danger">غير مفعّل</span>
                            @endif
                        </div>
                        @if($isStudent && $student->student_code)
                            <div class="text-muted fs-12">رمز الطالب: <span class="fw-semibold">{{ $student->student_code }}</span></div>
                        @endif
                    </div>
                </div>

                <div class="card custom-card mb-4">
                    <div class="card-header"><span class="fw-bold">بيانات التواصل</span></div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 user-profile-meta">
                            <li class="mb-3">
                                <span class="text-muted fs-12 d-block mb-1">البريد الإلكتروني</span>
                                <span dir="ltr" class="fw-medium">{{ $user->email }}</span>
                            </li>
                            <li class="mb-3">
                                <span class="text-muted fs-12 d-block mb-1">الهاتف</span>
                                @if($user->phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" rel="noopener" class="text-success text-decoration-none" dir="ltr">
                                        <i class="ri-whatsapp-line me-1"></i>{{ $user->phone }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </li>
                            <li>
                                <span class="text-muted fs-12 d-block mb-1">حالة الحساب</span>
                                <span class="fw-medium">{{ match($user->status) { 'active' => 'نشط', 'inactive' => 'غير نشط', 'banned' => 'محظور', default => $user->status } }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                @if($isStudent)
                    <div class="card custom-card mb-4">
                        <div class="card-header"><span class="fw-bold">ملف الطالب</span></div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0 user-profile-meta">
                                <li class="mb-2"><span class="text-muted">الحالة:</span> <span class="fw-medium">{{ $student->status_label }}</span></li>
                                <li class="mb-2"><span class="text-muted">الدولة:</span> {{ $student->country ?? '—' }}</li>
                                <li class="mb-2"><span class="text-muted">المدينة:</span> {{ $student->city ?? '—' }}</li>
                                <li class="mb-2"><span class="text-muted">التعليم:</span> {{ $student->education_level ?? '—' }}</li>
                                @if($student->university)
                                    <li class="mb-2"><span class="text-muted">الجامعة:</span> {{ $student->university }}</li>
                                @endif
                                @if($student->major)
                                    <li class="mb-2"><span class="text-muted">التخصص:</span> {{ $student->major }}</li>
                                @endif
                                <li class="mb-2"><span class="text-muted">مسجّل منذ:</span> {{ $student->created_at?->locale('ar')->translatedFormat('j F Y') }}</li>
                                @if($student->bio)
                                    <li class="mt-3 pt-3 border-top">
                                        <span class="text-muted fs-12 d-block mb-1">نبذة</span>
                                        <span class="fs-13">{{ $student->bio }}</span>
                                    </li>
                                @endif
                            </ul>
                            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-light border w-100 mt-3 btn-wave fs-13">
                                <i class="ri-external-link-line me-1"></i> عرض صفحة الطالب الكاملة
                            </a>
                        </div>
                    </div>
                @endif

                <div class="card custom-card">
                    <div class="card-header"><span class="fw-bold">نشاط الحساب</span></div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 user-profile-meta fs-13">
                            <li class="mb-2">
                                <span class="text-muted">تاريخ الإنشاء:</span>
                                {{ $user->created_at?->locale('ar')->translatedFormat('j F Y، H:i') ?? '—' }}
                            </li>
                            <li class="mb-2">
                                <span class="text-muted">آخر دخول:</span>
                                {{ $user->last_login_at?->locale('ar')->translatedFormat('j F Y، H:i') ?? '—' }}
                            </li>
                            @if($user->last_login_ip)
                                <li><span class="text-muted">آخر IP:</span> <span dir="ltr">{{ $user->last_login_ip }}</span></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                @if($isStudent && $student->orders->isNotEmpty())
                    <div class="card custom-card data-table-card mb-4">
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
                                        @foreach($student->orders as $order)
                                            <tr>
                                                <td class="fw-semibold">{{ $order->order_number }}</td>
                                                <td>{{ $order->status_label }}</td>
                                                <td>{{ number_format($order->total, 2) }} {{ $order->currency }}</td>
                                                <td class="text-muted fs-12">{{ $order->created_at?->locale('ar')->translatedFormat('j M Y') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-light border">عرض</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                @if(!$isStudent)
                    <div class="card custom-card">
                        <div class="card-header"><span class="fw-bold">معلومات الحساب</span></div>
                        <div class="card-body">
                            <p class="text-muted fs-13 mb-4">هذا المستخدم ليس لديه ملف طالب مرتبط. يمكنك إدارة بيانات الحساب والأدوار من صفحة التعديل.</p>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="p-3 rounded border bg-light">
                                        <span class="text-muted fs-12 d-block mb-1">الأدوار</span>
                                        <span class="fw-semibold">{{ $roleLabels->implode('، ') ?: '—' }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 rounded border bg-light">
                                        <span class="text-muted fs-12 d-block mb-1">التفعيل</span>
                                        <span class="fw-semibold">{{ $user->is_active ? 'مفعّل' : 'غير مفعّل' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($student->learning_goals)
                    <div class="card custom-card">
                        <div class="card-header"><span class="fw-bold">أهداف التعلّم</span></div>
                        <div class="card-body">
                            <p class="mb-0 fs-13 text-muted">{{ $student->learning_goals }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if($isStudent)
            @foreach($student->enrollments as $enrollment)
                @if($enrollment->status !== 'cancelled')
                    @can('enrollment-manage')
                        <x-admin.confirm-modal
                            :id="'cancelUserEnrollment' . $enrollment->id"
                            title="إلغاء التسجيل"
                            message="سيتم إلغاء وصول الطالب إلى هذا الكورس."
                            :subject="$enrollment->course?->title"
                            :action="route('admin.enrollments.destroy', $enrollment)"
                            method="DELETE"
                            variant="warning"
                            confirm-text="نعم، ألغِ التسجيل"
                        />
                    @endcan
                @endif
            @endforeach

            @can('enrollment-manage')
                @include('admin.partials.enrollments.grant-modal', [
                    'modalId' => 'userProfileGrantModal',
                    'formAction' => route('admin.enrollments.store'),
                    'presetStudentId' => $student->id,
                    'presetStudentLabel' => $studentLabel,
                    'lockStudent' => true,
                    'title' => 'تسجيل الطالب في كورس',
                    'subtitle' => 'اختر الكورس المناسب لهذا الطالب',
                ])
            @endcan
        @endif
    </div>
</div>
@stop

@push('scripts')
    @can('enrollment-manage')
        @if($isStudent)
            @include('admin.partials.enrollments.grant-scripts')
        @endif
    @endcan
@endpush
