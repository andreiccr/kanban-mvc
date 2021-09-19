<?php

namespace App\Events;

use App\Models\Listt;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ListtReorderedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $listt;
    public $newPosition;

    /**
     * Create a new event instance.
     *
     * @param Listt $listt
     * @param int $newPosition
     */
    public function __construct(Listt $listt, int $newPosition)
    {
        $this->listt = $listt;
        $this->newPosition = $newPosition;
    }

}
