<?php

namespace App\Http\Controllers;

use App\Events\ListtDeletedEvent;
use App\Events\ListtReorderedEvent;
use App\Models\Listt;
use App\Models\Workboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListtController extends Controller
{

    function __construct() {
        $this->middleware("auth");
    }

    function create(Request $request, Workboard $board) {

        // Allow board owner and board members
        if (Auth::user()->id != $board->user->id &&
            $board->members->contains(Auth::user()->id) == false )
            return response(null, 403);

        $validated = $request->validate([
           "name" => "required|string|max:500"
        ]);

        $board->listts()->create([
            "name" => $validated["name"],
            "position" => 0
        ]);

        return response("OK", 200);
    }

    function move(Request $request, Listt $listt) {
        $validated = $request->validate([
            "position" => "required|integer|numeric",
        ]);

        $newPosition = min(max($validated["position"], 1), $listt->workboard->listts->count());
        if($newPosition != $listt->position){
            event(new ListtReorderedEvent($listt, $newPosition));
            $listt->position = $newPosition;
        }

        $listt->save();

        return response()->json([
            "id" => $listt->id,
            "name" => $listt->name,
            "position" => $listt->position,
        ]);

    }

    function update(Request $request, Listt $listt) {

        $this->authorize("update", $listt);

        $validated = $request->validate([
            "name" => "required|string|max:500"
        ]);

        $listt->name = $validated["name"];
        $listt->save();

        //return response("OK", 200);
        return redirect("/b/" . $listt->workboard->id);
    }

    function destroy(Listt $listt) {

        $this->authorize("delete", $listt);

        $resp = [
            "id" => $listt->id,
            "name" => $listt->name,
            "position" => $listt->position,
            "boardId" => $listt->workboard->id,
        ];

        $listt->delete();

        event(new ListtDeletedEvent($resp));

        return response("OK", 200);
    }

    function edit(Listt $listt) {
        return view("listt.edit", compact('listt'));
    }
}
