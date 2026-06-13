@extends('admin.layouts.master')

@section('page-title')
    الطلبات
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الطلبات'],
            ],
            'title' => 'إدارة الطلبات',
            'subtitle' => 'متابعة طلبات الشراء وتأكيد الدفع',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-shopping-bag-line',
                'label' => 'إجمالي الطلبات', 'value' => number_format($stats['total']),
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-time-line',
                'label' => 'قيد الانتظار', 'value' => number_format($stats['pending']),
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-check-double-line',
                'label' => 'مدفوعة', 'value' => number_format($stats['paid']),
            ])
        </div>

        <div class="filter-panel mb-4">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fs-12 text-muted">بحث</label>
                    <input type="text" name="search" class="form-control" placeholder="رقم الطلب أو اسم الطالب"
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fs-12 text-muted">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">الكل</option>
                        @foreach(\App\Models\Order::STATUSES as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                {{ (new \App\Models\Order(['status' => $status]))->status_label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="ri-search-line me-1"></i> بحث</button>
                </div>
            </form>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>الطالب</th>
                                <th>الحالة</th>
                                <th>المبلغ</th>
                                <th>التاريخ</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="fw-bold">{{ $order->order_number }}</td>
                                    <td>{{ $order->student?->user?->name }}</td>
                                    <td><span class="badge-soft badge-soft-primary">{{ $order->status_label }}</span></td>
                                    <td>{{ number_format($order->total, 2) }} {{ $order->currency }}</td>
                                    <td>{{ $order->created_at?->locale('ar')->translatedFormat('j M Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-light border">عرض</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-5">لا توجد طلبات</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())
                    <div class="card-footer border-top">{{ $orders->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
