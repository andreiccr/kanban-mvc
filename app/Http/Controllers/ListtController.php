<?php

namespace App\Http\Controllers;

use App\Models\Listt;
use App\Models\Workboard;
use Illuminate\Http\Request;

class ListtController extends Controller
{

    function __construct() {
        $this->middleware("auth");
    }

    function create(Request $request, Workboard $board) {
        $validated = $request->validate([
           "name" => "required|string|max:500"
        ]);

        $board->listts()->create([
            "name" => $validated["name"],
            "position" => 0
        ]);

        return response("OK", 200);
    }

    function update(Request $request, Workboard $board, Listt $listt) {
        $validated = $request->validate([
            "name" => "required|string|max:500"
        ]);

        $listt->name = $validated["name"];
        $listt->save();

        //return response("OK", 200);
        return redirect("/b/" . $board->id);
    }

    function destroy(Workboard $board, Listt $listt) {

        $listt->delete();

        return response("OK", 200);
    }

    function edit(Workboard $board, Listt $listt) {
        return view("listt.edit", compact('board', 'listt'));
    }
}
