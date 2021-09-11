<?php

namespace App\Http\Controllers;

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

    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Workboard $board)
    {
        $this->authorize("view", $board);

        return view('workboard.show' , compact('board'));
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
}
