<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusUpdated extends Notification
{
    public function __construct(public Booking $booking, public string $message)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("SIPERU - Pengajuan {$this->booking->code}")
            ->greeting("Halo, {$notifiable->name}")
            ->line($this->message)
            ->line("Ruangan: {$this->booking->room->name}")
            ->line('Jadwal: ' . $this->booking->date->locale('id')->translatedFormat('d F Y') . ', ' . $this->booking->time_range)
            ->action('Buka SIPERU', route('login'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'code' => $this->booking->code,
            'room' => $this->booking->room->name,
            'message' => $this->message,
        ];
    }
}
