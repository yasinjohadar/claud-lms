<?php

use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\CourseController;
use App\Http\Controllers\Frontend\CourseResourceController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LessonController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/serve/blog-image/{filename}', [BlogController::class, 'serveImage'])
    ->where('filename', '[a-zA-Z0-9_.-]+')
    ->name('blog.image');

Route::get('/serve/course-image/{filename}', [CourseController::class, 'serveImage'])
    ->where('filename', '[a-zA-Z0-9_.-]+')
    ->name('course.image');

Route::get('/serve/course-thumbnail/{filename}', [CourseController::class, 'serveThumbnail'])
    ->where('filename', '[a-zA-Z0-9_.-]+')
    ->name('course.thumbnail');

Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

Route::get('/courses/search', [CourseController::class, 'search'])->name('courses.search');
Route::get('/courses', [CourseController::class, 'index'])->name('courses');
Route::get('/courses/{slug}/resources', [CourseResourceController::class, 'index'])->name('courses.resources');
Route::get('/courses/{slug}/resources/{resourceSlug}', [CourseResourceController::class, 'show'])->name('courses.resources.show');
Route::get('/courses/{slug}/resources/{resourceSlug}/download', [CourseResourceController::class, 'download'])->name('courses.resources.download');
Route::get('/courses/{slug}/resource-file/{resource}/download', [CourseResourceController::class, 'downloadById'])->name('courses.resource-file.download');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories');

Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::controller(PageController::class)->group(function () {
    Route::get('/about', 'about')->name('about');
    Route::get('/who-we-are', 'whoWeAre')->name('who-we-are');
    Route::get('/cart', 'cart')->name('cart');
    Route::get('/checkout', 'checkout')->name('checkout');
});

Route::get('/lessons/{id}', [LessonController::class, 'show'])->name('lessons.show')->where('id', '[0-9]+');
