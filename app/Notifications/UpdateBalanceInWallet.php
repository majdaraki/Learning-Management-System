<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdateBalanceInWallet extends Notification
{
    use Queueable;


    public function __construct(public $student,public $balance,public $new_balance)
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
            ->subject('transfer balance')
            ->greeting('Hello ' . $this->student->first_name )
            ->line('A balance has been transferred to your wallet with the value of: ' . $this->balance)
            ->line('Your balance has become: ' . $this->new_balance)
            ->line('Thanks');
    }


    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
