<?php

namespace App\Notifications;

use App\Http\Resources\UserAccountResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DiscussionInvitationResponseNotification extends Notification
{
    use Queueable;

    public $title;
    public $account;
    public $accountType;
    public $accountName;
    public $action;
    public $discussionId;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title,$action,$discussionId,$account)
    {
        $this->title = $title;
        $this->action = $action;
        $this->account = new UserAccountResource($account);
        $this->accountType = getAccountString(get_class($account));
        $this->accountName = $account->name ? $account->name : $account->company_name;
        $this->discussionId = $discussionId;
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
            'message' => "{$this->accountName}({$this->accountType}) {$this->action} your invitation to join your discussion with title: {$this->title}",
            'account' => $this->account,
        ];
    }
}
