<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="dark">
<head>
    @include('frontend.layouts.partials.head')
</head>
<body class="auth-page @yield('body_class')">
    @yield('content')
    @include('frontend.layouts.partials.scripts')
</body>
</html>
