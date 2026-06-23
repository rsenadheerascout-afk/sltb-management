<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Chatify\Traits\UUID;

class User extends Authenticatable
{
    use HasFactory, Notifiable, UUID;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nic',
        'phone',
        'address',
        'employee_id',
        'status',
        'is_approved',
        'avatar',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
        ];
    }

    public function hasRole(string|array $roles): bool
    {
        return in_array($this->role, (array) $roles);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
    public function isApproved(): bool
    {
        return $this->is_approved;
    }

    public function sltbNotifications()
    {
        return $this->hasMany(SltbNotification::class);
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : asset('images/default-avatar.png');
    }

    // ── Chatify compatibility ────────────────────────────────────────────
    public function getAvatarForChatify(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : asset('images/default-avatar.png');
    }

    public function getActiveStatusAttribute(): int
    {
        return $this->isActive() && $this->isApproved() ? 1 : 0;
    }
}