<?php

namespace App\Notifications;

use App\Http\Resources\ParticipantResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RemoveDiscussionParticipantNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private $discussionDTO){}

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
        $message = "you have been removed from the discussion with title: {$this->discussionDTO->discussion->title}";
        
        if ($this->discussionDTO->discussion->isAdmin($notifiable->id) &&
            $this->discussionDTO->participant->accountable->isNotUser($notifiable->id)) {
            $message = "this participant just left the discussion with title: {$this->discussionDTO->discussion->title}";
        }

        return [
            'account' => new ParticipantResource($this->discussionDTO->participant),
            'message' => $message,
        ];
    }
}
