<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'لوحة الطالب') — إديوماتيك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #e2e8f0; min-height: 100vh; }
        .student-nav { background: #1e293b; border-bottom: 1px solid #334155; }
        .student-nav .nav-link { color: #94a3b8; }
        .student-nav .nav-link.active, .student-nav .nav-link:hover { color: #fff; }
        .student-stat-card { background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 1.25rem; }
        .student-stat-card .value { font-size: 1.75rem; font-weight: 700; color: #38bdf8; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg student-nav">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="{{ route('student.dashboard') }}">
                <i class="ri-graduation-cap-line me-1"></i> لوحة الطالب
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#studentNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="studentNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}"
                           href="{{ route('student.dashboard') }}">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.courses.*') ? 'active' : '' }}"
                           href="{{ route('student.courses.index') }}">كورساتي</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-secondary small">{{ auth()->user()->name }}</span>
                    <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light">الموقع</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">خروج</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
