@extends('admin.layouts.master')

@section('page-title')
    ملف الطالب — {{ $student->user?->name }}
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الطلاب', 'url' => route('admin.students.index')],
                ['label' => $student->user?->name ?? 'طالب'],
            ],
            'title' => $student->user?->name ?? 'ملف الطالب',
            'subtitle' => $student->student_code . ' • ' . $student->status_label,
            'actions' => '
                <a href="' . route('admin.students.edit', $student) . '" class="btn btn-primary btn-wave me-2"><i class="ri-pencil-line me-1"></i> تعديل</a>
                <a href="' . route('admin.students.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>
            ',
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

                <div class="card custom-card">
                    <div class="card-header"><span class="fw-bold">منح تسجيل كورس</span></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.students.enrollments.store', $student) }}">
                            @csrf
                            <div class="mb-3">
                                <select name="course_id" class="form-select" required>
                                    <option value="">اختر كورساً</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="ri-add-circle-line me-1"></i> تسجيل
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card custom-card data-table-card mb-4">
                    <div class="card-header"><span class="fw-bold">التسجيلات في الكورسات</span></div>
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
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($student->enrollments as $enrollment)
                                        <tr>
                                            <td>{{ $enrollment->course?->title }}</td>
                                            <td><span class="badge-soft badge-soft-primary">{{ $enrollment->status_label }}</span></td>
                                            <td>{{ $enrollment->source_label }}</td>
                                            <td>{{ $enrollment->progress_percent }}%</td>
                                            <td>{{ $enrollment->enrolled_at?->locale('ar')->translatedFormat('j M Y') }}</td>
                                            <td>
                                                @if($enrollment->status !== 'cancelled')
                                                <form method="POST" action="{{ route('admin.enrollments.destroy', $enrollment) }}"
                                                      onsubmit="return confirm('إلغاء هذا التسجيل؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">إلغاء</button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center text-muted py-4">لا توجد تسجيلات</td></tr>
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
    </div>
</div>
@endsection
