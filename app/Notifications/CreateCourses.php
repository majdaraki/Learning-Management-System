<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateCourses extends Notification
{
    use Queueable;


    public function __construct(public $student,public $course,public $teacher)
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
            ->subject('Create Course')
            ->greeting('Hello ' . $this->student->first_name )
            ->line('A new course has been created by teacher: ' . $this->teacher->first_name)
            ->line('Course Name: ' . $this->course)
            ->line('Thanks');
    }


    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
