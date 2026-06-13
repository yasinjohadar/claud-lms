<?php

$pagesDir = dirname(__DIR__) . '/resources/views/frontend/pages';
$files = glob($pagesDir . '/*.blade.php');

$replacements = [
    'href="categories.html' => 'href="{{ route(\'categories\') }}',
    'href="courses.html"' => 'href="{{ route(\'courses\') }}"',
    'href="register.html"' => 'href="{{ route(\'register\') }}"',
    'href="about.html"' => 'href="{{ route(\'about\') }}"',
    'href="who-we-are.html"' => 'href="{{ route(\'who-we-are\') }}"',
    'href="cart.html"' => 'href="{{ route(\'cart\') }}"',
    'href="checkout.html"' => 'href="{{ route(\'checkout\') }}"',
    'href="blog.html"' => 'href="{{ route(\'blog\') }}"',
    'href="index.html"' => 'href="{{ route(\'home\') }}"',
    'href="login.html"' => 'href="{{ route(\'login\') }}"',
    'href="lesson-view.html"' => 'href="{{ route(\'lessons.show\', 1) }}"',
    '    <!-- Footer -->' => '',
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    $content = preg_replace('/href="course-detail\.html\?id=(\d+)"/', 'href="{{ route(\'courses.show\', $1) }}"', $content);
    file_put_contents($file, $content);
    echo "Fixed: $file\n";
}

echo "Done.\n";
