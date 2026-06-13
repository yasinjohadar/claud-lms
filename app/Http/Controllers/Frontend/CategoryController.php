<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = CourseCategory::active()
            ->orderBy('order')
            ->get()
            ->each(function (CourseCategory $category) {
                $category->courses_count = $category->publishedCourses()->count();
            });

        $stats = [
            'total_categories' => $categories->count(),
            'total_courses' => Course::published()->count(),
            'total_instructors' => Course::published()->distinct('instructor_id')->count('instructor_id'),
        ];

        return view('frontend.pages.categories', compact('categories', 'stats'));
    }
}
