@extends('frontend.layouts.auth')

@section('title', 'إنشاء حساب - إديوماتيك')

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
                    <h1 class="branding-title">انضم إلينا اليوم!</h1>
                    <p class="branding-text">أنشئ حسابك وابدأ رحلتك التعليمية مع أفضل الدورات والمدربين على مستوى المنطقة.</p>
                    <div class="branding-features">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-infinity"></i></div>
                            <div class="feature-text">
                                <h6>وصول غير محدود</h6>
                                <p>اشترك مرة واحدة واحصل على وصول دائم</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-rocket"></i></div>
                            <div class="feature-text">
                                <h6>تعلم بالسرعة الخاصة بك</h6>
                                <p>درس في أي وقت ومن أي مكان</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-headset"></i></div>
                            <div class="feature-text">
                                <h6>دعم فني متواصل</h6>
                                <p>فريق دعم متاح على مدار الساعة</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="branding-decoration">
                    <div class="floating-card card-1"><i class="fas fa-book-open"></i><span>+500 كورس</span></div>
                    <div class="floating-card card-2"><i class="fas fa-trophy"></i><span>شهادات معتمدة</span></div>
                    <div class="floating-card card-3"><i class="fas fa-percent"></i><span>خصم 30%</span></div>
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
                        <h2>إنشاء حساب جديد</h2>
                        <p>أدخل بياناتك لإنشاء حسابك الشخصي</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="auth-form" id="registerForm">
                        @csrf

                        <div class="form-group">
                            <label for="name" class="form-label"><i class="fas fa-user"></i> الاسم الكامل</label>
                            <div class="input-wrapper">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="أحمد محمد" required autofocus autocomplete="name">
                                <span class="input-focus-border"></span>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label"><i class="fas fa-envelope"></i> البريد الإلكتروني</label>
                            <div class="input-wrapper">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" required autocomplete="username">
                                <span class="input-focus-border"></span>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label"><i class="fas fa-lock"></i> كلمة المرور</label>
                            <div class="input-wrapper">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required autocomplete="new-password">
                                <button type="button" class="password-toggle" data-target="password"><i class="fas fa-eye"></i></button>
                                <span class="input-focus-border"></span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label"><i class="fas fa-lock"></i> تأكيد كلمة المرور</label>
                            <div class="input-wrapper">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password">
                                <button type="button" class="password-toggle" data-target="password_confirmation"><i class="fas fa-eye"></i></button>
                                <span class="input-focus-border"></span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-auth">
                            <span class="btn-text">إنشاء الحساب</span>
                            <span class="btn-icon"><i class="fas fa-user-plus"></i></span>
                            <span class="btn-loader"><i class="fas fa-spinner fa-spin"></i></span>
                        </button>
                    </form>

                    <div class="auth-footer">
                        <p>لديك حساب بالفعل؟ <a href="{{ route('login') }}">تسجيل الدخول</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('frontend.layouts.partials.auth-scripts')
@endpush
