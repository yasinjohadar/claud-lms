@extends('student.layouts.master')

@section('page-title')
    المتجر
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid pb-3">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('student.dashboard')],
                ['label' => 'التلعيب', 'url' => route('gamification.dashboard')],
                ['label' => 'المتجر'],
            ],
            'title' => 'متجر المكافآت',
            'subtitle' => 'استبدل نقاطك وجواهرك بمكافآت وتعزيزات ومظهر مميز',
            'actions' => '
                <div class="d-flex flex-wrap gap-2">
                    <a href="' . route('gamification.inventory.index') . '" class="btn btn-light border btn-wave">
                        <i class="ri-archive-line me-1"></i>مخزوني
                    </a>
                    <a href="' . route('gamification.dashboard') . '" class="btn btn-primary btn-wave">
                        <i class="ri-trophy-line me-1"></i>لوحة التلعيب
                    </a>
                </div>
            ',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple',
                'icon' => 'ri-coin-line',
                'label' => 'رصيد النقاط',
                'value' => number_format($userPoints ?? 0),
                'hint' => 'متاح للشراء',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange',
                'icon' => 'ri-vip-diamond-line',
                'label' => 'رصيد الجواهر',
                'value' => number_format($userGems ?? 0),
                'hint' => 'عملة مميزة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green',
                'icon' => 'ri-store-2-line',
                'label' => 'المنتجات',
                'value' => number_format(collect($categories ?? [])->sum(fn ($c) => $c->items->count())),
                'hint' => 'عناصر متاحة الآن',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan',
                'icon' => 'ri-shopping-bag-3-line',
                'label' => 'مشترياتي',
                'value' => number_format(count($myPurchases ?? [])),
                'hint' => 'آخر العمليات',
            ])
        </div>

        <div id="shopAlert" class="d-none alert mb-3"></div>

        @forelse($categories ?? [] as $category)
            <div class="card custom-card mb-4">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title mb-1">
                        <span class="me-1">{{ $category->icon ?? '📦' }}</span>
                        {{ $category->name }}
                    </h5>
                    @if($category->description)
                        <p class="text-muted fs-12 mb-0">{{ $category->description }}</p>
                    @endif
                </div>
                <div class="card-body pt-3">
                    <div class="row g-3">
                        @forelse($category->items as $index => $item)
                            @include('student.pages.gamification.partials.shop-item-card', [
                                'item' => $item,
                                'category' => $category,
                                'userPoints' => $userPoints,
                                'userGems' => $userGems,
                                'index' => $index,
                            ])
                        @empty
                            <div class="col-12">
                                <div class="empty-state py-4">
                                    <div class="empty-state-icon mx-auto mb-3"><i class="ri-shopping-bag-3-line"></i></div>
                                    <p class="text-muted mb-0">لا توجد منتجات في هذه الفئة</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="card custom-card">
                <div class="card-body text-center py-5">
                    <div class="empty-state-icon mx-auto mb-3"><i class="ri-store-2-line"></i></div>
                    <p class="text-muted mb-1">لا توجد فئات في المتجر حالياً</p>
                    <p class="text-muted fs-12 mb-0">ستظهر المنتجات هنا عند تفعيلها من الإدارة</p>
                </div>
            </div>
        @endforelse

        @if(!empty($myPurchases) && count($myPurchases))
            <div class="card custom-card">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title mb-1">
                        <i class="ri-history-line text-info me-1"></i>
                        مشترياتي الأخيرة
                    </h5>
                    <p class="text-muted fs-12 mb-0">آخر عمليات الشراء من المتجر</p>
                </div>
                <div class="card-body pt-3 p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>العنصر</th>
                                    <th>الطريقة</th>
                                    <th>السعر</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myPurchases as $purchase)
                                    <tr>
                                        <td class="fw-semibold fs-13">{{ $purchase->item_name ?? $purchase->shopItem?->name ?? '—' }}</td>
                                        <td>
                                            <span class="badge {{ $purchase->payment_method === 'gems' ? 'bg-warning-transparent text-warning' : 'bg-primary-transparent text-primary' }}">
                                                {{ $purchase->payment_method === 'gems' ? 'جواهر' : 'نقاط' }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($purchase->final_price ?? 0) }}</td>
                                        <td class="text-muted fs-12">{{ $purchase->created_at?->locale('ar')->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@stop

@push('scripts')
<script>
document.querySelectorAll('.shop-purchase-btn').forEach(function (btn) {
    btn.addEventListener('click', async function () {
        if (this.disabled || this.classList.contains('is-disabled')) {
            return;
        }

        const itemId = this.dataset.itemId;
        const method = this.dataset.method;
        const alertEl = document.getElementById('shopAlert');
        const card = this.closest('.gamification-shop-widget');

        this.disabled = true;
        if (card) {
            card.classList.add('is-purchasing');
        }

        try {
            const res = await fetch(`{{ url('/student/gamification/shop/items') }}/${itemId}/purchase`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ payment_method: method }),
            });
            const data = await res.json();
            alertEl.classList.remove('d-none', 'alert-success', 'alert-danger');
            if (data.success) {
                alertEl.classList.add('alert-success');
                alertEl.textContent = data.message || 'تم الشراء بنجاح';
                setTimeout(() => location.reload(), 1200);
            } else {
                alertEl.classList.add('alert-danger');
                alertEl.textContent = data.message || 'تعذّر إتمام الشراء';
                this.disabled = false;
                if (card) {
                    card.classList.remove('is-purchasing');
                }
            }
        } catch (e) {
            alertEl.classList.remove('d-none');
            alertEl.classList.add('alert-danger');
            alertEl.textContent = 'حدث خطأ أثناء الشراء';
            this.disabled = false;
            if (card) {
                card.classList.remove('is-purchasing');
            }
        }
    });
});
</script>
@endpush
