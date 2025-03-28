<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class UserCreated extends Notification
{
    public $user;

    // Constructor accepts the user object
    public function __construct($user)
    {
        $this->user = $user;
    }

    // Define the channels (we will use database here)
    public function via($notifiable)
    {
        return ['database']; // We'll save notifications to the database
    }

    // Define the data for the notification
    public function toDatabase($notifiable)
    {
        return [
            'message' => "New user account created: {$this->user->name}",
            'user_id' => $this->user->id,
            'action' => 'user_created',
        ];
    }
}
