<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Bus extends Model
{
    use LogsActivity;
    protected $fillable = [
        'vehicle_no', 'depot_reg_no', 'brand', 'seat_count',
        'manufactured_year', 'status', 'notes',
    ];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function breakdownReports()
    {
        return $this->hasMany(BreakdownReport::class);
    }

    public function isActive(): bool       { return $this->status === 'active'; }
    public function isCondemned(): bool    { return $this->status === 'condemned'; }
    public function isSchedulable(): bool  { return $this->status === 'active'; }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'active'       => 'green',
            'needs_repair' => 'amber',
            'under_repair' => 'orange',
            'maintenance'  => 'blue',
            'condemned'    => 'red',
            default        => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active'       => 'Active',
            'needs_repair' => 'Needs Repair',
            'under_repair' => 'Under Repair',
            'maintenance'  => 'Maintenance',
            'condemned'    => 'Condemned',
            default        => ucfirst($this->status),
        };
    }
}