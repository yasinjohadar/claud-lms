<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
            'source' => 'nullable|string|max:50',
        ], [
            'email.required' => 'يرجى إدخال بريدك الإلكتروني.',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح.',
            'email.unique' => 'هذا البريد مسجل مسبقاً في النشرة.',
        ]);

        NewsletterSubscriber::create([
            'email' => $validated['email'],
            'source' => $request->input('source', 'general'),
        ]);

        return redirect()->back()
            ->with('newsletter_success', 'تم الاشتراك بنجاح. شكراً لك!');
    }

    public function unsubscribe(string $token): RedirectResponse
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        if (! $subscriber) {
            return redirect()->route('home')
                ->with('error', 'رابط إلغاء الاشتراك غير صالح أو منتهي الصلاحية.');
        }

        $subscriber->unsubscribe();

        return redirect()->route('home')
            ->with('newsletter_success', 'تم إلغاء اشتراكك في النشرة البريدية بنجاح.');
    }
}
