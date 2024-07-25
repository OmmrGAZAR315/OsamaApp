<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;
class PushNewFCM extends Notification
{
    public $title;
    public $body;

    public function __construct($title,$body)
    {
        $this->title = $title;
        $this->body = $body;
    }
    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable): FcmMessage
    {
       
        return (new FcmMessage(notification: new FcmNotification(
                title: $this->title,
                body: $this->body
        )));
    }
}
