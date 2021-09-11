@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3 mt-2">
            <div data-toggle="modal" data-target="#new-workboard-modal" style="cursor: pointer">
                <div class="card justify-content-center align-items-center p-2" style="height: 125px">
                    <span class="text-dark font-weight-bold" style="font-size: large"><i class="bi bi-plus-lg"></i> Create a new board</span>
                </div>
            </div>
        </div>
        @foreach(Auth::user()->workboards as $workboard)
            <div class="col-md-3 mt-2">
                <a href="{{ route("workboard.show", ["board" => $workboard->id]) }}" class="text-decoration-none">
                    <div class="card justify-content-center align-items-center p-2" style="height: 125px">
                        <span class="text-dark" style="font-size: large">{{ $workboard->name }}</span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<div class="modal fade" id="new-workboard-modal" tabindex="-1" role="dialog" aria-labelledby="newWorkboardModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="form-group">
                    <span id="modal-error" class="text-danger"></span>
                    <label for="board-name">Board Name</label>
                    <input type="text" class="form-control" id="board-name" name="board-name" aria-describedby="board-name-help" placeholder="Enter board name">
                    <small id="board-name-help" class="form-text text-muted">Max 100 characters long</small>
                </div>

                <button type="button" onclick="createBoard()" id="create-board-btn" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Create board</button>
            </div>
        </div>
    </div>
</div>

@endsection
