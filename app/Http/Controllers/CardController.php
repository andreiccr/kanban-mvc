<?php

namespace App\Http\Controllers;

use App\Events\CardDeletedEvent;
use App\Events\CardReorderedEvent;
use App\Models\Card;
use App\Models\Listt;
use App\Models\Workboard;
use Illuminate\Http\Request;

class CardController extends Controller
{
    //

    function __construct() {
        $this->middleware("auth");
    }

    function create(Workboard $board, Listt $listt) {

        $card = $listt->cards()->create([
            "title" => "New card",
            "position" => 0
        ]);

        return response()->json([
            "id" => $card->id,
            "title" => "New card",
            "position" => $card->position,
            "listId" => $listt->id,
            "boardId" => $board->id
        ]);

    }

    function update(Request $request, Workboard $board, Listt $listt, Card $card) {
        $validated = $request->validate([
            "title" => "nullable|string|max:2000",
            "position" => "nullable|integer"
        ]);

        if(isset($validated["position"]) && $validated["position"] != null) {
            $oldPosition = $card->position;
            $newPosition = $validated["position"];
            if($newPosition<1) $newPosition = 1;
            else if($newPosition > $listt->cards->count()) $newPosition = $listt->cards->count();

            if ($oldPosition != $newPosition)
                event(new CardReorderedEvent($card, $newPosition));

            $card->position = $newPosition;
        }

        if(isset($validated["title"])) {
            $oldTitle = $card->title;
            $card->title = $validated["title"];
        }

        $card->save();

        return response()->json([
            "id" => $card->id,
            "title" => $card->title,
            "position" => $card->position,
            "old_title" => $oldTitle ?? null,
            "listId" => $listt->id,
            "boardId" => $board->id
        ]);

    }

    function destroy(Workboard $board, Listt $listt, Card $card) {

        $resp = [
            "id" => $card->id,
            "title" => $card->title,
            "position" => $card->position,
            "listtId" => $listt->id,
            "boardId" => $board->id
        ];

        $card->delete();

        event(new CardDeletedEvent($resp));

        return response()->json($resp);

    }
}
