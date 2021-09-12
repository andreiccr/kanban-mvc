<?php

namespace App\Events;

use App\Models\Card;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CardMovedToAnotherListEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $card;
    public $newPosition;
    public $targetListt;
    /**
     * Create a new event instance.
     *
     * @param Card $card
     * @param int $newPosition
     * @param int $targetListt
     */
    public function __construct(Card $card, int $newPosition, int $targetListt)
    {
        $this->card = $card;
        $this->newPosition = $newPosition;
        $this->targetListt = $targetListt;
    }

}
