<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewStatusChanged extends Notification
{
    use Queueable;

    protected $review;

    /**
     * Create a new notification instance.
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = match($this->review->status) {
            'approved' => 'disetujui',
            'rejected' => 'ditolak',
            default => 'diperbarui',
        };

        $reviewableType = match($this->review->reviewable_type) {
            'App\Models\Destination' => 'destinasi',
            'App\Models\Accommodation' => 'akomodasi',
            'App\Models\Transportation' => 'transportasi',
            'App\Models\Culinary' => 'kuliner',
            'App\Models\CreativeEconomy' => 'ekonomi kreatif',
            'App\Models\TravelPackage' => 'paket wisata',
            default => 'item',
        };

        $reviewableName = $this->review->reviewable ? $this->review->reviewable->name : '';

        return (new MailMessage)
            ->subject("Ulasan Anda telah $status")
            ->greeting("Halo {$notifiable->name},")
            ->line("Ulasan Anda untuk $reviewableType \"$reviewableName\" telah $status.")
            ->line("Rating: " . str_repeat('★', $this->review->rating))
            ->line("Komentar: {$this->review->comment}")
            ->action('Lihat Ulasan', url('/reviews/my-reviews'))
            ->line('Terima kasih telah memberikan ulasan!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'review_id' => $this->review->id,
            'status' => $this->review->status,
            'message' => "Ulasan Anda telah " . match($this->review->status) {
                'approved' => 'disetujui',
                'rejected' => 'ditolak',
                default => 'diperbarui',
            },
        ];
    }
}
