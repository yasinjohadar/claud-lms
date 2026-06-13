<?php

/**
 * One-off HTML to Blade converter for frontend pages.
 * Run: php scripts/convert-html-to-blade.php
 */

$baseDir = dirname(__DIR__);
$htmlDir = $baseDir . '/resources/views/frontend/pages/html';
$pagesDir = $baseDir . '/resources/views/frontend/pages';
$authDir = $baseDir . '/resources/views/auth';

$linkMap = [
    'index.html' => "{{ route('home') }}",
    'courses.html' => "{{ route('courses') }}",
    'categories.html' => "{{ route('categories') }}",
    'blog.html' => "{{ route('blog') }}",
    'blog-detail.html' => "{{ route('blog.show', \$post->slug ?? 'slug') }}",
    'about.html' => "{{ route('about') }}",
    'who-we-are.html' => "{{ route('who-we-are') }}",
    'cart.html' => "{{ route('cart') }}",
    'checkout.html' => "{{ route('checkout') }}",
    'course-detail.html' => "{{ route('courses.show', 1) }}",
    'lesson-view.html' => "{{ route('lessons.show', 1) }}",
    'login.html' => "{{ route('login') }}",
    'register.html' => "{{ route('register') }}",
    'forgot-password.html' => "{{ route('password.request') }}",
];

$dataNavMap = [
    'index.html' => 'home',
    'courses.html' => 'courses',
    'categories.html' => 'categories',
    'blog.html' => 'blog',
    'who-we-are.html' => 'who-we-are',
    'about.html' => 'about',
];

$mainPages = [
    'index' => ['title' => 'منصة التعليم الإلكتروني', 'swiper' => true],
    'courses' => ['title' => 'الكورسات - إديوماتيك'],
    'categories' => ['title' => 'التصنيفات - إديوماتيك'],
    'blog' => ['title' => 'المدونة - إديوماتيك'],
    'blog-detail' => ['title' => 'المقال - إديوماتيك'],
    'about' => ['title' => 'حول - إديوماتيك'],
    'who-we-are' => ['title' => 'من نحن - إديوماتيك'],
    'cart' => ['title' => 'سلة التسوق - إديوماتيك'],
    'checkout' => ['title' => 'الدفع - إديوماتيك'],
    'course-detail' => ['title' => 'تفاصيل الكورس - إديوماتيك'],
    'lesson-view' => ['title' => 'مشاهدة الدرس - إديوماتيك'],
];

function replaceLinks(string $content, array $linkMap, array $dataNavMap): string
{
    foreach ($linkMap as $html => $blade) {
        $content = str_replace('href="' . $html . '"', 'href="' . $blade . '"', $content);
        $content = str_replace("href='" . $html . "'", "href='" . $blade . "'", $content);
        $content = str_replace('data-nav="' . $html . '"', 'data-nav="' . ($dataNavMap[$html] ?? str_replace('.html', '', $html)) . '"', $content);
    }

    $content = preg_replace('/href="course-detail\.html\?id=(\$\{[^}]+\}|\d+)"/', 'href="{{ route(\'courses.show\', $1) }}"', $content);
    $content = str_replace('href="css/style.css"', 'href="{{ $fa }}/css/style.css"', $content);
    $content = str_replace('href="js/main.js"', 'src="{{ $fa }}/js/main.js"', $content);
    $content = str_replace('src="js/main.js"', 'src="{{ $fa }}/js/main.js"', $content);

    return $content;
}

function extractBodyClass(string $html): string
{
    if (preg_match('/<body[^>]*class="([^"]*)"/', $html, $m)) {
        return trim(str_replace('auth-page', '', $m[1]));
    }

    return '';
}

function extractTitle(string $html): string
{
    if (preg_match('/<title>([^<]+)<\/title>/', $html, $m)) {
        return trim($m[1]);
    }

    return 'إديوماتيك';
}

function extractMainContent(string $html): string
{
    $start = strpos($html, '</nav>');
    if ($start === false) {
        return '';
    }
    $start += strlen('</nav>');

    $footerPos = strpos($html, '<footer class="site-footer">', $start);
    if ($footerPos === false) {
        $footerPos = strpos($html, '<!-- Footer -->', $start);
    }
    if ($footerPos === false) {
        return trim(substr($html, $start));
    }

    return trim(substr($html, $start, $footerPos - $start));
}

function extractAuthContent(string $html): string
{
    $start = strpos($html, '<body');
    if ($start === false) {
        return '';
    }
    $start = strpos($html, '>', $start) + 1;

    $scriptPos = strpos($html, '<script src="https://cdn.jsdelivr.net/npm/bootstrap', $start);
    if ($scriptPos === false) {
        $scriptPos = strpos($html, '</body>', $start);
    }

    return trim(substr($html, $start, $scriptPos - $start));
}

function extractInlineScripts(string $html): string
{
    $scripts = '';
    if (preg_match_all('/<script>([\s\S]*?)<\/script>/', $html, $matches)) {
        foreach ($matches[1] as $script) {
            $trimmed = trim($script);
            if ($trimmed !== '') {
                $scripts .= $script . "\n";
            }
        }
    }

    return trim($scripts);
}

function buildMainBlade(string $name, array $meta, string $content, string $inlineScripts, bool $swiper = false): string
{
    $bodyClass = $meta['bodyClass'] ?? '';
    $title = $meta['title'] ?? 'إديوماتيك';

    $blade = "@extends('frontend.layouts.master')\n\n";
    $blade .= "@section('title', '" . addslashes($title) . "')\n\n";

    if ($bodyClass !== '') {
        $blade .= "@section('body_class', '" . addslashes(trim($bodyClass)) . "')\n\n";
    }

    if ($swiper) {
        $blade .= "@push('vendor-styles')\n";
        $blade .= "<link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css\">\n";
        $blade .= "@endpush\n\n";
        $blade .= "@push('vendor-scripts')\n";
        $blade .= "<script src=\"https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js\"></script>\n";
        $blade .= "@endpush\n\n";
    }

    $blade .= "@section('content')\n";
    $blade .= $content . "\n";
    $blade .= "@endsection\n";

    if ($inlineScripts !== '') {
        $blade .= "\n@push('scripts')\n<script>\n" . $inlineScripts . "\n</script>\n@endpush\n";
    }

    return $blade;
}

foreach ($mainPages as $file => $config) {
    $htmlPath = $htmlDir . '/' . $file . '.html';
    if (! file_exists($htmlPath)) {
        echo "Skip missing: $htmlPath\n";
        continue;
    }

    $html = file_get_contents($htmlPath);
    $bodyClass = extractBodyClass($html);
    $title = $config['title'] ?? extractTitle($html);
    $content = extractMainContent($html);
    $content = replaceLinks($content, $linkMap, $dataNavMap);

    // Remove duplicate toast container (master has it)
    $content = preg_replace('/<div id="toast-container"><\/div>\s*/', '', $content, 1);

    $inlineScripts = extractInlineScripts($html);
    // Remove simulate login redirect from any leaked scripts
    $inlineScripts = preg_replace('/document\.getElementById\(\'loginForm\'\)[\s\S]*?}, 2000\);\s*\}\);/', '', $inlineScripts);

    $blade = buildMainBlade($file, [
        'bodyClass' => $bodyClass,
        'title' => $title,
    ], $content, $inlineScripts, $config['swiper'] ?? false);

    $outPath = $pagesDir . '/' . $file . '.blade.php';
    file_put_contents($outPath, $blade);
    echo "Created: $outPath\n";
}

echo "Done.\n";
