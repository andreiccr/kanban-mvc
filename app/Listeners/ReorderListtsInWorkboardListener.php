<?php

namespace App\Listeners;

use App\Events\ListtDeletedEvent;
use App\Events\ListtReorderedEvent;
use App\Models\Workboard;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReorderListtsInWorkboardListener
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if(get_class($event) == ListtDeletedEvent::class) {
            foreach (Workboard::find($event->listt["boardId"])->listts as $listt) {
                if($listt->position > $event->listt["position"]) {
                    $listt->position--;
                    $listt->save();
                }
            }
        }
        else if(get_class($event) == ListtReorderedEvent::class) {
            if ($event->listt->position > $event->newPosition) {
                $listtWasMovedUp = true;
            } else {
                $listtWasMovedUp = false;
            }

            foreach ($event->listt->workboard->listts as $listt) {
                if ($listt->id == $event->listt->id)
                    continue;

                //Moving listt to the right
                if ($listtWasMovedUp) {
                    if ($listt->position >= $event->newPosition && $listt->position < $event->listt->position) {
                        $listt->position++;
                        $listt->save();
                    }
                } //Moving listt to the left
                else {
                    if ($listt->position > $event->listt->position && $listt->position <= $event->newPosition) {
                        $listt->position--;
                        $listt->save();
                    }
                }

            }
        }
    }
}
