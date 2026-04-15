<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = ['bus_id', 'seat_number', 'seat_type'];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function isBookedForSchedule(int $scheduleId): bool
    {
        return $this->bookings()
            ->where('schedule_id', $scheduleId)
            ->where('payment_status', 'paid')
            ->exists();
    }
}