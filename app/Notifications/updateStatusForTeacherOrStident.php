<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class updateStatusForTeacherOrStident extends Notification
{
    use Queueable;


    public function __construct(public $user,public $status)
    {

    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->mailer('smtp')
            ->subject('Update status')
            ->greeting('Hello ' . $this->user->first_name )
            ->line('Your account status has become: ' . $this->status)
            ->line('Thanks');
    }


    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
