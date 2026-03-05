<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class newMsgFromQRorpa extends Notification
{
    use Queueable;
    private $theMsg;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($theMsg){
        $this->theMsg = $theMsg;
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

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable){
        return [
            'type' => $this->theMsg['type'],
            'avid' => $this->theMsg['avid'],
            'avChatid' => $this->theMsg['avChatid'],
            'byId' => $this->theMsg['byId'],
            'forId' => $this->theMsg['forId'],
            'theMsg' => $this->theMsg['theMsg'],
        ];
    }
}
