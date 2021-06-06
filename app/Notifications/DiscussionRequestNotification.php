<?php

namespace App\Notifications;

use App\Http\Resources\UserAccountResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DiscussionRequestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private $invitationDTO){}

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
        $message = $this->invitationDTO->methodType === 'joinRequest' ? 
            "{$this->invitationDTO->joiner->name} wants to join your discussion with title: {$this->invitationDTO->request->requestable->title}" : 
            "{$this->invitationDTO->inviter->name} wants to invite you to the discussion with title: {$this->invitationDTO->request->requestable->title}";
        
        $account = $this->invitationDTO->methodType === 'joinRequest' ?
            new UserAccountResource($this->invitationDTO->joiner) : null;

        return [
            'message' => $message,
            'account' => $account,
        ];
    }
}
