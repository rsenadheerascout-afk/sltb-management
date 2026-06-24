<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Schedule extends Model
{
    use LogsActivity;
    protected $fillable = [
        'route_id', 'bus_id', 'driver_id', 'conductor_id',
        'departure_time', 'arrival_time', 'schedule_date',
        'fare', 'status', 'created_by',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'fare'          => 'decimal:2',
    ];

    public function route()      { return $this->belongsTo(Route::class); }
    public function bus()        { return $this->belongsTo(Bus::class); }
    public function driver()     { return $this->belongsTo(User::class, 'driver_id'); }
    public function conductor()  { return $this->belongsTo(User::class, 'conductor_id'); }
    public function creator()    { return $this->belongsTo(User::class, 'created_by'); }
    public function bookings()   { return $this->hasMany(Booking::class); }
    public function rosters()    { return $this->hasMany(DutyRoster::class); }

    public function availableSeatsCount(): int
    {
        $bookedCount = $this->bookings()
            ->where('payment_status', 'paid')
            ->count();
        return $this->bus->seat_count - $bookedCount;
    }
}