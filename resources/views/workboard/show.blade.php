@extends('layouts.app')

@section('content')
<div class="container-fluid ml-5 mb-3">
    <div class="row">
        <div class="d-flex align-items-center">

            <div class="dropdown show m-1">
                <a class="btn btn-outline-secondary btn-boards-dropdown dropdown-toggle" style="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bi bi-view-list"></i> Boards
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    @foreach($board->user->workboards as $b)
                        <a class="dropdown-item" href="{{route("workboard.show", ["board" => $b->id])}}">{{$b->name}}</a>
                    @endforeach
                </div>
            </div>

            <div class="vertical-separator-sm" style="border-right: 1px solid #bbb; width:1px; height: 20px"></div>
            <h4 class="m-1" style="font-weight: bold; color: #0b2133; font-size: large; padding: 0.6rem !important;">{{ $board->name }}</h4>
            <div class="vertical-separator-sm" style="border-right: 1px solid #bbb; width:1px; height: 20px"></div>

            <button class="btn m-1" data-toggle="modal" data-target="#edit-workboard-modal" style="font-size: large; color: #0b2133;"><i class="bi bi-pencil-square"></i></button>
            @if($isBoardOwner)
                <button class="btn m-1" data-toggle="modal" data-target="#delete-workboard-modal" style="font-size: large; color: #0b2133;"><i class="bi bi-trash"></i></button>
            @endif


            <div class="vertical-separator-sm" style="border-right: 1px solid #bbb; width:1px; height: 20px"></div>
            <button class="btn btn-outline-secondary btn-add-member m-1" data-toggle="modal" data-target="#add-member-modal" style="">
                <i class="bi bi-people"></i> Add members
            </button>

        </div>
    </div>
    <div class="row">
        @if($board->members->count() > 0)
            <div class="ml-3" onclick="loadMemberInModal({{$board->id}}, {{$board->user->id}})" data-toggle="modal" data-target="#show-member-modal" title="{{$board->user->email}}" style="display:flex; justify-content: center; align-items: center; width: 32px; height:32px; border-radius:0.25rem; background: rgb({{rand(70, 100)}}, {{rand(70, 100)}}, {{rand(70, 100)}}); color: white; font-weight: bolder; cursor:pointer">{{ strtoupper(substr($board->user->email,0,2)) }}</div>
            @foreach($board->members as $member)
                <div class="ml-3" onclick="loadMemberInModal({{$board->id}}, {{$member->id}})" data-toggle="modal" data-target="#show-member-modal" title="{{$member->email}}" style="display:flex; justify-content: center; align-items: center; width: 32px; height:32px; border-radius:0.25rem; background: rgb({{rand(70, 100)}}, {{rand(70, 100)}}, {{rand(70, 100)}}); color: white; font-weight: bolder; cursor:pointer">{{ strtoupper(substr($member->email,0,2)) }}</div>
            @endforeach
        @endif
    </div>
</div>

    <div class="d-flex align-items-start w-100 px-5 pb-1 listt-sortable" style="overflow-x: auto; height: calc((100vh - 80px) - (68px + 1.5rem));">
        @foreach($board->listts as $listt)
            <div class="d-flex mx-2 listt" data-id="{{$listt->id}}" style="width:275px; height:100%; float: left;">
                <div class="card py-1" style="cursor: pointer; min-width:275px; background: #f7f7f7;">
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

                    <div class="btn btn-create-card px-2 py-1 m-1" style="text-align: left; color: #444;" onclick="createCard({{$listt->id}})"><i class="bi bi-plus-lg"></i> Add card</div>
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


<div class="modal fade" id="edit-card-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
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

<div class="modal fade" id="add-member-modal" tabindex="-1" role="dialog" aria-labelledby="addMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="form-group">
                    <label for="member-email">Add member to board</label>
                    <hr class="mt-0 mb-3">
                    <span class="text-danger modal-error"></span>
                    <input type="email" class="form-control" id="member-email" name="member-email" placeholder="Email address">
                </div>

                <button type="button" id="add-member-btn" onclick="addMember({{$board->id}})" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add member</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="show-member-modal" tabindex="-1" role="dialog" aria-labelledby="addMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="form-group">
                    <span class="text-danger modal-error"></span>
                    <label for="current-member-email">Board Member</label>
                    <hr class="mt-0 mb-3">
                    <input type="email" id="current-member-email" value="" class="form-control" readonly>
                </div>

                <button type="button" id="remove-member-btn" onclick="removeMember({{$board->id}})" class="btn btn-outline-danger w-100 my-1"><i class="bi bi-dash-lg"></i> Remove member</button>
            </div>
        </div>
    </div>
</div>

@endsection
