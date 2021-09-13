<?php

namespace App\Http\Controllers;

use App\Events\CardDeletedEvent;
use App\Events\CardMovedToAnotherListEvent;
use App\Events\CardReorderedEvent;
use App\Models\Card;
use App\Models\Listt;
use App\Models\Workboard;
use Illuminate\Http\Request;

class CardController extends Controller
{
    //TODO: Improve the json response

    function __construct() {
        $this->middleware("auth");
    }

    function get(Workboard $board, Listt $listt, Card $card) {

        return response()->json([
            "id" => $card->id,
            "title" => $card->title,
            "details" => $card->details,

        ]);
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
            "listtId" => $listt->id,
            "boardId" => $board->id
        ]);

    }

    function move(Request $request, Workboard $board, Listt $listt, Card $card) {
        $validated = $request->validate([
            "position" => "required|integer|numeric",
            "listtId" => "required|integer|numeric"
        ]);

        if($validated["listtId"] != $card->listt->id) {

            $targetListt = Listt::find($validated["listtId"]);
            if($targetListt->workboard->id == $card->listt->workboard->id){

                $newPosition = min(max($validated["position"], 1), $targetListt->cards->count()+1);

                event(new CardMovedToAnotherListEvent($card, $newPosition, $validated["listtId"]));
                $card->listt_id = $validated["listtId"];
                $card->position = $newPosition;

            } else {
                return response()->json([
                    "error" => "Card must be moved to a list belonging to the same board",
                ], 403);
            }

        }
        else {
            $newPosition = min(max($validated["position"], 1), $listt->cards->count());
            if($newPosition != $card->position){
                event(new CardReorderedEvent($card, $newPosition));
                $card->position = $newPosition;
            }

        }

        $card->save();

        return response()->json([
            "id" => $card->id,
            "title" => $card->title,
            "position" => $card->position,
            "listtId" => $listt->id,
            "boardId" => $board->id
        ]);

    }

    function update(Request $request, Workboard $board, Listt $listt, Card $card) {
        $validated = $request->validate([
            "title" => "required|string|max:1000",
            "details" => "nullable|string|max:3500"
        ]);

        $oldTitle = $card->title;
        $card->title = $validated["title"];
        $card->details = $validated["details"];
        $card->save();

        return response()->json([
            "id" => $card->id,
            "title" => $card->title,
            "position" => $card->position,
            "old_title" => $oldTitle ?? null,
            "listtId" => $listt->id,
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
