<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUSES = ['pending', 'paid', 'failed', 'cancelled', 'refunded'];

    protected $fillable = [
        'student_id',
        'order_number',
        'status',
        'subtotal',
        'discount',
        'tax',
        'total',
        'currency',
        'payment_method',
        'payment_reference',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'قيد الانتظار',
            'paid' => 'مدفوع',
            'failed' => 'فشل',
            'cancelled' => 'ملغى',
            'refunded' => 'مسترد',
            default => $this->status,
        };
    }

    public static function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        } while (static::where('order_number', $number)->exists());

        return $number;
    }
}
