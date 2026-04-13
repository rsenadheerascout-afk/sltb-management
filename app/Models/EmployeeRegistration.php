<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRegistration extends Model
{
    protected $fillable = [
        'name', 'nic', 'email', 'phone', 'address',
        'applied_role', 'status', 'reviewed_by', 'review_notes',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }
}