{{-- Admin sidebar links for gamification — merge into admin sidebar --}}

<li class="slide has-sub">
    <a href="javascript:void(0);" class="side-menu__item">
        <i class="fe fe-award side-menu__icon"></i>
        <span class="side-menu__label">التحفيز</span>
        <i class="fe fe-chevron-down side-menu__angle"></i>
    </a>
    <ul class="slide-menu child1">
        <li><a href="{{ route('admin.gamification.dashboard') }}" class="slide-item">لوحة التحكم</a></li>
        <li><a href="{{ route('admin.gamification.points.index') }}" class="slide-item">النقاط</a></li>
        <li><a href="{{ route('admin.gamification.badges.index') }}" class="slide-item">الشارات</a></li>
        <li><a href="{{ route('admin.gamification.achievements.index') }}" class="slide-item">الإنجازات</a></li>
        <li><a href="{{ route('admin.gamification.challenges.index') }}" class="slide-item">التحديات</a></li>
        <li><a href="{{ route('admin.gamification.leaderboards.index') }}" class="slide-item">لوائح المتصدرين</a></li>
        <li><a href="{{ route('admin.gamification.shop.items.index') }}" class="slide-item">المتجر</a></li>
    </ul>
</li>
