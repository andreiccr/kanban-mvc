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

class CardDeletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $card;

    /**
     * Create a new event instance.
     *
     * @param $deletedCard
     */
    public function __construct($deletedCard)
    {
        $this->card = $deletedCard;
    }

}
