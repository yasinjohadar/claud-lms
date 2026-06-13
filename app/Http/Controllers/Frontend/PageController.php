<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('frontend.pages.about');
    }

    public function whoWeAre(): View
    {
        $teamMembers = TeamMember::forTeamPage()
            ->with('user')
            ->ordered()
            ->get();

        $teamStats = [
            'members' => $teamMembers->count(),
            'avg_rating' => round((float) $teamMembers->avg('rating'), 1) ?: 4.9,
            'courses' => (int) $teamMembers->sum(fn ($m) => $m->display_courses_count ?? 0),
        ];

        return view('frontend.pages.who-we-are', compact('teamMembers', 'teamStats'));
    }

    public function cart(): View
    {
        return view('frontend.pages.cart');
    }

    public function checkout(): View
    {
        return view('frontend.pages.checkout');
    }
}
