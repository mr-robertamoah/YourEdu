<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DiscussionInvitationNotification extends Notification
{
    use Queueable;

    public $requestId;
    public $name;
    public $title;
    public $admin;
    public $discussionId;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($requestId,$name, $title,$admin,$discussionId)
    {
        $this->requestId = $requestId;
        $this->discussionId = $discussionId;
        $this->name = $name;
        $this->title = $title;
        $this->admin = $admin;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
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
    public function toArray($notifiable)
    {
        return [
            'account' => $this->admin,
            'requestId' => $this->requestId,
            'discussionId' => $this->discussionId,
            'message' => "{$this->name} has invited you to join a discussion with tittle {$this->title}",
        ];
    }
}
