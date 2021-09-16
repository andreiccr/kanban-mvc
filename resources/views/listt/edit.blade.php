@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-5">
            <h4>{{ $listt->workboard->name }} - {{ $listt->name }}</h4>
            <div class="card p-3">
                <form method="post" action="/l/{{ $listt->id }}"> @csrf @method('patch')
                    <div class="form-group">
                        <label for="board-name">Edit List Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{old('listt-name') ?? $listt->name }}" placeholder="List name">
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn text-dark" href="{{ route("workboard.show", ["board" => $listt->workboard]) }}">Back</a>
                </form>
            </div>
        </div>
    </div>


</div>
@endsection
