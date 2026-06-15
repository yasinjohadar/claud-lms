<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @include('student.layouts.theme-init')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title') — إديوماتيك</title>
    @include('student.layouts.head')
    @yield('styles')
    @stack('styles')
</head>
<body class="student-lesson-page">
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('script')
    @stack('scripts')
</body>
</html>
