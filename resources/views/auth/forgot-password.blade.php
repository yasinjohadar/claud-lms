@extends('frontend.layouts.auth')

@section('title', 'استعادة كلمة المرور - إديوماتيك')

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
        <div class="auth-wrapper auth-wrapper-centered">
            <div class="auth-card-only">
                <div class="auth-header-actions">
                    <button class="theme-toggle" type="button" aria-label="تبديل الوضع الليلي">
                        <i class="fas fa-sun"></i>
                    </button>
                </div>

                <div class="auth-logo-centered">
                    <a href="{{ route('home') }}">
                        <i class="fas fa-graduation-cap"></i>
                        <span>إديو<span class="accent">ماتيك</span></span>
                    </a>
                </div>

                <div class="auth-icon-wrapper">
                    <div class="auth-icon-circle">
                        <i class="fas fa-key"></i>
                    </div>
                </div>

                <div class="auth-form-header text-center">
                    <h2>نسيت كلمة المرور؟</h2>
                    <p>لا تقلق! أدخل بريدك الإلكتروني وسنرسل لك رابطاً لإعادة تعيين كلمة المرور</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success text-center" role="alert">{{ session('status') }}</div>
                @endif

                <div class="auth-steps-container">
                    <div class="auth-step active" id="step1">
                        <form method="POST" action="{{ route('password.email') }}" class="auth-form" id="forgotForm">
                            @csrf
                            <div class="form-group">
                                <label for="email" class="form-label"><i class="fas fa-envelope"></i> البريد الإلكتروني</label>
                                <div class="input-wrapper">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" required autofocus>
                                    <span class="input-focus-border"></span>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-auth btn-block">
                                <span class="btn-text">إرسال رابط الاستعادة</span>
                                <span class="btn-icon"><i class="fas fa-paper-plane"></i></span>
                                <span class="btn-loader"><i class="fas fa-spinner fa-spin"></i></span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="auth-footer text-center">
                    <a href="{{ route('login') }}" class="back-to-login">
                        <i class="fas fa-arrow-right"></i>
                        العودة لتسجيل الدخول
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('frontend.layouts.partials.auth-scripts')
@endpush
