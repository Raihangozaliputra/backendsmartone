<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendanceSummary extends Notification
{
    use Queueable;

    protected $attendanceData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($attendanceData)
    {
        $this->attendanceData = $attendanceData;
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
                    ->subject('Daily Attendance Summary')
                    ->line('Here is your daily attendance summary:')
                    ->line('Status: ' . ($this->attendanceData['status'] ?? 'N/A'))
                    ->line('Check-in Time: ' . ($this->attendanceData['check_in_time'] ?? 'N/A'))
                    ->line('Check-out Time: ' . ($this->attendanceData['check_out_time'] ?? 'N/A'))
                    ->action('View Full Report', url('/'))
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
            'message' => 'Daily attendance summary',
            'attendance_data' => $this->attendanceData,
        ];
    }
}