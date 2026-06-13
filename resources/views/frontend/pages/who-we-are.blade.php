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
                    <strong class="en-text">{{ $teamStats['members'] }}+</strong>
                    <span>عضو فريق</span>
                </div>
                <div class="who-hero-stat">
                    <strong class="en-text">{{ number_format($teamStats['avg_rating'], 1) }}</strong>
                    <span>متوسط التقييم</span>
                </div>
                <div class="who-hero-stat">
                    <strong class="en-text">{{ $teamStats['courses'] }}+</strong>
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
                @forelse($teamMembers as $member)
                    <div class="col-md-6 col-lg-3">
                        @include('frontend.partials.team-card', ['member' => $member, 'showCourses' => true])
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted text-center py-5">لا يوجد أعضاء فريق للعرض حالياً.</p>
                    </div>
                @endforelse
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
