<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('frontend.pages.about');
    }

    public function whoWeAre(): View
    {
        return view('frontend.pages.who-we-are');
    }

    public function cart(): View
    {
        return view('frontend.pages.cart');
    }

    public function checkout(): View
    {
        return view('frontend.pages.checkout');
    }

    public function lessonView(int $id): View
    {
        return view('frontend.pages.lesson-view', compact('id'));
    }
}
