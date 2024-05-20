<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class verfication_code extends Notification
{
    use Queueable;


    public function __construct(public $user,public $verificationCode)
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
            ->subject('Verification Code')
            ->greeting('Hello ' . $this->user->first_name )
            ->line('Here is your verification code: ' . $this->verificationCode)
            ->line('Please use this code to complete your operation.');
    }


    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
