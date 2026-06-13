<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {
        $this->middleware(['auth', 'check.user.active', 'ensure.student']);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.course_id' => 'required|integer|exists:courses,id',
            'simulate_payment' => 'nullable|boolean',
            'payment_method' => 'nullable|string|max:50',
        ]);

        $student = $request->user()->student;
        $order = $this->orderService->createFromCart($student, $validated['items']);

        if ($request->boolean('simulate_payment')) {
            $order = $this->orderService->markAsPaid(
                $order,
                $validated['payment_method'] ?? 'card_simulated',
                'SIM-' . $order->order_number
            );
        }

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total' => (float) $order->total,
                'currency' => $order->currency,
            ],
            'message' => $order->isPaid()
                ? 'تم تأكيد الطلب وتفعيل التسجيلات بنجاح.'
                : 'تم إنشاء الطلب. في انتظار تأكيد الدفع.',
        ]);
    }
}
