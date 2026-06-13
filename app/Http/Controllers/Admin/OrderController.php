<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {
        $this->middleware('auth');
        $this->middleware('permission:order-manage');
    }

    public function index(Request $request): View
    {
        $query = Order::query()->with(['student.user', 'items']);

        if ($request->filled('status') && in_array($request->status, Order::STATUSES, true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('student.user', fn ($uq) => $uq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $orders = $query->orderByDesc('id')->paginate(20)->withQueryString();

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'paid' => Order::where('status', 'paid')->count(),
        ];

        return view('admin.pages.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order): View
    {
        $order->load(['student.user', 'items.course', 'enrollments.course']);

        return view('admin.pages.orders.show', compact('order'));
    }

    public function markPaid(Request $request, Order $order): RedirectResponse
    {
        if ($order->isPaid()) {
            return back()->with('error', 'الطلب مدفوع مسبقاً');
        }

        $validated = $request->validate([
            'payment_method' => 'nullable|string|max:50',
            'payment_reference' => 'nullable|string|max:100',
        ]);

        $this->orderService->markAsPaid(
            $order,
            $validated['payment_method'] ?? 'manual',
            $validated['payment_reference'] ?? null
        );

        return back()->with('success', 'تم تأكيد الدفع وتفعيل التسجيلات');
    }
}
