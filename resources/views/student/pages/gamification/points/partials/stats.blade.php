@php
    $statCards = [
        ['variant' => 'purple', 'icon' => 'ri-star-line', 'label' => 'إجمالي النقاط', 'value' => number_format($totalPoints ?? 0), 'hint' => 'كل ما كسبته'],
        ['variant' => 'green', 'icon' => 'ri-wallet-3-line', 'label' => 'النقاط المتاحة', 'value' => number_format($availablePoints ?? 0), 'hint' => 'جاهزة للاستخدام'],
        ['variant' => 'orange', 'icon' => 'ri-shopping-cart-line', 'label' => 'النقاط المستهلكة', 'value' => number_format($spentPoints ?? 0), 'hint' => 'في المتجر والمكافآت'],
        ['variant' => 'cyan', 'icon' => 'ri-calendar-check-line', 'label' => 'كسب هذا الشهر', 'value' => number_format($monthlyEarned ?? 0), 'hint' => now()->translatedFormat('F Y')],
    ];
@endphp

<div class="row g-3 mb-4">
    @foreach ($statCards as $card)
        @include('admin.partials.ui.stat-card-gradient', $card)
    @endforeach
</div>
