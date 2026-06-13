@php
    $email = $siteSettings['site_email'] ?? 'info@edumatic.com';
    $phone = $siteSettings['site_phone'] ?? '+971 50 123 4567';
    $address = $siteSettings['site_address'] ?? 'دبي، الإمارات العربية';
    $footerSocial = [
        ['url' => $siteSettings['facebook_url'] ?? '#', 'icon' => 'fab fa-facebook-f', 'label' => 'Facebook'],
        ['url' => $siteSettings['instagram_url'] ?? '#', 'icon' => 'fab fa-instagram', 'label' => 'Instagram'],
        ['url' => $siteSettings['linkedin_url'] ?? '#', 'icon' => 'fab fa-linkedin-in', 'label' => 'LinkedIn'],
        ['url' => $siteSettings['youtube_url'] ?? '#', 'icon' => 'fab fa-youtube', 'label' => 'YouTube'],
        ['url' => $siteSettings['github_url'] ?? '#', 'icon' => 'fab fa-github', 'label' => 'GitHub'],
    ];
@endphp
<footer class="site-footer">
    <div class="footer-wave">
        <svg viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C360,90 720,0 1080,40 C1260,60 1380,50 1440,40 L1440,80 L0,80 Z" fill="currentColor"/>
        </svg>
    </div>

    <div class="footer-cta-band">
        <div class="container">
            <div class="footer-cta-inner">
                <div class="footer-cta-text">
                    <span class="footer-cta-label"><i class="fas fa-crown me-2"></i>تجربة تعليمية استثنائية</span>
                    <h3 class="footer-cta-title">ابدأ رحلتك نحو <span>التميز المهني</span> اليوم</h3>
                    <p class="footer-cta-desc">انضم لأكثر من 100,000 متعلم واكتشف عالماً من المعرفة بانتظارك</p>
                </div>
                <div class="footer-cta-actions">
                    <a href="{{ route('courses') }}" class="btn footer-cta-btn-primary">استكشف الكورسات <i class="fas fa-arrow-left"></i></a>
                    <a href="{{ route('register') }}" class="btn footer-cta-btn-outline">إنشاء حساب مجاني</a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-main">
        <div class="footer-glow footer-glow-1"></div>
        <div class="footer-glow footer-glow-2"></div>
        <div class="container position-relative">
            <div class="row g-5 footer-grid">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand">
                        <a href="{{ route('home') }}" class="footer-logo">
                            <span class="footer-logo-icon"><i class="fas fa-graduation-cap"></i></span>
                            <span class="footer-logo-text">إديو<span>ماتيك</span></span>
                        </a>
                        <p class="footer-brand-desc">
                            منصة تعليمية عربية رائدة تقدّم تجربة تعلم فاخرة بمحتوى عالي الجودة، مدربين نخبة، وشهادات معتمدة — لتمكين الجيل القادم من قادة التكنولوجيا والإبداع.
                        </p>
                        <div class="footer-trust-badges">
                            <div class="footer-trust-item"><i class="fas fa-shield-alt"></i><span>دفع آمن</span></div>
                            <div class="footer-trust-item"><i class="fas fa-certificate"></i><span>شهادات معتمدة</span></div>
                            <div class="footer-trust-item"><i class="fas fa-headset"></i><span>دعم 24/7</span></div>
                        </div>
                        <div class="footer-social">
                            @foreach ($footerSocial as $social)
                                @if (! empty($social['url']) && $social['url'] !== '#')
                                    <a href="{{ $social['url'] }}" class="footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['label'] }}"><i class="{{ $social['icon'] }}"></i></a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-2 col-md-3">
                    <h6 class="footer-heading">استكشف</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('courses') }}"><i class="fas fa-chevron-left"></i> جميع الكورسات</a></li>
                        <li><a href="{{ route('categories') }}"><i class="fas fa-chevron-left"></i> التصنيفات</a></li>
                        <li><a href="{{ route('blog') }}"><i class="fas fa-chevron-left"></i> المدونة</a></li>
                        <li><a href="{{ route('who-we-are') }}"><i class="fas fa-chevron-left"></i> فريق الخبراء</a></li>
                        <li><a href="{{ route('cart') }}"><i class="fas fa-chevron-left"></i> سلة التسوق</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-2 col-md-3">
                    <h6 class="footer-heading">المنصة</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('about') }}"><i class="fas fa-chevron-left"></i> حول إديوماتيك</a></li>
                        <li><a href="{{ route('who-we-are') }}"><i class="fas fa-chevron-left"></i> من نحن</a></li>
                        <li><a href="#"><i class="fas fa-chevron-left"></i> انضم كمدرب</a></li>
                        <li><a href="#"><i class="fas fa-chevron-left"></i> الشركاء</a></li>
                        <li><a href="#"><i class="fas fa-chevron-left"></i> الوظائف</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-2 col-md-3">
                    <h6 class="footer-heading">الدعم</h6>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-chevron-left"></i> مركز المساعدة</a></li>
                        <li><a href="#"><i class="fas fa-chevron-left"></i> الأسئلة الشائعة</a></li>
                        <li><a href="#"><i class="fas fa-chevron-left"></i> سياسة الخصوصية</a></li>
                        <li><a href="#"><i class="fas fa-chevron-left"></i> الشروط والأحكام</a></li>
                        <li><a href="#"><i class="fas fa-chevron-left"></i> سياسة الاسترداد</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="footer-heading">تواصل معنا</h6>
                    <ul class="footer-contact">
                        <li>
                            <span class="footer-contact-icon"><i class="fas fa-envelope"></i></span>
                            <div>
                                <small>البريد الإلكتروني</small>
                                <a href="mailto:{{ $email }}">{{ $email }}</a>
                            </div>
                        </li>
                        <li>
                            <span class="footer-contact-icon"><i class="fas fa-phone-alt"></i></span>
                            <div>
                                <small>الهاتف</small>
                                <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="en-text" dir="ltr">{{ $phone }}</a>
                            </div>
                        </li>
                        <li>
                            <span class="footer-contact-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <div>
                                <small>المقر</small>
                                <span>{{ $address }}</span>
                            </div>
                        </li>
                    </ul>
                    <div class="footer-newsletter">
                        <p class="footer-newsletter-label">النشرة البريدية</p>
                        <div class="newsletter-alert newsletter-alert--compact" role="alert" aria-live="polite" hidden></div>
                        <form class="footer-newsletter-form js-newsletter-form" action="{{ route('newsletter.subscribe') }}" method="post" data-source="footer">
                            @csrf
                            <input type="email" name="email" placeholder="بريدك الإلكتروني" required autocomplete="email">
                            <button type="submit" aria-label="اشتراك"><i class="fas fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="footer-stats">
                <div class="footer-stat"><strong class="en-text">500+</strong><span>كورس متاح</span></div>
                <div class="footer-stat-divider"></div>
                <div class="footer-stat"><strong class="en-text">100K+</strong><span>طالب مسجل</span></div>
                <div class="footer-stat-divider"></div>
                <div class="footer-stat"><strong class="en-text">150+</strong><span>مدرب خبير</span></div>
                <div class="footer-stat-divider"></div>
                <div class="footer-stat"><strong class="en-text">4.9</strong><span>تقييم المتعلمين</span></div>
                <div class="footer-stat-divider"></div>
                <div class="footer-stat"><strong class="en-text">15K+</strong><span>ساعة محتوى</span></div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <p class="footer-copyright">&copy; {{ date('Y') }} <strong>إديوماتيك</strong>. جميع الحقوق محفوظة.</p>
                <div class="footer-payments">
                    <span class="footer-payments-label">طرق الدفع:</span>
                    <div class="footer-payment-icons">
                        <span title="Visa"><i class="fab fa-cc-visa"></i></span>
                        <span title="Mastercard"><i class="fab fa-cc-mastercard"></i></span>
                        <span title="PayPal"><i class="fab fa-cc-paypal"></i></span>
                        <span title="Apple Pay"><i class="fab fa-cc-apple-pay"></i></span>
                    </div>
                </div>
                <div class="footer-legal">
                    <a href="#">الخصوصية</a>
                    <span class="footer-legal-sep">|</span>
                    <a href="#">الشروط</a>
                    <span class="footer-legal-sep">|</span>
                    <a href="#">ملفات تعريف الارتباط</a>
                </div>
            </div>
        </div>
    </div>
</footer>
