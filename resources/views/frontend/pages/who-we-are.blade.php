@extends('frontend.layouts.master')

@section('title', 'من نحن - إديوماتيك')

@section('body_class', 'who-we-are-page')

@section('content')
<!-- Page Hero -->
    <section class="who-we-are-hero section-fade-up">
        <div class="container who-we-are-hero-inner">
            <span class="who-we-are-hero-eyebrow"><i class="fas fa-users"></i> نخبة المدربين</span>
            <h1 class="who-we-are-hero-title">تعرف على فريق الخبراء</h1>
            <p class="who-we-are-hero-desc">نخبة من أفضل المدربين في الوطن العربي، يجمعهم شغف واحد — نقل الخبرة الحقيقية إليك.</p>

            <div class="who-we-are-hero-stats">
                <div class="who-hero-stat">
                    <strong class="en-text">150+</strong>
                    <span>مدرب</span>
                </div>
                <div class="who-hero-stat">
                    <strong class="en-text">4.9</strong>
                    <span>متوسط التقييم</span>
                </div>
                <div class="who-hero-stat">
                    <strong class="en-text">500+</strong>
                    <span>كورس</span>
                </div>
            </div>

            <nav class="who-we-are-breadcrumb" aria-label="breadcrumb">
                <a href="{{ route('home') }}">الرئيسية</a>
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
                <span>من نحن</span>
            </nav>
        </div>
    </section>

    <main class="container who-we-are-main">
        <header class="who-we-are-intro section-fade-up">
            <span class="who-we-are-intro-eyebrow">فريق إديوماتيك</span>
            <h2 class="who-we-are-intro-title">مدربون يصنعون الفرق</h2>
            <p class="who-we-are-intro-desc">خبراء معتمدون في البرمجة، التصميم، التسويق، والذكاء الاصطناعي — يقدّمون محتوى عملياً يرافقك خطوة بخطوة.</p>
        </header>

        <!-- Team Grid -->
        <section class="team-section who-we-are-team section-fade-up">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <article class="team-card h-100" style="--team-color: #059669;">
                        <div class="team-card-avatar">
                            <span class="team-card-avatar-inner"><i class="fas fa-user-tie"></i></span>
                        </div>
                        <h3 class="team-card-name">م. أحمد سعيد</h3>
                        <p class="team-card-role en-text">Senior Front-end Engineer</p>
                        <div class="team-card-rating en-text" aria-label="تقييم 4.9 من 5">
                            <span class="team-rating-stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </span>
                            <span class="team-rating-value">4.9</span>
                        </div>
                        <p class="team-card-courses en-text"><i class="fas fa-play-circle"></i> 14 كورس</p>
                        <p class="team-card-bio">خبير في بناء وتطوير واجهات الويب بأحدث التقنيات مع خبرة 10 سنوات.</p>
                        <div class="team-card-social">
                            <a href="#" class="team-social-btn" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                            <a href="#" class="team-social-btn" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </article>
                </div>

                <div class="col-md-6 col-lg-3">
                    <article class="team-card h-100" style="--team-color: #ec4899;">
                        <div class="team-card-avatar">
                            <span class="team-card-avatar-inner"><i class="fas fa-paint-brush"></i></span>
                        </div>
                        <h3 class="team-card-name">سارة محمد</h3>
                        <p class="team-card-role en-text">UX/UI Designer</p>
                        <div class="team-card-rating en-text" aria-label="تقييم 4.8 من 5">
                            <span class="team-rating-stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                            </span>
                            <span class="team-rating-value">4.8</span>
                        </div>
                        <p class="team-card-courses en-text"><i class="fas fa-play-circle"></i> 8 كورسات</p>
                        <p class="team-card-bio">متخصصة في تصميم تجربة المستخدم وتصميم الويب وتطبيقات الجوال.</p>
                        <div class="team-card-social">
                            <a href="#" class="team-social-btn" aria-label="Behance"><i class="fab fa-behance"></i></a>
                            <a href="#" class="team-social-btn" aria-label="Dribbble"><i class="fab fa-dribbble"></i></a>
                        </div>
                    </article>
                </div>

                <div class="col-md-6 col-lg-3">
                    <article class="team-card h-100" style="--team-color: #7c3aed;">
                        <div class="team-card-avatar">
                            <span class="team-card-avatar-inner"><i class="fas fa-robot"></i></span>
                        </div>
                        <h3 class="team-card-name">عمر مصطفى</h3>
                        <p class="team-card-role en-text">Machine Learning Engineer</p>
                        <div class="team-card-rating en-text" aria-label="تقييم 4.7 من 5">
                            <span class="team-rating-stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                            </span>
                            <span class="team-rating-value">4.7</span>
                        </div>
                        <p class="team-card-courses en-text"><i class="fas fa-play-circle"></i> 11 كورس</p>
                        <p class="team-card-bio">شغوف بعلوم البيانات والذكاء الاصطناعي، يربط النظريات بالتطبيق العملي.</p>
                        <div class="team-card-social">
                            <a href="#" class="team-social-btn" aria-label="GitHub"><i class="fab fa-github"></i></a>
                            <a href="#" class="team-social-btn" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </article>
                </div>

                <div class="col-md-6 col-lg-3">
                    <article class="team-card h-100" style="--team-color: #f59e0b;">
                        <div class="team-card-avatar">
                            <span class="team-card-avatar-inner"><i class="fas fa-chart-pie"></i></span>
                        </div>
                        <h3 class="team-card-name">طارق زياد</h3>
                        <p class="team-card-role en-text">Business Analyst & Marketer</p>
                        <div class="team-card-rating en-text" aria-label="تقييم 4.5 من 5">
                            <span class="team-rating-stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                            </span>
                            <span class="team-rating-value">4.5</span>
                        </div>
                        <p class="team-card-courses en-text"><i class="fas fa-play-circle"></i> 6 كورسات</p>
                        <p class="team-card-bio">خبرة في إدارة الأعمال والتسويق الرقمي بتركيز على تحقيق النمو والمبيعات.</p>
                        <div class="team-card-social">
                            <a href="#" class="team-social-btn" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="team-social-btn" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="team-page-cta section-fade-up">
            <div class="team-page-cta-inner">
                <span class="team-page-cta-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                <h2>هل أنت مدرب محترف؟</h2>
                <p>انضم إلى مجتمع الخبراء وساهم في نشر المعرفة لآلاف الطلاب من مختلف أنحاء العالم العربي.</p>
                <a href="{{ route('about') }}" class="team-page-cta-btn">تواصل معنا للانضمام <i class="fas fa-arrow-left"></i></a>
            </div>
        </section>
    </main>


@endsection
