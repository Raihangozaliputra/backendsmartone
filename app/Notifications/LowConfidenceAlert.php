<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowConfidenceAlert extends Notification
{
    use Queueable;

    protected $recognitionData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($recognitionData)
    {
        $this->recognitionData = $recognitionData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Low Confidence in Face Recognition')
                    ->line('The face recognition system detected a low confidence score during your attendance process.')
                    ->line('Confidence Score: ' . ($this->recognitionData['confidence_score'] ?? 'N/A'))
                    ->line('This may indicate poor lighting conditions or an angle that made recognition difficult.')
                    ->action('View Attendance', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'Low confidence in face recognition during attendance process',
            'confidence_score' => $this->recognitionData['confidence_score'] ?? null,
            'recognition_data' => $this->recognitionData,
        ];
    }
}