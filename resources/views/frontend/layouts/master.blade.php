<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="dark">
<head>
    @include('frontend.layouts.partials.head')
</head>
<body @hasSection('body_class') class="@yield('body_class')" @endif>
    @include('frontend.layouts.partials.topbar')
    @include('frontend.layouts.navbar')

    <div id="toast-container"></div>
    @yield('content')

    @include('frontend.layouts.footer')
    @include('frontend.layouts.partials.scripts')
</body>
</html>
