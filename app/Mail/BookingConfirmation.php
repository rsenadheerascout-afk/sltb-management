<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $bookings;

    public function __construct(Collection $bookings)
    {
        $this->bookings = $bookings;
    }

    public function envelope(): Envelope
    {
        $refs = $this->bookings->pluck('booking_ref')->join(', ');
        return new Envelope(subject: 'Booking Confirmed — ' . $refs);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.booking',
            with: [
                'bookings'  => $this->bookings,
                'firstName' => explode(' ', $this->bookings->first()->passenger_name)[0],
            ],
        );
    }
}