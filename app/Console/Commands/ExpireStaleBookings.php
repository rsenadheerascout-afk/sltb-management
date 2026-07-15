<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class ExpireStaleBookings extends Command
{
    protected $signature = 'bookings:expire-stale';

    protected $description = 'Marks pending bookings older than 15 minutes as failed, freeing the seat for others.';

    public function handle(): int
    {
        $count = Booking::where('payment_status', 'pending')
            ->where('created_at', '<', now()->subMinutes(15))
            ->update(['payment_status' => 'failed']);

        $this->info("Expired {$count} stale pending booking(s).");

        return self::SUCCESS;
    }
}