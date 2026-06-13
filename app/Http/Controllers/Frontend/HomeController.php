<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $blogPosts = BlogPost::published()
            ->with('category')
            ->latest('published_at')
            ->take(3)
            ->get();

        $featuredCourses = Course::published()
            ->featured()
            ->with(['category', 'instructor'])
            ->orderBy('order')
            ->orderByDesc('students_count')
            ->take(8)
            ->get();

        if ($featuredCourses->count() < 6) {
            $featuredCourses = Course::published()
                ->with(['category', 'instructor'])
                ->orderByDesc('students_count')
                ->take(8)
                ->get();
        }

        $homeCourses = $featuredCourses->take(6);
        $sliderCourses = $featuredCourses;

        $homeCategories = CourseCategory::active()
            ->featured()
            ->orderBy('order')
            ->get()
            ->each(function (CourseCategory $category) {
                $category->courses_count = $category->publishedCourses()->count();
            });

        if ($homeCategories->isEmpty()) {
            $homeCategories = CourseCategory::active()
                ->orderBy('order')
                ->take(6)
                ->get()
                ->each(function (CourseCategory $category) {
                    $category->courses_count = $category->publishedCourses()->count();
                });
        }

        return view('frontend.pages.index', compact(
            'blogPosts',
            'homeCourses',
            'sliderCourses',
            'homeCategories'
        ));
    }
}
