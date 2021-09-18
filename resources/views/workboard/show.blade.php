@extends('layouts.app')

@section('content')
<div class="container-fluid ml-5 mb-3">
    <div class="row">
        <div class="d-flex align-items-center">
            <h4 class="m-1" style="font-weight: bold; color: #0b2133; font-size: large; padding: 0.6rem !important;">{{ $board->name }}</h4>
            <div class="vertical-separator-sm" style="border-right: 1px solid #bbb; width:1px; height: 20px"></div>
            <button class="btn m-1" data-toggle="modal" data-target="#edit-workboard-modal" style="font-size: large; color: #0b2133;"><i class="bi bi-pencil-square"></i></button>
            <button class="btn m-1" data-toggle="modal" data-target="#delete-workboard-modal" style="font-size: large; color: #0b2133;"><i class="bi bi-trash"></i></button>
        </div>
    </div>
</div>

    <div class="d-flex align-items-start w-100 px-5 pb-1" style="overflow-x: auto; height: calc((100vh - 80px) - (68px + 1.5rem));">
        @foreach($board->listts as $listt)
            <div class="d-flex mx-2" style="width:275px; height:100%;">
                <div class="card p-2" style="cursor: pointer; min-width:275px;">
                    <div class="d-flex align-items-baseline justify-content-between">
                        <div class="font-weight-bold ml-3" style="color: #0b2133;">{{ $listt->name }}</div>
                        <div>
                            <a class="btn" href="{{ route("listt.edit", ["board" => $board->id, "listt" => $listt->id] ) }}"><i class="bi bi-pencil"></i></a>
                            <button class="btn" onclick="deleteListt({{ $listt->id }})"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>

                    <div class="kanban-cards sortable py-1" data-listt-id="{{ $listt->id }}" style="overflow-y: auto; overflow-x: hidden; height:100%;">
                    @foreach($listt->cards as $card)
                        <div class="kanban-card kanban-card-gray" data-id="{{$card->id}}" data-listt-id="{{$listt->id}}" data-toggle="modal" data-target="#edit-card-modal">
                            <div>{{ $card->title }}</div>

                            @if($card->details)
                                <i class="bi bi-justify-left m-1" style="color:#4e535e; font-size: medium"></i>
                            @endif

                            @if($card->due_date)
                                <div class="d-inline-block m-1">
                                    <i class="bi @if($card->done_date) bi-calendar-check @else bi-calendar @endif" style="@if($card->done_date) color:#188c26; @else color:#4e535e; @endif font-size: medium"></i><span class="card-due p-1" data-card-due="{{ date(DateTimeInterface::ISO8601, strtotime($card->due_date)) }}" style="font-size: small; @if($card->done_date) color:#188c26; @else color:#4e535e; @endif "></span>
                                </div>

                            @endif
                        </div>
                    @endforeach
                    </div>

                    <div class="btn btn-create-card px-2 py-1 m-1" style="text-align: left" onclick="createCard({{$listt->id}})"><i class="bi bi-plus-lg"></i> Add card</div>
                </div>
            </div>
        @endforeach

        <div class="d-flex mx-1 " style="width:300px">
            <div style="min-width:300px;">
                <div class="d-flex">
                    <button class="btn btn-outline-primary w-100" data-toggle="modal" data-target="#new-listt-modal" style="text-align: left"><i class="bi bi-plus-lg"></i> Add list</button>
                </div>
            </div>
        </div>
    </div>


<div class="modal fade" id="edit-card-modal" tabindex="-1" role="dialog" aria-labelledby="editCardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="spinner-border m-4 text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <div class="modal-body" hidden>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="form-group">
                    <span class="text-danger modal-error"></span>
                    <label for="card-title" style="font-weight: 300; font-size: small; color: #777; margin-left: 0.25rem; margin-bottom: 0 !important;">Card Title</label>
                    <input type="text" placeholder="Card Title" class="form-control p-1 mb-2" onchange="_editCard()" id="card-title" name="card-title" style="font-size: x-large; border: 0;">

                    <div style="margin-left: 0.25rem; display:flex;" class="align-items-baseline" id="card-due-date-container">
                        <i class="bi bi-calendar"></i><span class="px-1" style="color: #777"> Due date</span>
                        <span class="p-1" id="card-due-date" data-completed="false" data-stored-date="" data-toggle="modal" data-target="#date-modal" style="color: #495057; background: #fafafa; cursor:pointer;">13 September 2021</span>

                        <button class="btn btn-outline-primary px-2 py-1 ml-2" id="card-due-date-check" style="font-size: small; border:none;" onclick="markAsComplete(true)"><i class="bi bi-calendar-check"></i></button>
                        <button class="btn btn-outline-danger px-2 py-1 ml-1" style="font-size: small; border:none;" onclick="removeDueDate()"><i class="bi bi-calendar-minus"></i></button>
                    </div>
                    <div style="color: #3490dc; margin-left: 0.25rem;" id="card-done-date-container">
                        <i class="bi bi-calendar-check"></i> Marked as completed on
                        <span class="p-1" id="card-done-date" onclick="markAsComplete(false)" style="color: #495057; background: #fafafa; cursor:pointer;">13 September 2021</span>
                    </div>
                </div>

                <div class="row justify-content-between">
                    <div class="col-lg-8">
                        <textarea class="w-100 p-2" onchange="_editCard()" id="card-details" name="card-details" placeholder="Add details to this card..." style="border: none; border-radius: 0.5rem; background: #fafafa"></textarea>
                    </div>
                    <div class="col-lg-3 d-flex flex-column">
                        <span class="p-1" style="color: #777777; text-align: center">Settings</span>
                        <button class="btn btn-outline-primary" data-toggle="modal" data-target="#date-modal" ><i class="bi bi-calendar-plus"></i> Due Date</button>
                        <hr>
                        <span class="p-1" style="color: #777777; text-align: center">Card Actions</span>
                        <button class="btn btn-outline-primary" data-dismiss="modal" onclick="_deleteCard()" ><i class="bi bi-trash"></i> Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="date-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h6><i class="bi bi-calendar"></i> Due date</h6>
                <hr>
                <span id="date-modal-error" class="text-danger"></span>
                <div class="d-flex justify-content-between">

                    <input class="p-2 my-2" id="due-date-input-date" type="date" min="{{ date("Y-m-d") }}" value="{{ date("Y-m-d") }}">
                    <input class="p-2 my-2" id="due-date-input-time" type="time" value="00:00">
                </div>
                <button class="btn btn-primary w-100 my-2" onclick="setDueDate()">Set Due Date</button>
                <button class="btn btn-secondary w-100 my-2" data-dismiss="modal" onclick="removeDueDate()">Remove</button>
            </div>
        </div>
    </div>
</div>

<script>

    window.onload = () => {
        let cards = document.getElementsByClassName("card-due");
        for(let i=0; i<cards.length; i++) {
            cards.item(i).innerText = makeShortDueDateString(cards.item(i).dataset.cardDue);
        }
    };

    const editCardModal = document.getElementById("edit-card-modal");
    const modalSpinner = document.querySelector("#edit-card-modal .spinner-border");
    const modalContent = document.querySelector("#edit-card-modal .modal-body");

    if(editCardModal != null) {
        const cards = document.getElementsByClassName("kanban-card");

        //Pass the card that was clicked inside the edit modal
        for (let i = 0; i < cards.length; i++) {
            cards.item(i).addEventListener("click", e => {
                editCardModal.dataset.cardId = e.currentTarget.dataset.id;
                editCardModal.dataset.listtId = e.currentTarget.dataset.listtId;

                modalSpinner.hidden = false;
                modalContent.hidden = true;
                axios.get("/c/" + e.currentTarget.dataset.id ).then(resp => {
                    displayCardInfoInModal(resp.data);
                    modalSpinner.hidden = true;
                    modalContent.hidden = false;
                })
            });
        }
    }

    function _editCard() {
        editCard(editCardModal.dataset.cardId);
    }

    function _deleteCard() {
        deleteCard(editCardModal.dataset.cardId);
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
