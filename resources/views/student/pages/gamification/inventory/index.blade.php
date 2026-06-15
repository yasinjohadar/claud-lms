@extends('student.layouts.master')
@section('page-title') المخزون @stop
@section('content')
<div class="main-content app-content student-gamification-dashboard">
    <div class="container-fluid pb-3">
        <div class="d-md-flex align-items-center justify-content-between my-4">
            <h4 class="mb-0">مخزوني</h4>
            <a href="{{ route('gamification.shop.index') }}" class="btn btn-sm btn-primary">المتجر</a>
        </div>
        @if(!empty($stats))
            <div class="row g-3 mb-4">
                <div class="col-md-4"><div class="card custom-card mb-0"><div class="card-body text-center"><div class="fw-bold">{{ $stats['total'] ?? 0 }}</div><div class="text-muted fs-12">إجمالي</div></div></div></div>
                <div class="col-md-4"><div class="card custom-card mb-0"><div class="card-body text-center"><div class="fw-bold text-success">{{ $stats['active'] ?? 0 }}</div><div class="text-muted fs-12">نشط</div></div></div></div>
                <div class="col-md-4"><div class="card custom-card mb-0"><div class="card-body text-center"><div class="fw-bold text-warning">{{ $stats['expired'] ?? 0 }}</div><div class="text-muted fs-12">منتهي</div></div></div></div>
            </div>
        @endif
        <div class="row g-3">
            @forelse($inventory as $item)
                <div class="col-lg-4 col-md-6">
                    <div class="card border h-100">
                        <div class="card-body">
                            <h6 class="fw-bold">{{ $item->item_name ?? $item->name ?? 'عنصر' }}</h6>
                            <p class="text-muted fs-12 mb-2">{{ $item->item_type ?? $item->type ?? '—' }}</p>
                            @if(!empty($item->expires_at))
                                <p class="fs-12 text-warning mb-2">ينتهي: {{ $item->time_remaining['human'] ?? $item->expires_at }}</p>
                            @endif
                            <div class="d-flex gap-2">
                                @if(($item->status ?? '') === 'inactive' || ($item->is_active ?? false) === false)
                                    <button type="button" class="btn btn-sm btn-success inv-action" data-action="activate" data-id="{{ $item->id }}">تفعيل</button>
                                @else
                                    <button type="button" class="btn btn-sm btn-light border inv-action" data-action="deactivate" data-id="{{ $item->id }}">إيقاف</button>
                                @endif
                                @if($item->is_consumable ?? false)
                                    <button type="button" class="btn btn-sm btn-primary inv-action" data-action="consume" data-id="{{ $item->id }}">استخدام</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">المخزون فارغ — اشترِ من المتجر</div>
            @endforelse
        </div>
    </div>
</div>
@stop
@push('scripts')
<script>
document.querySelectorAll('.inv-action').forEach(btn => {
    btn.addEventListener('click', async function() {
        const id = this.dataset.id, action = this.dataset.action;
        const res = await fetch(`{{ url('/student/gamification/inventory') }}/${id}/${action}`, {
            method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (data.success) location.reload(); else alert(data.message || 'فشل الإجراء');
    });
});
</script>
@endpush
