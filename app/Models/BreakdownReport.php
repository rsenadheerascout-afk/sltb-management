<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreakdownReport extends Model
{
    use \App\Traits\LogsActivity;

    protected $fillable = [
        'bus_id', 'reported_by', 'latitude', 'longitude',
        'description', 'photo_path', 'status',
        'approved_by', 'reject_reason',
        'response_action', 'response_notes', 'responded_by',
        'replacement_bus_id', 'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'latitude'    => 'decimal:7',
        'longitude'   => 'decimal:7',
    ];

    public function bus()              { return $this->belongsTo(Bus::class); }
    public function reportedBy()       { return $this->belongsTo(User::class, 'reported_by'); }
    public function approvedBy()       { return $this->belongsTo(User::class, 'approved_by'); }
    public function respondedBy()      { return $this->belongsTo(User::class, 'responded_by'); }
    public function replacementBus()   { return $this->belongsTo(Bus::class, 'replacement_bus_id'); }

    public function isPending(): bool     { return $this->status === 'pending'; }
    public function isApproved(): bool    { return in_array($this->status, ['approved', 'in_progress', 'resolved']); }
    public function isResolved(): bool    { return $this->status === 'resolved'; }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'     => 'bg-amber-50 text-amber-700',
            'approved'    => 'bg-blue-50 text-blue-700',
            'rejected'    => 'bg-red-50 text-red-600',
            'in_progress' => 'bg-purple-50 text-purple-700',
            'resolved'    => 'bg-green-50 text-green-700',
            default       => 'bg-gray-100 text-gray-500',
        };
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }
}