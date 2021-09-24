@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Owned Boards</h4>
    <div class="row">
        <div class="col-md-3 mt-2">
            <div id="create-board-modal-btn" style="cursor: pointer">
                <div class="card justify-content-center align-items-center p-2" style="height: 125px;  background: #f7f7f7;">
                    <span class="text-dark font-weight-bold" style="font-size: large"><i class="bi bi-plus-lg"></i> Create a new board</span>
                </div>
            </div>
        </div>

        @foreach(Auth::user()->workboards as $workboard)
            <div class="col-md-3 mt-2">
                <a href="{{ route("workboard.show", ["board" => $workboard->id]) }}" class="text-decoration-none">
                    <div class="card justify-content-center align-items-center p-2" style="height: 125px; background: #f7f7f7;">
                        <span class="text-dark" style="font-size: large">{{ $workboard->name }}</span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    @if(Auth::user()->joinedWorkboards->count() > 0)
    <h4 class="mt-3">Joined Boards</h4>
    <div class="row">
        @foreach(Auth::user()->joinedWorkboards as $workboard)
            <div class="col-md-3 mt-2">
                <a href="{{ route("workboard.show", ["board" => $workboard->id]) }}" class="text-decoration-none">
                    <div class="card justify-content-center align-items-center p-2" style="height: 125px; background: #f7f7f7;">
                        <span class="text-dark" style="font-size: large">{{ $workboard->name }}</span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    @endif
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content"></div>
    </div>
</div>

@endsection
