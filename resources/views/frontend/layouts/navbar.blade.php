<nav class="navbar navbar-expand-xl main-nav site-navbar" id="siteNavbar">
    <div class="container site-navbar-container">
        <a class="navbar-brand site-brand" href="{{ route('home') }}">
            <span class="site-brand-mark"><i class="fas fa-graduation-cap"></i></span>
            <span class="site-brand-name">إدي<span>وماتيك</span></span>
        </a>

        <div class="site-nav-mobile-tools d-xl-none">
            <button type="button" class="site-nav-icon-btn site-nav-search-toggle" aria-label="فتح البحث" data-bs-toggle="collapse" data-bs-target="#navSearchMobile">
                <i class="fas fa-search"></i>
            </button>
            <button class="navbar-toggler site-nav-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="فتح القائمة">
                <span></span><span></span><span></span>
            </button>
        </div>

        <div class="collapse navbar-collapse site-nav-collapse" id="mainNav">
            <ul class="navbar-nav site-nav-menu">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}" data-nav="home">الرئيسية</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('courses', 'courses.show') ? 'active' : '' }}" href="{{ route('courses') }}" data-nav="courses">الكورسات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories') ? 'active' : '' }}" href="{{ route('categories') }}" data-nav="categories">التصنيفات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('blog', 'blog.show') ? 'active' : '' }}" href="{{ route('blog') }}" data-nav="blog">المدونة</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('who-we-are') ? 'active' : '' }}" href="{{ route('who-we-are') }}" data-nav="who-we-are">من نحن</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}" data-nav="about">حول</a>
                </li>
            </ul>

            <div class="site-nav-search-wrap collapse d-xl-flex" id="navSearchMobile">
                <form class="site-search-form" role="search" action="{{ route('courses') }}" method="get">
                    <div class="site-search-box" id="siteSearchBox">
                        <span class="site-search-glow" aria-hidden="true"></span>
                        <span class="site-search-ring" aria-hidden="true"></span>
                        <i class="fas fa-search site-search-icon" aria-hidden="true"></i>
                        <input type="search" class="site-search-input" id="nav-search-input" name="search" placeholder="ابحث عن كورس، مدرب، أو مهارة..." autocomplete="off" aria-label="بحث في المنصة" value="{{ request('search') }}">
                        <kbd class="site-search-kbd d-none d-lg-inline-flex" aria-hidden="true">Ctrl+K</kbd>
                        <button type="submit" class="site-search-submit" aria-label="بحث">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </div>
                    <div class="site-search-results" id="nav-search-results" hidden></div>
                </form>
            </div>

            <div class="site-nav-actions">
                <button class="site-nav-icon-btn theme-toggle" type="button" aria-label="تبديل الوضع الليلي">
                    <i class="fas fa-sun"></i>
                </button>
                <a href="{{ route('cart') }}" class="site-nav-icon-btn site-nav-cart" aria-label="سلة التسوق">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-badge">0</span>
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="site-nav-cta">
                        <i class="fas fa-user"></i>
                        <span>لوحة التحكم</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="site-nav-cta">
                        <i class="fas fa-user"></i>
                        <span>تسجيل الدخول</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
