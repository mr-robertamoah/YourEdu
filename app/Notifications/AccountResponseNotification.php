<?php

namespace App\Notifications;

use App\Http\Resources\UserAccountResource;
use App\Http\Resources\UserMiniResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountResponseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private $requestDTO){}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
            'message' => $this->requestDTO->message,
            'account' => property_exists(
                $this->requestDTO->request->requestto, 'accountType'
            ) ? new UserAccountResource(
                $this->requestDTO->request->requestto
            ) : new UserMiniResource($this->requestDTO->request->requestto),
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'requestDTO' => $this->requestDTO,
        ];
    }
}
