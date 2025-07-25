<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class CallInitiated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $from;
    public $to;
    public $type; // 'audio' ou 'video'

    public function __construct(User $from, User $to, $type)
    {
        $this->from = [
            'id' => $from->id,
            'name' => $from->name,
        ];
        $this->to = $to->id;
        $this->type = $type;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('calls.' . $this->to);
    }

    public function broadcastAs()
    {
        return 'CallInitiated';
    }
}
