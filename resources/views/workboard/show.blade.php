@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="d-flex align-items-baseline">
            <h4 class="m-1" style="border: 1px solid #3490dc; border-radius: 0.25rem; color: #3490dc; font-size: large; padding: 0.6rem !important;">{{ $board->name }}</h4>
            <button class="btn btn-outline-primary m-1" data-toggle="modal" data-target="#edit-workboard-modal" style="font-size: large"><i class="bi bi-pencil-square"></i></button>
            <button class="btn btn-outline-primary m-1" data-toggle="modal" data-target="#delete-workboard-modal" style="font-size: large"><i class="bi bi-trash"></i></button>
        </div>
    </div>
    <hr>
    <div class="d-flex align-items-start" style="margin-left: -15px;">
        @foreach($board->listts as $listt)
            <div class="d-flex mx-1" style="width:300px">
                <div class="card p-2" style="cursor: pointer; min-width:300px;">
                    <div class="d-flex align-items-baseline justify-content-between">
                        <div class="font-weight-bold ml-3" style="color: #0b2133;">{{ $listt->name }}</div>
                        <div>
                            <a class="btn" href="{{ route("listt.edit", ["board" => $board->id, "listt" => $listt->id] ) }}"><i class="bi bi-pencil"></i></a>
                            <button class="btn" onclick="deleteListt({{$board->id}}, {{ $listt->id }})"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>

                    <div class="kanban-cards sortable py-1" data-board-id="{{ $board->id }}" data-listt-id="{{ $listt->id }}">
                    @foreach($listt->cards as $card)
                        <div class="kanban-card kanban-card-gray" data-id="{{$card->id}}" data-listt-id="{{$listt->id}}" data-toggle="modal" data-target="#edit-card-modal">
                            <div>{{ $card->title }}</div>
                            @if($card->details)
                                <i class="bi bi-justify-left" style="color:#1a202c; font-size: medium"></i>
                            @endif
                        </div>
                    @endforeach
                    </div>

                    <div class="btn p-1 m-1" style="text-align: left" onclick="createCard({{$board->id}},{{$listt->id}})"><i class="bi bi-plus-lg"></i> Add card</div>
                </div>
            </div>
        @endforeach

        <div class="d-flex ml-1 mr-1" style="width:300px">
            <div class="card p-2" style="cursor: pointer; min-width:300px;">
                <div class="d-flex align-items-baseline justify-content-between">
                    <button class="btn w-100" data-toggle="modal" data-target="#new-listt-modal" style="text-align: left"><i class="bi bi-plus-lg"></i> Add list</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-card-modal" tabindex="-1" role="dialog" aria-labelledby="editCardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="form-group">
                    <span class="text-danger modal-error"></span>
                    <label for="card-title" style="font-weight: 100; font-size: small; color: #777; margin-left: 0.25rem; margin-bottom: 0 !important;">Card Title</label>
                    <input type="text" placeholder="Card Title" class="form-control p-1" onchange="_editCard({{$board->id}})" id="card-title" name="card-title" style="font-size: x-large; border: 0;">
                </div>
                <div class="row justify-content-between">
                    <div class="col-md-8">
                        <textarea class="w-100 p-2" onchange="_editCard({{$board->id}})" id="card-details" name="card-details" placeholder="Add details to this card..." style="border: none; border-radius: 0.5rem; background: #fafafa"></textarea>
                    </div>
                    <div class="col-md-3 d-flex flex-column">
                        <span class="p-1" style="color: #777777; text-align: center">Card Actions</span>
                        <button class="btn btn-outline-primary" data-dismiss="modal" onclick="_deleteCard({{$board->id}})" ><i class="bi bi-trash"></i> Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const editCardModal = document.getElementById("edit-card-modal");
    if(editCardModal != null) {
        const cards = document.getElementsByClassName("kanban-card");

        //Pass the card that was clicked inside the edit modal
        for (let i = 0; i < cards.length; i++) {
            cards.item(i).addEventListener("click", e => {
                editCardModal.dataset.cardId = e.currentTarget.dataset.id;
                editCardModal.dataset.listtId = e.currentTarget.dataset.listtId;

                axios.get("/b/" + {{$board->id}} + "/l/" + e.currentTarget.dataset.listtId + "/c/" + e.currentTarget.dataset.id ).then(resp => {
                    document.getElementById("card-title").value = resp.data["title"];
                    document.getElementById("card-details").value = resp.data["details"];
                })

            });
        }
    }

    function _editCard(boardId) {
        editCard(boardId, editCardModal.dataset.listtId, editCardModal.dataset.cardId);
    }

    function _deleteCard(boardId) {
        deleteCard(boardId, editCardModal.dataset.listtId, editCardModal.dataset.cardId);
    }

</script>

<div class="modal fade" id="new-listt-modal" tabindex="-1" role="dialog" aria-labelledby="newListtModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="form-group">
                    <span class="text-danger modal-error"></span>
                    <label for="listt-name">List Name</label>
                    <input type="text" class="form-control" id="listt-name" name="listt-name" placeholder="List name">
                </div>
                <button type="button" id="new-listt-btn" onclick="createNewListt({{ $board->id }})" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add list</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="edit-workboard-modal" tabindex="-1" role="dialog" aria-labelledby="editWorkboardModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="form-group">
                    <span class="text-danger modal-error"></span>
                    <label for="board-name">Board Name</label>
                    <input type="text" class="form-control" id="board-name" name="board-name" value="{{ $board->name }}" aria-describedby="board-name-help" placeholder="Enter board's name">
                    <small id="board-name-help" class="form-text text-muted">Max 100 characters long</small>
                </div>
                <button type="button" id="edit-board-btn" onclick="editBoard({{ $board->id }})" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Save</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="delete-workboard-modal" tabindex="-1" role="dialog" aria-labelledby="deleteWorkboardModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="text-danger modal-error"></span>
                <p>Are you sure you want to delete this board? <span class="text-danger">All lists and cards associated with it will be permanently deleted!</span></p>
                <button type="button" id="delete-board-btn" onclick="deleteBoard({{ $board->id }})" class="btn btn-danger"><i class="bi bi-trash"></i> Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


@endsection
