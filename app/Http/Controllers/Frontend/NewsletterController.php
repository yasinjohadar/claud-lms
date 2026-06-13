<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'source' => 'nullable|string|max:50',
        ], [
            'email.required' => 'يرجى إدخال بريدك الإلكتروني.',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح.',
        ]);

        $email = strtolower(trim($validated['email']));
        $source = $request->input('source', 'general');
        $existing = NewsletterSubscriber::where('email', $email)->first();

        if ($existing?->is_active) {
            $message = 'هذا البريد مسجّل مسبقاً في النشرة البريدية.';

            if ($this->wantsJsonResponse($request)) {
                throw ValidationException::withMessages(['email' => $message]);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => $message]);
        }

        if ($existing) {
            $existing->update([
                'is_active' => true,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
                'source' => $source,
            ]);
        } else {
            NewsletterSubscriber::create([
                'email' => $email,
                'source' => $source,
            ]);
        }

        $message = 'تم الاشتراك بنجاح! ستصلك آخر الأخبار والعروض على بريدك قريباً.';

        if ($this->wantsJsonResponse($request)) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->back()
            ->with('newsletter_success', $message);
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

    private function wantsJsonResponse(Request $request): bool
    {
        return $request->ajax() || $request->wantsJson();
    }
}
