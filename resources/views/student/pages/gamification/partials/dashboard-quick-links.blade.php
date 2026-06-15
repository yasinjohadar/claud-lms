@php
    $quickLinks = [
        ['route' => 'gamification.badges.index', 'icon' => 'ri-medal-line', 'color' => 'warning', 'title' => 'الشارات', 'description' => 'مجموعتك وإنجازاتك'],
        ['route' => 'gamification.challenges.index', 'icon' => 'ri-focus-3-line', 'color' => 'primary', 'title' => 'التحديات', 'description' => 'أكمل المهام اليومية'],
        ['route' => 'gamification.leaderboards.index', 'icon' => 'ri-trophy-line', 'color' => 'info', 'title' => 'المتصدرون', 'description' => 'ترتيبك بين الطلاب'],
        ['route' => 'gamification.points.index', 'icon' => 'ri-coin-line', 'color' => 'success', 'title' => 'النقاط', 'description' => 'السجل والمكافآت'],
        ['route' => 'gamification.streak.index', 'icon' => 'ri-fire-line', 'color' => 'danger', 'title' => 'السلسلة', 'description' => 'أيام النشاط المتتالية'],
        ['route' => 'gamification.shop.index', 'icon' => 'ri-shopping-bag-3-line', 'color' => 'cyan', 'title' => 'المتجر', 'description' => 'استبدل نقاطك'],
        ['route' => 'gamification.achievements.index', 'icon' => 'ri-flag-line', 'color' => 'purple', 'title' => 'الإنجازات', 'description' => 'أهدافك طويلة المدى'],
        ['route' => 'gamification.notifications.index', 'icon' => 'ri-notification-3-line', 'color' => 'secondary', 'title' => 'الإشعارات', 'description' => 'آخر التنبيهات'],
        ['route' => 'gamification.inventory.index', 'icon' => 'ri-archive-line', 'color' => 'primary', 'title' => 'المخزون', 'description' => 'عناصرك المشتراة'],
        ['route' => 'gamification.friends.index', 'icon' => 'ri-group-line', 'color' => 'info', 'title' => 'الأصدقاء', 'description' => 'شبكتك الاجتماعية'],
        ['route' => 'gamification.competitions.index', 'icon' => 'ri-sword-line', 'color' => 'danger', 'title' => 'المسابقات', 'description' => 'تنافس مع الآخرين'],
        ['route' => 'gamification.social.index', 'icon' => 'ri-chat-3-line', 'color' => 'success', 'title' => 'النشاط', 'description' => 'آخر أحداث المجتمع'],
    ];
@endphp

<div class="shortcut-section mb-4">
    <div class="shortcut-section__header mb-3">
        <h5 class="shortcut-section__title mb-1">
            <i class="ri-flashlight-line text-warning"></i>
            روابط سريعة
        </h5>
        <p class="shortcut-section__subtitle mb-0">انتقل بسرعة لأقسام التلعيب</p>
    </div>
    <div class="row g-3 shortcut-grid">
        @foreach ($quickLinks as $link)
            @include('admin.partials.ui.shortcut-card', [
                'url' => route($link['route']),
                'title' => $link['title'],
                'description' => $link['description'],
                'icon' => $link['icon'],
                'icon_color' => $link['color'],
                'col' => 'col-xl-2 col-lg-3 col-md-4 col-sm-6',
            ])
        @endforeach
    </div>
</div>
