<?php

namespace App\Notifications;

use App\Http\Resources\UserAccountResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RemoveAssessmentMarkerNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private $assessmentDTO){}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast', 'database'];
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
        if ($this->assessmentDTO->assessment->isOwner($notifiable->id)) {
            return [
                "message" => "has left your assessment with name: {$this->assessmentDTO->assessment->name}, as marker.",
                'account' => new UserAccountResource($this->assessmentDTO->assessmentable)
            ] ;
        }

        return [
            "message" => "has removed you from the assessment with name: {$this->assessmentDTO->assessment->name}, as marker",
            'account' => new UserAccountResource($this->assessmentDTO->assessment->addedby)
        ];
    }
}
