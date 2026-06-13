@extends('frontend.layouts.master')

@section('title', 'منصة التعليم الإلكتروني')

@push('vendor-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
@endpush

@push('vendor-scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush

@section('content')
<!-- Main Body -->
    <main class="home-main">
        @include('frontend.partials.hero-slider')

        <!-- Stats Section -->
        <section class="stats-section home-zone home-zone--alt section-fade-up">
            <div class="container">
                <div class="row g-3 g-md-4 stats-row">
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-card-icon"><i class="fas fa-book-open"></i></div>
                            <h2 class="stat-card-number counter en-text" data-target="500">0</h2>
                            <p class="stat-card-label">كورس متاح</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-card-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                            <h2 class="stat-card-number counter en-text" data-target="150">0</h2>
                            <p class="stat-card-label">مدرب خبير</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-card-icon"><i class="fas fa-certificate"></i></div>
                            <h2 class="stat-card-number counter en-text" data-target="85000">0</h2>
                            <p class="stat-card-label">شهادة معتمدة</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-card-icon"><i class="fas fa-video"></i></div>
                            <h2 class="stat-card-number counter en-text" data-target="15000">0</h2>
                            <p class="stat-card-label">ساعة فيديو</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Categories -->
        <section class="categories-section home-zone home-zone--base section-fade-up">
            <div class="container">
                <div class="categories-header">
                    <div class="categories-header-text">
                        <span class="categories-eyebrow">أبرز المجالات</span>
                        <h2 class="categories-title">استكشف التصنيفات</h2>
                        <p class="categories-subtitle">اختر مجالك وابدأ التعلم مع أفضل الكورسات العربية</p>
                    </div>
                    <a href="{{ route('categories') }}" class="btn categories-view-all d-none d-md-inline-flex">
                        عرض الكل <i class="fas fa-arrow-left ms-2"></i>
                    </a>
                </div>

                <div class="row g-3 g-md-4 categories-grid">
                    @foreach($homeCategories as $category)
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('courses', ['categories' => [$category->slug]]) }}" class="category-item" style="--cat-color: {{ $category->color ?? '#059669' }};">
                            <span class="category-item-icon"><i class="{{ $category->icon ?? 'fas fa-folder' }}"></i></span>
                            <span class="category-item-title">{{ $category->name }}</span>
                            <span class="category-item-meta"><span class="en-text">{{ $category->courses_count }}</span>+ كورس</span>
                            <span class="category-item-arrow"><i class="fas fa-arrow-left"></i></span>
                        </a>
                    </div>
                    @endforeach
                </div>

                <div class="text-center mt-4 d-md-none">
                    <a href="{{ route('categories') }}" class="btn categories-view-all">
                        عرض الكل <i class="fas fa-arrow-left ms-2"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Popular Courses -->
        <section class="courses-section home-zone home-zone--alt section-fade-up">
            <div class="container">
                <div class="courses-header">
                    <div class="courses-header-text">
                        <span class="courses-eyebrow">تعلم الآن</span>
                        <h2 class="courses-title">الكورسات الأكثر مبيعاً</h2>
                        <p class="courses-subtitle">اختر من بين أفضل الكورسات التي يثق بها آلاف المتعلمين</p>
                    </div>
                    <a href="{{ route('courses') }}" class="btn courses-view-all d-none d-md-inline-flex">
                        جميع الكورسات <i class="fas fa-arrow-left ms-2"></i>
                    </a>
                </div>

                <div class="row g-4" id="home-courses-container">
                    @foreach($homeCourses as $course)
                        @include('frontend.partials.course-card-grid', ['course' => $course])
                    @endforeach
                </div>

                <div class="text-center mt-4 d-md-none">
                    <a href="{{ route('courses') }}" class="btn courses-view-all">
                        جميع الكورسات <i class="fas fa-arrow-left ms-2"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Courses Slider -->
        <section class="courses-slider-section home-zone home-zone--base section-fade-up">
            <div class="container">
                <div class="courses-header">
                    <div class="courses-header-text">
                        <span class="courses-eyebrow">اكتشف المزيد</span>
                        <h2 class="courses-title">كورسات مختارة لك</h2>
                        <p class="courses-subtitle">مرّر واستكشف مجموعة واسعة من الكورسات في مختلف المجالات</p>
                    </div>
                    <a href="{{ route('courses') }}" class="btn courses-view-all d-none d-md-inline-flex">
                        جميع الكورسات <i class="fas fa-arrow-left ms-2"></i>
                    </a>
                </div>

                <div class="courses-swiper-wrap">
                    <button class="courses-swiper-btn courses-swiper-prev" aria-label="السابق">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <div class="swiper courses-swiper">
                        <div class="swiper-wrapper" id="home-courses-slider-wrapper">
                            @foreach($sliderCourses as $course)
                            <div class="swiper-slide">
                                <div class="course-card-slide">
                                    @include('frontend.partials.course-card', ['course' => $course])
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <button class="courses-swiper-btn courses-swiper-next" aria-label="التالي">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
                <div class="swiper-pagination courses-swiper-pagination"></div>
            </div>
        </section>

        <!-- Why Us / Features -->
        <section class="features-section home-zone home-zone--alt section-fade-up">
            <div class="container">
                <div class="features-header text-center">
                    <span class="features-eyebrow">مميزاتنا</span>
                    <h2 class="features-title">لماذا تختار إديوماتيك؟</h2>
                    <p class="features-subtitle mx-auto">منصة تعليمية متكاملة تجمع بين الجودة والمرونة لتمنحك تجربة تعلم استثنائية</p>
                </div>

                <div class="row g-4 features-grid">
                    <div class="col-md-6 col-lg-3">
                        <article class="feature-card" style="--feature-color: #059669;">
                            <span class="feature-card-number">01</span>
                            <span class="feature-card-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                            <h3 class="feature-card-title">نخبة المدربين</h3>
                            <p class="feature-card-desc">تعلم من خبراء الصناعة الذين يمتلكون سنوات من الخبرة العملية المتراكمة.</p>
                            <span class="feature-card-accent" aria-hidden="true"></span>
                        </article>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <article class="feature-card" style="--feature-color: #0891b2;">
                            <span class="feature-card-number">02</span>
                            <span class="feature-card-icon"><i class="fas fa-clock"></i></span>
                            <h3 class="feature-card-title">وصول مدى الحياة</h3>
                            <p class="feature-card-desc">تعلم بالسرعة التي تناسبك وفي الوقت الذي تريده، بدون أي قيود زمنية.</p>
                            <span class="feature-card-accent" aria-hidden="true"></span>
                        </article>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <article class="feature-card" style="--feature-color: #7c3aed;">
                            <span class="feature-card-number">03</span>
                            <span class="feature-card-icon"><i class="fas fa-certificate"></i></span>
                            <h3 class="feature-card-title">شهادات معتمدة</h3>
                            <p class="feature-card-desc">احصل على شهادات موثقة عند إتمامك للكورس لتعزيز سيرتك الذاتية في سوق العمل.</p>
                            <span class="feature-card-accent" aria-hidden="true"></span>
                        </article>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <article class="feature-card" style="--feature-color: #059669;">
                            <span class="feature-card-number">04</span>
                            <span class="feature-card-icon"><i class="fas fa-mobile-alt"></i></span>
                            <h3 class="feature-card-title">تعلم من أي مكان</h3>
                            <p class="feature-card-desc">منصتنا متوافقة مع كل الأجهزة لتستمر في التعلم سواء كنت في المنزل أو خارجه.</p>
                            <span class="feature-card-accent" aria-hidden="true"></span>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team Slider Section -->
        <section class="team-section home-zone home-zone--base section-fade-up">
            <div class="container">
                <div class="team-header text-center">
                    <span class="team-eyebrow">شركاء النجاح</span>
                    <h2 class="team-title">فريق الخبراء</h2>
                    <p class="team-subtitle mx-auto">مجموعة من أفضل المدربين في الوطن العربي، يجمعهم شغف نقل خبراتهم العملية.</p>
                </div>

                <div class="team-swiper-wrap">
                    <button class="team-swiper-btn team-swiper-prev" aria-label="السابق">
                        <i class="fas fa-chevron-right"></i>
                    </button>

                    <div class="swiper team-swiper">
                        <div class="swiper-wrapper">
                            @forelse($teamMembers as $member)
                                @include('frontend.partials.team-card', ['member' => $member, 'slide' => true])
                            @empty
                                <div class="swiper-slide">
                                    <article class="team-card">
                                        <p class="text-muted text-center py-5 mb-0">لا يوجد أعضاء فريق للعرض حالياً.</p>
                                    </article>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <button class="team-swiper-btn team-swiper-next" aria-label="التالي">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>

                <div class="swiper-pagination team-swiper-pagination"></div>

                <div class="text-center team-footer-cta">
                    <a href="{{ route('who-we-are') }}" class="btn team-view-all">
                        تعرف على كامل الفريق <i class="fas fa-arrow-left ms-2"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="testimonials-section home-zone home-zone--alt section-fade-up">
            <div class="container">
                <div class="testimonials-header text-center">
                    <span class="testimonials-eyebrow">قصص نجاح</span>
                    <h2 class="testimonials-title">آراء المتعلمين</h2>
                    <p class="testimonials-subtitle mx-auto">تجارب حقيقية من متعلمين غيّروا مساراتهم المهنية بفضل محتوى عملي ومحدّث</p>
                </div>

                <div class="row g-4 testimonials-grid">
                    <div class="col-md-6 col-lg-4">
                        <blockquote class="testimonial-card" style="--testimonial-color: #059669;">
                            <span class="testimonial-quote-icon" aria-hidden="true"><i class="fas fa-quote-right"></i></span>
                            <div class="testimonial-avatar">
                                <span class="testimonial-avatar-inner"><i class="fas fa-user"></i></span>
                            </div>
                            <p class="testimonial-text">بفضل الكورسات هنا، استطعت تغيير مساري المهني والعمل كمطور واجهات أمامية في شركة عالمية بوقت قياسي.</p>
                            <div class="testimonial-rating en-text" aria-label="تقييم 5 من 5">
                                <span class="testimonial-stars">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                </span>
                            </div>
                            <footer class="testimonial-author">
                                <cite class="testimonial-name">أحمد محمود</cite>
                                <span class="testimonial-role en-text">Front-end Developer</span>
                            </footer>
                        </blockquote>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <blockquote class="testimonial-card" style="--testimonial-color: #ec4899;">
                            <span class="testimonial-quote-icon" aria-hidden="true"><i class="fas fa-quote-right"></i></span>
                            <div class="testimonial-avatar">
                                <span class="testimonial-avatar-inner"><i class="fas fa-user-graduate"></i></span>
                            </div>
                            <p class="testimonial-text">منصة رائعة، المحتوى مشروح بطريقة مبسطة وعملية. الكورسات يتم تحديثها باستمرار لتطابق متطلبات السوق.</p>
                            <div class="testimonial-rating en-text" aria-label="تقييم 4.5 من 5">
                                <span class="testimonial-stars">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                                </span>
                            </div>
                            <footer class="testimonial-author">
                                <cite class="testimonial-name">سارة خليل</cite>
                                <span class="testimonial-role en-text">UI/UX Designer</span>
                            </footer>
                        </blockquote>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <blockquote class="testimonial-card" style="--testimonial-color: #0891b2;">
                            <span class="testimonial-quote-icon" aria-hidden="true"><i class="fas fa-quote-right"></i></span>
                            <div class="testimonial-avatar">
                                <span class="testimonial-avatar-inner"><i class="fas fa-laptop-code"></i></span>
                            </div>
                            <p class="testimonial-text">المشاريع العملية والدعم المستمر من المدربين ساعدوني على بناء portfolio قوي والحصول على وظيفة خلال شهرين فقط.</p>
                            <div class="testimonial-rating en-text" aria-label="تقييم 5 من 5">
                                <span class="testimonial-stars">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                </span>
                            </div>
                            <footer class="testimonial-author">
                                <cite class="testimonial-name">محمد العلي</cite>
                                <span class="testimonial-role en-text">Data Analyst</span>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter -->
        <section class="newsletter-section home-zone home-zone--base section-fade-up">
            <div class="container">
                <div class="newsletter-box">
                    <div class="row g-4 g-lg-5 align-items-center">
                        <div class="col-lg-6">
                            <div class="newsletter-content">
                                <span class="newsletter-eyebrow"><i class="fas fa-envelope-open-text"></i> ابقَ على اطلاع</span>
                                <h2 class="newsletter-title">انضم لنشرتنا البريدية</h2>
                                <p class="newsletter-desc">اشترك ليصلك آخر الأخبار عن الكورسات الجديدة، نصائح تعليمية حصرية، وعروض الخصومات قبل الجميع.</p>

                                <ul class="newsletter-benefits">
                                    <li>
                                        <span class="newsletter-benefit-icon"><i class="fas fa-tags"></i></span>
                                        <span>خصومات حصرية تصل إلى <strong>40%</strong> للمشتركين</span>
                                    </li>
                                    <li>
                                        <span class="newsletter-benefit-icon"><i class="fas fa-book-open"></i></span>
                                        <span>دروس مجانية وموارد تعليمية أسبوعياً</span>
                                    </li>
                                    <li>
                                        <span class="newsletter-benefit-icon"><i class="fas fa-bell"></i></span>
                                        <span>تنبيهات فورية عند إطلاق كورسات جديدة</span>
                                    </li>
                                </ul>

                                <div class="newsletter-trust">
                                    <div class="newsletter-trust-item">
                                        <strong>+25K</strong>
                                        <span>مشترك نشط</span>
                                    </div>
                                    <div class="newsletter-trust-divider" aria-hidden="true"></div>
                                    <div class="newsletter-trust-item">
                                        <strong>أسبوعياً</strong>
                                        <span>محتوى جديد</span>
                                    </div>
                                    <div class="newsletter-trust-divider" aria-hidden="true"></div>
                                    <div class="newsletter-trust-item">
                                        <strong>0%</strong>
                                        <span>رسائل مزعجة</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="newsletter-form-card">
                                <div class="newsletter-form-header">
                                    <span class="newsletter-form-badge">مجاني</span>
                                    <h3 class="newsletter-form-title">سجّل بريدك الآن</h3>
                                    <p class="newsletter-form-subtitle">انضم لآلاف المتعلمين واحصل على مزايا حصرية</p>
                                </div>
                                <div class="newsletter-alert" role="alert" aria-live="polite" hidden></div>
                                <form class="newsletter-form js-newsletter-form" action="{{ route('newsletter.subscribe') }}" method="post" data-source="home">
                                    @csrf
                                    <label class="visually-hidden" for="newsletter-email">البريد الإلكتروني</label>
                                    <div class="newsletter-input-wrap">
                                        <i class="fas fa-envelope newsletter-input-icon" aria-hidden="true"></i>
                                        <input type="email" id="newsletter-email" name="email" class="newsletter-input" placeholder="أدخل بريدك الإلكتروني" required autocomplete="email">
                                    </div>
                                    <button type="submit" class="newsletter-submit">
                                        اشترك الآن <i class="fas fa-arrow-left"></i>
                                    </button>
                                </form>
                                <p class="newsletter-privacy">
                                    <i class="fas fa-lock"></i>
                                    نحترم خصوصيتك — لن نشارك بريدك مع أي طرف ثالث.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Latest Blog Section -->
        <section class="blog-section home-zone home-zone--alt home-zone--last section-fade-up">
            <div class="container">
                <div class="blog-header">
                    <div class="blog-header-text">
                        <span class="blog-eyebrow">المدونة التعليمية</span>
                        <h2 class="blog-section-title">آخر المقالات والأخبار</h2>
                        <p class="blog-section-subtitle">مقالات عملية ونصائح من خبراء لتطوير مهاراتك ومواكبة أحدث التوجهات</p>
                    </div>
                    <a href="{{ route('blog') }}" class="btn blog-view-all d-none d-md-inline-flex">
                        جميع المقالات <i class="fas fa-arrow-left ms-2"></i>
                    </a>
                </div>

                <div class="blog-swiper-wrap">
                    <button class="blog-swiper-btn blog-swiper-prev" aria-label="السابق">
                        <i class="fas fa-chevron-right"></i>
                    </button>

                    <div class="swiper blog-swiper">
                        <div class="swiper-wrapper">
                            @forelse($blogPosts ?? [] as $post)
                                <div class="swiper-slide">
                                    @include('frontend.partials.blog-card', ['post' => $post])
                                </div>
                            @empty
                                <div class="swiper-slide">
                                    <article class="blog-card">
                                        <div class="blog-card-body p-4 text-center">
                                            <p class="mb-0 text-secondary">لا توجد مقالات منشورة حالياً.</p>
                                        </div>
                                    </article>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <button class="blog-swiper-btn blog-swiper-next" aria-label="التالي">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>

                <div class="swiper-pagination blog-swiper-pagination"></div>

                <div class="text-center mt-4 d-md-none">
                    <a href="{{ route('blog') }}" class="btn blog-view-all">جميع المقالات <i class="fas fa-arrow-left ms-2"></i></a>
                </div>
            </div>
        </section>

    </main>


@endsection

@push('scripts')
<script src="{{ asset('frontend/assets/js/hero-slider.js') }}"></script>
<script>
        // Init Team Swiper
        const teamSwiper = new Swiper('.team-swiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            autoplay: { delay: 4500, disableOnInteraction: false },
            pagination: { el: '.team-swiper-pagination', clickable: true },
            navigation: { nextEl: '.team-swiper-next', prevEl: '.team-swiper-prev' },
            breakpoints: {
                576:  { slidesPerView: 2 },
                992:  { slidesPerView: 3 },
                1200: { slidesPerView: 4 }
            }
        });

        // Init Blog Swiper
        const blogSwiper = new Swiper('.blog-swiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            autoplay: { delay: 5000, disableOnInteraction: false },
            pagination: { el: '.blog-swiper-pagination', clickable: true },
            navigation: { nextEl: '.blog-swiper-next', prevEl: '.blog-swiper-prev' },
            breakpoints: {
                768:  { slidesPerView: 2 },
                992:  { slidesPerView: 3 }
            }
        });

        if (document.querySelector('.courses-swiper')) {
            new Swiper('.courses-swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                speed: 600,
                grabCursor: true,
                autoplay: { delay: 4500, disableOnInteraction: false, pauseOnMouseEnter: true },
                pagination: { el: '.courses-swiper-pagination', clickable: true },
                navigation: { nextEl: '.courses-swiper-next', prevEl: '.courses-swiper-prev' },
                breakpoints: {
                    576:  { slidesPerView: 2, spaceBetween: 18 },
                    768:  { slidesPerView: 2, spaceBetween: 20 },
                    992:  { slidesPerView: 3, spaceBetween: 22 },
                    1200: { slidesPerView: 4, spaceBetween: 24 }
                }
            });
        }

        document.querySelectorAll('#home-courses-container .course-card-cart, #home-courses-slider-wrapper .course-card-cart').forEach(btn => {
            btn.addEventListener('click', () => { if (typeof addToCart === 'function') addToCart(btn); });
        });
</script>
@endpush
