<?php

namespace App\Listeners;

use App\Events\CardDeletedEvent;
use App\Events\CardMovedToAnotherListEvent;
use App\Events\CardReorderedEvent;
use App\Models\Listt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReorderCardsInListtListener
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if(get_class($event) == CardDeletedEvent::class) {
            foreach (Listt::find($event->card["listtId"])->cards as $card) {
                if($card->position > $event->card["position"]) {
                    $card->position--;
                    $card->save();
                }
            }
        }
        else if(get_class($event) == CardMovedToAnotherListEvent::class) {
            foreach($event->card->listt->cards as $card) {
                if($card->id == $event->card->id)
                    continue;

                if($card->position > $event->card->position) {
                    $card->position--;
                    $card->save();
                }
            }

            foreach(Listt::find($event->targetListt)->cards as $card) {
                if($card->position >= $event->newPosition) {
                    $card->position++;
                    $card->save();
                }
            }
        }
        else if(get_class($event) == CardReorderedEvent::class) {
            if ($event->card->position > $event->newPosition) {
                $cardWasMovedUp = true;
            } else {
                $cardWasMovedUp = false;
            }

            foreach ($event->card->listt->cards as $card) {
                if ($card->id == $event->card->id)
                    continue;

                //Moving card up the list
                if ($cardWasMovedUp) {
                    if ($card->position >= $event->newPosition && $card->position < $event->card->position) {
                        $card->position++;
                        $card->save();
                    }
                } //Moving card down the list
                else {
                    if ($card->position > $event->card->position && $card->position <= $event->newPosition) {
                        $card->position--;
                        $card->save();
                    }
                }

            }
        }
    }
}
