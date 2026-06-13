<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected EnrollmentService $enrollmentService
    ) {}

    /**
     * @param  array<int, array{course_id: int}>  $items
     */
    public function createFromCart(Student $student, array $items, string $currency = 'USD'): Order
    {
        return DB::transaction(function () use ($student, $items, $currency) {
            $courseIds = collect($items)->pluck('course_id')->unique()->values();
            $courses = Course::query()->whereIn('id', $courseIds)->get()->keyBy('id');

            $subtotal = 0;
            $order = Order::create([
                'student_id' => $student->id,
                'order_number' => Order::generateOrderNumber(),
                'status' => 'pending',
                'subtotal' => 0,
                'discount' => 0,
                'tax' => 0,
                'total' => 0,
                'currency' => $currency,
            ]);

            foreach ($courseIds as $courseId) {
                $course = $courses->get($courseId);
                if (! $course) {
                    continue;
                }

                $price = (float) $course->price;
                $subtotal += $price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'unit_price' => $price,
                    'quantity' => 1,
                ]);

                $this->enrollmentService->grant(
                    $student,
                    $course,
                    'purchase',
                    null,
                    $order,
                    'pending'
                );
            }

            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);

            return $order->fresh(['items']);
        });
    }

    public function markAsPaid(Order $order, ?string $paymentMethod = null, ?string $paymentReference = null): Order
    {
        return DB::transaction(function () use ($order, $paymentMethod, $paymentReference) {
            $order->update([
                'status' => 'paid',
                'payment_method' => $paymentMethod ?? $order->payment_method ?? 'manual',
                'payment_reference' => $paymentReference ?? $order->payment_reference,
                'paid_at' => now(),
            ]);

            $this->enrollmentService->activateFromOrder($order->fresh(['items.course', 'student']));

            return $order->fresh(['items', 'enrollments']);
        });
    }

    public function cancel(Order $order): Order
    {
        return DB::transaction(function () use ($order) {
            $order->update(['status' => 'cancelled']);

            foreach ($order->enrollments as $enrollment) {
                if ($enrollment->status === 'pending') {
                    $this->enrollmentService->cancel($enrollment);
                }
            }

            return $order->fresh();
        });
    }
}
