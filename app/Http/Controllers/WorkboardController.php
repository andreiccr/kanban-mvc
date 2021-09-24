<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Workboard;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkboardController extends Controller
{
    /**
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('workboard.index');
    }

    public function member(Workboard $board, User $user) {
        if($board->members->contains($user->id) || $board->user->id == $user->id) {

            $role = $board->user->id == $user->id ? 2 : $board->members->find($user->id)->pivot->role;
            return response()->json([
                "email" => $user->email,
                "role" => $role,
                "isOwner" => $board->user->id == $user->id,
                "isYou" => $user->id == Auth::user()->id
            ]);
        }
        else {
            return response()->json([
                "error" => "The user is not a board member",
            ], 404);
        }
    }

    public function register(Request $request, Workboard $board, $user) {

        $this->authorize("update", $board);

        $validated = $request->validate([
            "role" => "required|integer|numeric|min:1"
        ]);

        try {
            $user = User::where("email", $user)->firstOrFail();
        } catch(\Exception $e) {
            return response()->json(["userNotFound" => true], 404);
        }

        $role = min($validated["role"] , 2);
        $alreadyRegistered = false;

        if($board->members->contains($user->id) == false && $board->user != $user) {
            $board->members()->attach($user->id, ["role" => $role]);
        } else {
            $alreadyRegistered = true;
        }

        return response()->json([
            "success" => true,
            "alreadyRegistered" => $alreadyRegistered,
            "role" => $role,
        ]);
    }


    public function unregister(Workboard $board, $user) {

        $this->authorize("update", $board);

        try {
            $user = User::where("email", $user)->firstOrFail();
        } catch(\Exception $e) {
            return response()->json(["userNotFound" => true], 404);
        }

        $board->members()->detach($user->id);

        return response()->json([
            "success" => true,
        ]);
    }

    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Workboard $board)
    {
        $this->authorize("view", $board);

        $isBoardOwner = $board->user->id == \auth()->user()->id;
        $isBoardMember = $board->members->contains(auth()->user()->id);

        return view('workboard.show' , compact('board', 'isBoardOwner', 'isBoardOwner'));
    }


    /**
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\Support\Renderable|\Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        Auth::user()->workboards()->create([
            'name' => $validated['name']
        ]);

        return response("OK", 200);
    }

    /**
     *
     * @param Workboard $board
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\Support\Renderable|\Illuminate\Http\Response
     */
    public function destroy(Workboard $board)
    {
        $this->authorize("delete", $board);

        $board->delete();

        return response("OK", 200);
    }

    /**
     *
     * @param Response $response
     * @param Workboard $board
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\Support\Renderable|\Illuminate\Http\Response
     */
    public function update(Request $request, Workboard $board)
    {

        $this->authorize("update", $board);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $board->name = $validated['name'];
        try {
            $board->saveOrFail();
        } catch (\Throwable $e) {
            return response("Bad request", 400);
        }

        return response("OK", 200);
    }

    public function edit(Workboard $board) {

        $this->authorize("update", $board);
        return view("modal.edit-board", compact('board'));
    }

    function delete(Workboard $board) {

        $this->authorize("delete", $board);
        return view("modal.delete-board", compact('board'));
    }
}
