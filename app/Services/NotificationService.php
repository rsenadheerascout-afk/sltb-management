<?php

namespace App\Services;

use App\Models\SltbNotification;
use App\Models\User;

class NotificationService
{
    public function send(int $userId, string $title, string $message, string $type = 'info', ?string $link = null): void
    {
        SltbNotification::create([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'type'    => $type,
            'link'    => $link,
        ]);
    }

    public function sendToRole(string $role, string $title, string $message, string $type = 'info', ?string $link = null): void
    {
        User::where('role', $role)
            ->where('status', 'active')
            ->where('is_approved', true)
            ->each(fn($user) => $this->send($user->id, $title, $message, $type, $link));
    }

    public function sendToRoles(array $roles, string $title, string $message, string $type = 'info', ?string $link = null): void
    {
        User::whereIn('role', $roles)
            ->where('status', 'active')
            ->where('is_approved', true)
            ->each(fn($user) => $this->send($user->id, $title, $message, $type, $link));
    }
}