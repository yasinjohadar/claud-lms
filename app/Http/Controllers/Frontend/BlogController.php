<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $categories = BlogCategory::active()->orderBy('order')->get();
        $featuredPost = BlogPost::published()
            ->with('category', 'author')
            ->where('is_featured', true)
            ->latest('published_at')
            ->first();

        if (! $featuredPost) {
            $featuredPost = BlogPost::published()
                ->with('category', 'author')
                ->latest('published_at')
                ->first();
        }

        $posts = BlogPost::published()
            ->with('category', 'author')
            ->latest('published_at')
            ->paginate(9);

        return view('frontend.pages.blog', compact('posts', 'categories', 'featuredPost'));
    }

    public function show(string $slug): View
    {
        $post = BlogPost::where('slug', $slug)
            ->published()
            ->with('category', 'author', 'tags')
            ->firstOrFail();

        $recentPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->with('category')
            ->latest('published_at')
            ->take(4)
            ->get();

        $categories = BlogCategory::active()
            ->withCount('publishedPosts')
            ->orderBy('order')
            ->get();

        $prevPost = BlogPost::published()
            ->where('published_at', '>', $post->published_at)
            ->latest('published_at')
            ->first();

        $nextPost = BlogPost::published()
            ->where('published_at', '<', $post->published_at)
            ->oldest('published_at')
            ->first();

        return view('frontend.pages.blog-detail', compact(
            'post',
            'recentPosts',
            'categories',
            'prevPost',
            'nextPost'
        ));
    }
}
