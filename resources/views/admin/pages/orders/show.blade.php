@extends('admin.layouts.master')

@section('page-title')
    طلب {{ $order->order_number }}
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الطلبات', 'url' => route('admin.orders.index')],
                ['label' => $order->order_number],
            ],
            'title' => 'طلب #' . $order->order_number,
            'subtitle' => $order->status_label . ' • ' . ($order->student?->user?->name ?? ''),
            'actions' => '
                <a href="' . route('admin.enrollments.index', ['order_id' => $order->id]) . '" class="btn btn-primary btn-wave me-2">
                    <i class="ri-book-mark-line me-1"></i> إدارة التسجيلات
                </a>
                <a href="' . route('admin.orders.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>
            ',
        ])

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card custom-card">
                    <div class="card-header"><span class="fw-bold">تفاصيل الطلب</span></div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><span class="text-muted">الطالب:</span>
                                @if($order->student)
                                    <a href="{{ route('admin.students.show', $order->student) }}">{{ $order->student->user?->name }}</a>
                                @else — @endif
                            </li>
                            <li class="mb-2"><span class="text-muted">الحالة:</span> {{ $order->status_label }}</li>
                            <li class="mb-2"><span class="text-muted">المجموع:</span> {{ number_format($order->total, 2) }} {{ $order->currency }}</li>
                            <li class="mb-2"><span class="text-muted">طريقة الدفع:</span> {{ $order->payment_method ?? '—' }}</li>
                            <li class="mb-2"><span class="text-muted">مرجع الدفع:</span> {{ $order->payment_reference ?? '—' }}</li>
                            <li><span class="text-muted">تاريخ الدفع:</span> {{ $order->paid_at?->locale('ar')->translatedFormat('j M Y H:i') ?? '—' }}</li>
                        </ul>

                        @if(!$order->isPaid())
                        <hr>
                        <form method="POST" action="{{ route('admin.orders.mark-paid', $order) }}">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label fs-12">طريقة الدفع</label>
                                <input type="text" name="payment_method" class="form-control" value="manual" placeholder="manual">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fs-12">مرجع الدفع</label>
                                <input type="text" name="payment_reference" class="form-control" placeholder="اختياري">
                            </div>
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('تأكيد الدفع وتفعيل التسجيلات؟')">
                                <i class="ri-check-double-line me-1"></i> تأكيد الدفع
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card custom-card data-table-card mb-4">
                    <div class="card-header"><span class="fw-bold">عناصر الطلب</span></div>
                    <div class="card-body p-0">
                        <table class="table data-table mb-0">
                            <thead>
                                <tr>
                                    <th>الكورس</th>
                                    <th>السعر</th>
                                    <th>الكمية</th>
                                    <th>الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->course_title }}</td>
                                        <td>{{ number_format($item->unit_price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($order->enrollments->isNotEmpty())
                <div class="card custom-card data-table-card">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <span class="fw-bold">التسجيلات المرتبطة</span>
                        <a href="{{ route('admin.enrollments.index', ['order_id' => $order->id]) }}" class="btn btn-sm btn-light border">
                            عرض في التسجيلات
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <table class="table data-table mb-0">
                            <thead>
                                <tr>
                                    <th>الكورس</th>
                                    <th>الحالة</th>
                                    <th>المصدر</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->enrollments as $enrollment)
                                    <tr>
                                        <td>{{ $enrollment->course?->title }}</td>
                                        <td>{{ $enrollment->status_label }}</td>
                                        <td>{{ $enrollment->source_label }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
