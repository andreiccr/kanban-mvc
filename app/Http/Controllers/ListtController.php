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

    function update(Request $request, Listt $listt) {
        $validated = $request->validate([
            "name" => "required|string|max:500"
        ]);

        $listt->name = $validated["name"];
        $listt->save();

        //return response("OK", 200);
        return redirect("/b/" . $listt->workboard->id);
    }

    function destroy(Listt $listt) {

        $listt->delete();

        return response("OK", 200);
    }

    function edit(Listt $listt) {
        return view("listt.edit", compact('listt'));
    }
}
