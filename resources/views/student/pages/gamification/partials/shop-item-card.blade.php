@php
    $categoryName = $category->name ?? '';
    $categoryVariants = [
        'التخصيص والمظهر' => 'cosmetics',
        'المعززات والمضاعفات' => 'boosters',
        'الوصول للكورسات' => 'courses',
        'الميزات الخاصة' => 'features',
        'الجوائز الحقيقية' => 'physical',
    ];
    $variant = $categoryVariants[$categoryName] ?? 'default';

    $pricePoints = (int) ($item->price_points ?? 0);
    $priceGems = (int) ($item->price_gems ?? 0);
    $requiredLevel = (int) ($item->required_level ?? 1);
    $discount = (int) ($item->discount_percentage ?? 0);
    $hasActiveDiscount = $discount > 0
        && (empty($item->discount_expires_at) || $item->discount_expires_at->isFuture());

    $finalPoints = $hasActiveDiscount
        ? (int) max(0, round($pricePoints * (1 - $discount / 100)))
        : $pricePoints;
    $finalGems = $hasActiveDiscount
        ? (int) max(0, round($priceGems * (1 - $discount / 100)))
        : $priceGems;

    $userPointsBalance = (int) ($userPoints ?? 0);
    $userGemsBalance = (int) ($userGems ?? 0);
    $canBuyPoints = $pricePoints > 0 && $userPointsBalance >= $finalPoints;
    $canBuyGems = $priceGems > 0 && $userGemsBalance >= $finalGems;
    $isFeatured = (bool) ($item->is_featured ?? false);
    $icon = $item->icon ?? '🎁';
    $delay = ($index ?? 0) * 45;
@endphp

<div class="col-xl-3 col-lg-4 col-md-6 shop-grid-item" style="--shop-delay: {{ $delay }}ms">
    <article class="gamification-shop-widget gamification-shop-widget--{{ $variant }} {{ $isFeatured ? 'is-featured' : '' }}">
        <span class="gamification-shop-widget__glow" aria-hidden="true"></span>
        <span class="gamification-shop-widget__shine" aria-hidden="true"></span>

        @if($isFeatured)
            <span class="gamification-shop-widget__tag is-featured">
                <i class="ri-star-fill"></i> مميز
            </span>
        @endif

        @if($hasActiveDiscount)
            <span class="gamification-shop-widget__tag is-sale">-{{ $discount }}%</span>
        @endif

        <div class="gamification-shop-widget__icon-wrap">
            <span class="gamification-shop-widget__icon">{{ $icon }}</span>
        </div>

        <h6 class="gamification-shop-widget__title">{{ $item->name }}</h6>

        @if(!empty($item->description))
            <p class="gamification-shop-widget__desc">{{ Str::limit($item->description, 88) }}</p>
        @endif

        <div class="gamification-shop-widget__prices">
            @if($pricePoints > 0)
                <span class="gamification-shop-widget__price gamification-shop-widget__price--points {{ $canBuyPoints ? 'is-ok' : 'is-low' }}">
                    <i class="ri-coin-line"></i>
                    @if($hasActiveDiscount && $finalPoints < $pricePoints)
                        <s class="gamification-shop-widget__price-old">{{ number_format($pricePoints) }}</s>
                    @endif
                    {{ number_format($finalPoints) }} نقطة
                </span>
            @endif
            @if($priceGems > 0)
                <span class="gamification-shop-widget__price gamification-shop-widget__price--gems {{ $canBuyGems ? 'is-ok' : 'is-low' }}">
                    <i class="ri-vip-diamond-line"></i>
                    @if($hasActiveDiscount && $finalGems < $priceGems)
                        <s class="gamification-shop-widget__price-old">{{ number_format($priceGems) }}</s>
                    @endif
                    {{ number_format($finalGems) }} جوهرة
                </span>
            @endif
        </div>

        @if($requiredLevel > 1)
            <span class="gamification-shop-widget__level">
                <i class="ri-shield-star-line"></i> المستوى {{ $requiredLevel }}+
            </span>
        @endif

        <div class="gamification-shop-widget__actions">
            @if($pricePoints > 0)
                <button type="button"
                    class="gamification-shop-widget__buy gamification-shop-widget__buy--points shop-purchase-btn {{ $canBuyPoints ? '' : 'is-disabled' }}"
                    data-item-id="{{ $item->id }}"
                    data-method="points"
                    @disabled(!$canBuyPoints)>
                    <i class="ri-shopping-bag-3-line"></i>
                    شراء بالنقاط
                </button>
            @endif
            @if($priceGems > 0)
                <button type="button"
                    class="gamification-shop-widget__buy gamification-shop-widget__buy--gems shop-purchase-btn {{ $canBuyGems ? '' : 'is-disabled' }}"
                    data-item-id="{{ $item->id }}"
                    data-method="gems"
                    @disabled(!$canBuyGems)>
                    <i class="ri-vip-diamond-fill"></i>
                    شراء بالجواهر
                </button>
            @endif
        </div>
    </article>
</div>
