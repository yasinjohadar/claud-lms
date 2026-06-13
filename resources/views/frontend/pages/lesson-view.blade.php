@extends('frontend.layouts.master')

@section('title', 'مشاهدة الدرس - إديوماتيك')

@section('content')
<!-- Lesson Viewer Container -->
    <div class="lesson-viewer-container">
        <div class="lesson-viewer-wrapper">
            <!-- Sidebar - Course Syllabus -->
            <aside class="lesson-sidebar glass-panel">
                <div class="sidebar-header">
                    <h5 class="fw-bold text-white mb-2">محتوى الكورس</h5>
                    <p class="text-secondary small mb-0">12 وحدة • 140 درس • 45 ساعة</p>
                </div>

                <div class="syllabus-content">
                    <!-- Section 1 -->
                    <div class="course-section">
                        <div class="section-header" data-bs-toggle="collapse" data-bs-target="#section1"
                            aria-expanded="true">
                            <div class="section-info">
                                <h6 class="section-title mb-0">الوحدة 1: أساسيات HTML5</h6>
                                <span class="section-meta">8 دروس • 1س 45د</span>
                            </div>
                            <i class="fas fa-chevron-down section-icon"></i>
                        </div>
                        <div class="collapse show" id="section1">
                            <ul class="lessons-list">
                                <li class="lesson-item completed">
                                    <a href="#" class="lesson-link">
                                        <div class="lesson-status">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="lesson-info">
                                            <span class="lesson-title">مقدمة في تطوير الويب</span>
                                            <div class="lesson-meta">
                                                <i class="fas fa-play-circle"></i>
                                                <span class="duration">10:45</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="lesson-item active">
                                    <a href="#" class="lesson-link">
                                        <div class="lesson-status">
                                            <i class="fas fa-play-circle"></i>
                                        </div>
                                        <div class="lesson-info">
                                            <span class="lesson-title">كتابة أول كود HTML</span>
                                            <div class="lesson-meta">
                                                <i class="fas fa-play-circle"></i>
                                                <span class="duration">15:20</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="lesson-item">
                                    <a href="#" class="lesson-link">
                                        <div class="lesson-status">
                                            <i class="fas fa-play-circle"></i>
                                        </div>
                                        <div class="lesson-info">
                                            <span class="lesson-title">هيكل صفحة HTML الأساسي</span>
                                            <div class="lesson-meta">
                                                <i class="fas fa-play-circle"></i>
                                                <span class="duration">12:30</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="lesson-item">
                                    <a href="#" class="lesson-link">
                                        <div class="lesson-status">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div class="lesson-info">
                                            <span class="lesson-title">تطبيق عملي: بناء صفحة</span>
                                            <div class="lesson-meta">
                                                <i class="fas fa-file-download"></i>
                                                <span class="duration">مرفق الملفات</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div class="course-section">
                        <div class="section-header collapsed" data-bs-toggle="collapse" data-bs-target="#section2">
                            <div class="section-info">
                                <h6 class="section-title mb-0">الوحدة 2: تنسيق CSS</h6>
                                <span class="section-meta">12 درس • 2س 30د</span>
                            </div>
                            <i class="fas fa-chevron-down section-icon"></i>
                        </div>
                        <div class="collapse" id="section2">
                            <ul class="lessons-list">
                                <li class="lesson-item locked">
                                    <a href="#" class="lesson-link">
                                        <div class="lesson-status">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                        <div class="lesson-info">
                                            <span class="lesson-title">مقدمة في CSS</span>
                                            <div class="lesson-meta">
                                                <i class="fas fa-play-circle"></i>
                                                <span class="duration">14:00</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="lesson-item locked">
                                    <a href="#" class="lesson-link">
                                        <div class="lesson-status">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                        <div class="lesson-info">
                                            <span class="lesson-title">الألوان والخلفيات</span>
                                            <div class="lesson-meta">
                                                <i class="fas fa-play-circle"></i>
                                                <span class="duration">18:45</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Section 3 -->
                    <div class="course-section">
                        <div class="section-header collapsed" data-bs-toggle="collapse" data-bs-target="#section3">
                            <div class="section-info">
                                <h6 class="section-title mb-0">الوحدة 3: JavaScript</h6>
                                <span class="section-meta">15 درس • 3س 15د</span>
                            </div>
                            <i class="fas fa-chevron-down section-icon"></i>
                        </div>
                        <div class="collapse" id="section3">
                            <ul class="lessons-list">
                                <li class="lesson-item locked">
                                    <a href="#" class="lesson-link">
                                        <div class="lesson-status">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                        <div class="lesson-info">
                                            <span class="lesson-title">مقدمة في JavaScript</span>
                                            <div class="lesson-meta">
                                                <i class="fas fa-play-circle"></i>
                                                <span class="duration">20:00</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Section 4 -->
                    <div class="course-section">
                        <div class="section-header collapsed" data-bs-toggle="collapse" data-bs-target="#section4">
                            <div class="section-info">
                                <h6 class="section-title mb-0">الوحدة 4: React.js</h6>
                                <span class="section-meta">10 دروس • 4س 00د</span>
                            </div>
                            <i class="fas fa-chevron-down section-icon"></i>
                        </div>
                        <div class="collapse" id="section4">
                            <ul class="lessons-list">
                                <li class="lesson-item locked">
                                    <a href="#" class="lesson-link">
                                        <div class="lesson-status">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                        <div class="lesson-info">
                                            <span class="lesson-title">مقدمة في React</span>
                                            <div class="lesson-meta">
                                                <i class="fas fa-play-circle"></i>
                                                <span class="duration">25:00</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="lesson-main">
                <!-- Video Player Container -->
                <div class="video-player-box glass-panel">
                    <div class="video-wrapper">
                        <div class="video-placeholder">
                            <i class="fas fa-play-circle"></i>
                            <span>اضغط للتشغيل</span>
                        </div>
                        <!-- Video Controls Overlay -->
                        <div class="video-controls">
                            <div class="progress-bar-container">
                                <div class="progress-bar">
                                    <div class="progress-filled" style="width: 35%;"></div>
                                    <div class="progress-handle"></div>
                                </div>
                                <span class="time-display">05:20 / 15:20</span>
                            </div>
                            <div class="controls-buttons">
                                <div class="controls-right">
                                    <button class="control-btn" title="السابق">
                                        <i class="fas fa-backward"></i>
                                    </button>
                                    <button class="control-btn play-btn" title="تشغيل/إيقاف">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    <button class="control-btn" title="التالي">
                                        <i class="fas fa-forward"></i>
                                    </button>
                                    <button class="control-btn" title="الصوت">
                                        <i class="fas fa-volume-up"></i>
                                    </button>
                                    <div class="volume-slider">
                                        <input type="range" min="0" max="100" value="80">
                                    </div>
                                </div>
                                <div class="controls-left">
                                    <button class="control-btn" title="سرعة التشغيل">
                                        <span class="speed-label">1x</span>
                                    </button>
                                    <button class="control-btn" title="الترجمات">
                                        <i class="fas fa-closed-captioning"></i>
                                    </button>
                                    <button class="control-btn" title="الإعدادات">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <button class="control-btn" title="ملء الشاشة">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lesson Info -->
                <div class="lesson-info-header">
                    <div class="lesson-breadcrumb">
                        <a href="{{ route('courses.show', 1) }}">الدورة الشاملة في تطوير واجهات الويب</a>
                        <i class="fas fa-chevron-left"></i>
                        <span>الوحدة 1: أساسيات HTML5</span>
                    </div>
                    <h1 class="lesson-title-main">كتابة أول كود HTML لك وإعداد بيئة العمل</h1>
                    <div class="lesson-meta-info">
                        <span class="meta-item">
                            <i class="fas fa-clock"></i>
                            15:20 دقيقة
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-eye"></i>
                            2,450 مشاهدة
                        </span>
                        <span class="meta-item completed-badge">
                            <i class="fas fa-check-circle"></i>
                            مكتمل
                        </span>
                    </div>
                </div>

                <!-- Lesson Tabs -->
                <div class="lesson-tabs-container glass-panel">
                    <ul class="nav nav-tabs lesson-tabs" id="lessonTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                data-bs-target="#description" type="button" role="tab">
                                <i class="fas fa-file-alt ms-2"></i>
                                الوصف
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="resources-tab" data-bs-toggle="tab" data-bs-target="#resources"
                                type="button" role="tab">
                                <i class="fas fa-download ms-2"></i>
                                المصادر
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="questions-tab" data-bs-toggle="tab" data-bs-target="#questions"
                                type="button" role="tab">
                                <i class="fas fa-question-circle ms-2"></i>
                                الأسئلة
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content lesson-tab-content" id="lessonTabsContent">
                        <!-- Description Tab -->
                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                            <h5 class="mb-3 text-white">عن هذا الدرس</h5>
                            <p class="text-secondary lh-lg">
                                في هذا الدرس، ستتعلم كيفية كتابة أول كود HTML لك من الصفر. سنبدأ بفهم الهيكل الأساسي
                                لصفحة الويب وكيفية إنشاء ملف HTML جديد. ستتعلم أيضاً كيفية إعداد بيئة العمل الخاصة بك
                                باستخدام محرر أكواد احترافي مثل Visual Studio Code.
                            </p>
                            <p class="text-secondary lh-lg">
                                سنغطي في هذا الدرس:
                            </p>
                            <ul class="text-secondary lesson-highlights">
                                <li>إنشاء ملف HTML الأول</li>
                                <li>فهم هيكل الصفحة الأساسي (DOCTYPE, html, head, body)</li>
                                <li>إعداد Visual Studio Code للتطوير</li>
                                <li>تثبيت الإضافات المفيدة لكتابة كود أفضل</li>
                                <li>كيفية فتح ومعاينة صفحتك في المتصفح</li>
                            </ul>
                            <div class="lesson-instructor-info mt-4">
                                <div class="instructor-avatar">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="المدرب">
                                </div>
                                <div class="instructor-details">
                                    <span class="label">المدرب</span>
                                    <span class="name">م. أحمد سعيد</span>
                                </div>
                            </div>
                        </div>

                        <!-- Resources Tab -->
                        <div class="tab-pane fade" id="resources" role="tabpanel">
                            <h5 class="mb-4 text-white">مصادر الدرس</h5>
                            <div class="resources-list">
                                <div class="resource-item glass-panel">
                                    <div class="resource-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="resource-info">
                                        <h6 class="resource-title">ملخص الدرس - PDF</h6>
                                        <span class="resource-size">2.5 MB</span>
                                    </div>
                                    <a href="#" class="btn btn-glass btn-sm">
                                        <i class="fas fa-download ms-1"></i>
                                        تحميل
                                    </a>
                                </div>
                                <div class="resource-item glass-panel">
                                    <div class="resource-icon">
                                        <i class="fas fa-file-code"></i>
                                    </div>
                                    <div class="resource-info">
                                        <h6 class="resource-title">ملفات الكود المصدرية</h6>
                                        <span class="resource-size">156 KB</span>
                                    </div>
                                    <a href="#" class="btn btn-glass btn-sm">
                                        <i class="fas fa-download ms-1"></i>
                                        تحميل
                                    </a>
                                </div>
                                <div class="resource-item glass-panel">
                                    <div class="resource-icon">
                                        <i class="fas fa-link"></i>
                                    </div>
                                    <div class="resource-info">
                                        <h6 class="resource-title">رابط Visual Studio Code</h6>
                                        <span class="resource-size">رابط خارجي</span>
                                    </div>
                                    <a href="#" class="btn btn-glass btn-sm" target="_blank">
                                        <i class="fas fa-external-link-alt ms-1"></i>
                                        فتح
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Questions Tab -->
                        <div class="tab-pane fade" id="questions" role="tabpanel">
                            <div class="questions-header mb-4">
                                <h5 class="text-white mb-0">أسئلة ومناقشات</h5>
                            </div>

                            <!-- Ask Question Form -->
                            <div class="ask-question-form glass-panel p-3 mb-4">
                                <div class="d-flex gap-3">
                                    <div class="user-avatar-sm">
                                        <img src="https://randomuser.me/api/portraits/lego/1.jpg" alt="المستخدم">
                                    </div>
                                    <div class="flex-grow-1">
                                        <textarea class="form-control bg-glass border-0" rows="2"
                                            placeholder="هل لديك سؤال؟ اكتبه هنا..."></textarea>
                                        <div class="d-flex justify-content-end mt-2">
                                            <button class="btn btn-accent btn-sm">
                                                <i class="fas fa-paper-plane ms-1"></i>
                                                نشر السؤال
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Questions List -->
                            <div class="questions-list">
                                <div class="question-item glass-panel">
                                    <div class="question-header">
                                        <div class="user-info">
                                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="المستخدم">
                                            <div class="user-details">
                                                <span class="user-name">سارة أحمد</span>
                                                <span class="question-time">منذ ساعتين</span>
                                            </div>
                                        </div>
                                        <div class="question-actions">
                                            <button class="btn btn-sm btn-glass">
                                                <i class="fas fa-thumbs-up"></i> 12
                                            </button>
                                        </div>
                                    </div>
                                    <p class="question-text">ما الفرق بين HTML و HTML5؟ وهل يجب أن أتعلم HTML العادية
                                        أولاً؟</p>
                                    <div class="question-answer">
                                        <div class="answer-badge">
                                            <i class="fas fa-check-circle text-accent"></i>
                                            إجابة المدرب
                                        </div>
                                        <p class="answer-text">HTML5 هو الإصدار الأحدث من HTML ويحتوي على عناصر جديدة
                                            وAPIs حديثة. لا داعي لتعلم الإصدارات القديمة، يمكنك البدء مباشرة بـ HTML5
                                            لأنه يتضمن كل الأساسيات.</p>
                                    </div>
                                </div>

                                <div class="question-item glass-panel">
                                    <div class="question-header">
                                        <div class="user-info">
                                            <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="المستخدم">
                                            <div class="user-details">
                                                <span class="user-name">محمد علي</span>
                                                <span class="question-time">منذ 5 ساعات</span>
                                            </div>
                                        </div>
                                        <div class="question-actions">
                                            <button class="btn btn-sm btn-glass">
                                                <i class="fas fa-thumbs-up"></i> 8
                                            </button>
                                        </div>
                                    </div>
                                    <p class="question-text">هل يمكنني استخدام محرر أكواد غير VS Code؟</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="lesson-navigation">
                    <a href="#" class="nav-btn prev-lesson glass-panel">
                        <i class="fas fa-arrow-right"></i>
                        <div class="nav-info">
                            <span class="nav-label">الدرس السابق</span>
                            <span class="nav-title">مقدمة في تطوير الويب</span>
                        </div>
                    </a>
                    <a href="#" class="nav-btn next-lesson glass-panel">
                        <div class="nav-info">
                            <span class="nav-label">الدرس التالي</span>
                            <span class="nav-title">هيكل صفحة HTML الأساسي</span>
                        </div>
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Toggle -->
    <button class="sidebar-toggle-btn d-lg-none" id="sidebarToggle">
        <i class="fas fa-list"></i>
    </button>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ $fa }}/js/main.js"></script>
    <script>
        // Sidebar toggle for mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const lessonSidebar = document.querySelector('.lesson-sidebar');

        sidebarToggle.addEventListener('click', () => {
            lessonSidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('show');
        });

        sidebarOverlay.addEventListener('click', () => {
            lessonSidebar.classList.remove('open');
            sidebarOverlay.classList.remove('show');
        });

        // Video player controls interaction
        const videoPlayerBox = document.querySelector('.video-player-box');
        const videoControls = document.querySelector('.video-controls');
        let controlsTimeout;

        videoPlayerBox.addEventListener('mousemove', () => {
            videoControls.classList.add('visible');
            clearTimeout(controlsTimeout);
            controlsTimeout = setTimeout(() => {
                videoControls.classList.remove('visible');
            }, 3000);
        });

        videoPlayerBox.addEventListener('mouseleave', () => {
            videoControls.classList.remove('visible');
        });

        // Play button toggle
        const playBtn = document.querySelector('.play-btn');
        playBtn.addEventListener('click', () => {
            const icon = playBtn.querySelector('i');
            icon.classList.toggle('fa-play');
            icon.classList.toggle('fa-pause');
        });
    </script>
</body>

</html>
@endsection

@push('scripts')
<script>
// Sidebar toggle for mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const lessonSidebar = document.querySelector('.lesson-sidebar');

        sidebarToggle.addEventListener('click', () => {
            lessonSidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('show');
        });

        sidebarOverlay.addEventListener('click', () => {
            lessonSidebar.classList.remove('open');
            sidebarOverlay.classList.remove('show');
        });

        // Video player controls interaction
        const videoPlayerBox = document.querySelector('.video-player-box');
        const videoControls = document.querySelector('.video-controls');
        let controlsTimeout;

        videoPlayerBox.addEventListener('mousemove', () => {
            videoControls.classList.add('visible');
            clearTimeout(controlsTimeout);
            controlsTimeout = setTimeout(() => {
                videoControls.classList.remove('visible');
            }, 3000);
        });

        videoPlayerBox.addEventListener('mouseleave', () => {
            videoControls.classList.remove('visible');
        });

        // Play button toggle
        const playBtn = document.querySelector('.play-btn');
        playBtn.addEventListener('click', () => {
            const icon = playBtn.querySelector('i');
            icon.classList.toggle('fa-play');
            icon.classList.toggle('fa-pause');
        });
</script>
@endpush
