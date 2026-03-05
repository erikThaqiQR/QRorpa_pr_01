<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class productIsReadyRes extends Notification
{
    use Queueable;
    private $orDt;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($orDt){
        $this->orDt = $orDt;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable){
        return ['database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }


    public function toDatabase($notifiable){
        return [
           'id' => $this->orDt['id'],
           'type' => $this->orDt['type'],
           'prodId' => $this->orDt['prodId'],
           'tableNr' => $this->orDt['tableNr'],
        ];
    }
}
