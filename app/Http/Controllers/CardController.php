<?php

namespace App\Http\Controllers;

use App\Events\CardDeletedEvent;
use App\Events\CardMovedToAnotherListEvent;
use App\Events\CardReorderedEvent;
use App\Models\Card;
use App\Models\Listt;
use App\Models\Workboard;
use DateTime;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    //TODO: Improve the json response

    function __construct() {
        $this->middleware("auth");
    }

    function get(Card $card) {

        $this->authorize("view", $card);

        return response()->json([
            "id" => $card->id,
            "title" => $card->title,
            "details" => $card->details,
            "due_date" => $card->due_date == null ? null : date(DateTimeInterface::ISO8601, strtotime($card->due_date)),
            "done_date" => $card->done_date == null ? null : date(DateTimeInterface::ISO8601, strtotime($card->done_date)),

        ]);
    }

    function create(Listt $listt) {

        // Allow board owner and board members
        if(Auth::user()->id != $listt->workboard->user->id &&
            $listt->workboard->members->contains(Auth::user()->id)==false )
            return response(null, 403);

        $card = $listt->cards()->create([
            "title" => "New card",
            "position" => 0
        ]);

        return $this->get($card);

    }

    function move(Request $request, Card $card) {

        $this->authorize("update", $card);

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
            $newPosition = min(max($validated["position"], 1), $card->listt->cards->count());
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
        ]);

    }

    function update(Request $request, Card $card) {

        $this->authorize("update", $card);

        $validated = $request->validate([
            "title" => "required|string|max:1000",
            "details" => "nullable|string|max:3500",
            "due_date" => "nullable|date",
            "marked_as_completed" => "nullable"
        ]);

        if(strtotime($validated['due_date']) >= strtotime("now") || $validated["due_date"] == null || (strtotime($validated['due_date']) < strtotime("now") && $validated["marked_as_completed"] != null)) {
            $card->due_date = $validated["due_date"] == null ? null : date("Y-m-d H:i:s", strtotime($validated["due_date"]));
            $card->done_date = null;
        }

        if($validated["marked_as_completed"] != null) {
            if($card->done_date == null) {
                $card->done_date = date("Y-m-d H:i:s", strtotime("now"));
            }
        } else {
            $card->done_date = null;
        }

        $oldTitle = $card->title;
        $card->title = $validated["title"];
        $card->details = $validated["details"];

        $card->save();

        return $this->get($card);

    }

    function destroy(Card $card) {

        $this->authorize("delete", $card);

        $resp = [
            "id" => $card->id,
            "title" => $card->title,
            "position" => $card->position,
            "listtId" => $card->listt->id
        ];

        $card->delete();

        event(new CardDeletedEvent($resp));

        return response()->json($resp);

    }

    function display(Card $card) {

        $this->authorize("view", $card);

        return view("modal.card", compact('card'));
    }
}
