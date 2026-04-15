<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'passenger_id',
        'schedule_id',
        'seat_id',
        'passenger_name',
        'passenger_email',
        'passenger_phone',
        'passenger_nic',
        'amount',
        'stripe_payment_intent_id',
        'payment_status',
        'booking_ref',
        'booked_at',
    ];

    protected $casts = [
        'booked_at' => 'datetime',
        'amount'    => 'decimal:2',
    ];

    // ── Relationships ────────────────────────────────────────────────────
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────────
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function getBookerNameAttribute(): string
    {
        return $this->passenger?->name ?? $this->user?->name ?? $this->passenger_name;
    }

    // Generate booking ref: SLTB-2026-00001
    public static function generateRef(): string
    {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)
            ->orderByDesc('id')
            ->value('booking_ref');

        $next = $last ? ((int) substr($last, -5)) + 1 : 1;

        return 'SLTB-' . $year . '-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }
}