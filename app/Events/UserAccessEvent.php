<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserAccessEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * 访问的用户id
     * @var user
     */
    public $user;

    /**
     * 访问用户登录的ip地址
     * @var ip
     */
    public $ip;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $ip)
    {
        $this->user = $user;
        $this->ip = $ip;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
