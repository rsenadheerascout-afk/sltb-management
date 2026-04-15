<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DutyRoster extends Model
{
    protected $fillable = ['user_id', 'schedule_id', 'duty_date', 'status'];

    protected $casts = ['duty_date' => 'date'];

    public function user()     { return $this->belongsTo(User::class); }
    public function schedule() { return $this->belongsTo(Schedule::class); }
}