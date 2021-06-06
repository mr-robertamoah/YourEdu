<?php

namespace App\Notifications;

use App\Http\Resources\ParticipantResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RemoveAssessmentParticipantNotification extends Notification
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
        $message = "you have been removed from the assessment with name: {$this->assessmentDTO->assessment->name}";
        
        if ($this->assessmentDTO->assessment->isOwner($notifiable->id) &&
            $this->assessmentDTO->participant->accountable->isNotUser($notifiable->id)) {
            $message = "this participant just left the assessment with name: {$this->assessmentDTO->assessment->name}";
        }

        return [
            'account' => new ParticipantResource($this->assessmentDTO->participant),
            'message' => $message,
        ];
    }
}
