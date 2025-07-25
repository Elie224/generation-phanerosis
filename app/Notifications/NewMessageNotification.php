<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\User;
use App\Models\Message;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $fromUser;
    public $message;

    public function __construct(User $fromUser, Message $message)
    {
        $this->fromUser = $fromUser;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'from_user_id' => $this->fromUser->id,
            'from_user_name' => $this->fromUser->name,
            'message_content' => $this->message->content,
            'message' => 'Nouveau message de ' . $this->fromUser->name,
        ];
    }
}
