@extends('frontend.layouts.auth')

@section('title', 'تسجيل الدخول - إديوماتيك')

@section('content')
    <div class="auth-bg">
        <div class="auth-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
            <div class="shape shape-5"></div>
        </div>
        <div class="auth-grid"></div>
    </div>

    <div class="auth-container">
        <div class="auth-wrapper">
            <div class="auth-branding">
                <div class="branding-content">
                    <a href="{{ route('home') }}" class="auth-logo">
                        <i class="fas fa-graduation-cap"></i>
                        <span>إديو<span class="accent">ماتيك</span></span>
                    </a>
                    <h1 class="branding-title">مرحباً بعودتك!</h1>
                    <p class="branding-text">سجل دخولك للوصول إلى آلاف الدورات التعليمية واكتساب مهارات جديدة من أفضل المدربين.</p>
                    <div class="branding-features">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-video"></i></div>
                            <div class="feature-text">
                                <h6>دروس عالية الجودة</h6>
                                <p>فيديوهات بجودة HD مع موارد قابلة للتحميل</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-certificate"></i></div>
                            <div class="feature-text">
                                <h6>شهادات معتمدة</h6>
                                <p>احصل على شهادات إتمام معترف بها</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-users"></i></div>
                            <div class="feature-text">
                                <h6>مجتمع تفاعلي</h6>
                                <p>تواصل مع آلاف المتعلمين والمدربين</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="branding-decoration">
                    <div class="floating-card card-1"><i class="fas fa-play-circle"></i><span>+12,500 درس</span></div>
                    <div class="floating-card card-2"><i class="fas fa-star"></i><span>4.9 تقييم</span></div>
                    <div class="floating-card card-3"><i class="fas fa-user-graduate"></i><span>+50,000 طالب</span></div>
                </div>
            </div>

            <div class="auth-form-section">
                <div class="auth-form-wrapper">
                    <div class="auth-header-actions">
                        <button class="theme-toggle" type="button" aria-label="تبديل الوضع الليلي">
                            <i class="fas fa-sun"></i>
                        </button>
                    </div>

                    <div class="auth-mobile-logo">
                        <a href="{{ route('home') }}">
                            <i class="fas fa-graduation-cap"></i>
                            <span>إديو<span class="accent">ماتيك</span></span>
                        </a>
                    </div>

                    <div class="auth-form-header">
                        <h2>تسجيل الدخول</h2>
                        <p>أدخل بياناتك للوصول إلى حسابك</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="auth-form" id="loginForm">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="form-label"><i class="fas fa-envelope"></i> البريد الإلكتروني</label>
                            <div class="input-wrapper">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" required autofocus autocomplete="username">
                                <span class="input-focus-border"></span>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label"><i class="fas fa-lock"></i> كلمة المرور</label>
                            <div class="input-wrapper">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                                <button type="button" class="password-toggle" data-target="password"><i class="fas fa-eye"></i></button>
                                <span class="input-focus-border"></span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-options">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                                <span class="checkbox-label">تذكرني</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="forgot-link">نسيت كلمة المرور؟</a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-auth">
                            <span class="btn-text">تسجيل الدخول</span>
                            <span class="btn-icon"><i class="fas fa-arrow-left"></i></span>
                            <span class="btn-loader"><i class="fas fa-spinner fa-spin"></i></span>
                        </button>
                    </form>

                    <div class="auth-footer">
                        <p>ليس لديك حساب؟ <a href="{{ route('register') }}">إنشاء حساب جديد</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('frontend.layouts.partials.auth-scripts')
@endpush
