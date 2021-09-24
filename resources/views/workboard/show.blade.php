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

                    @if(Auth::user()->workboards->count() > 0)
                        <h6 class="dropdown-header">Owned boards</h6>
                        @foreach(Auth::user()->workboards as $b)
                            <a class="dropdown-item" href="{{route("workboard.show", ["board" => $b->id])}}">{{$b->name}}</a>
                        @endforeach
                    @endif

                    @if(Auth::user()->workboards->count() > 0 && Auth::user()->joinedWorkboards->count() > 0)
                        <div class="dropdown-divider"></div>
                    @endif

                    @if(Auth::user()->joinedWorkboards->count() > 0)
                        <h6 class="dropdown-header">Joined boards</h6>
                        @foreach(Auth::user()->joinedWorkboards as $b)
                            <a class="dropdown-item" href="{{route("workboard.show", ["board" => $b->id])}}">{{$b->name}}</a>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="vertical-separator-sm" style="border-right: 1px solid #bbb; width:1px; height: 20px"></div>
            <h4 class="m-1" style="font-weight: bold; color: #0b2133; font-size: large; padding: 0.6rem !important;">{{ $board->name }}</h4>
            <div class="vertical-separator-sm" style="border-right: 1px solid #bbb; width:1px; height: 20px"></div>

            <button class="btn m-1" data-board-id="{{$board->id}}" id="edit-board-modal-btn" style="font-size: large; color: #0b2133;"><i class="bi bi-pencil-square"></i></button>
            @if($isBoardOwner)
                <button class="btn m-1" data-board-id="{{$board->id}}" id="delete-board-modal-btn" style="font-size: large; color: #0b2133;"><i class="bi bi-trash"></i></button>
            @endif


            <div class="vertical-separator-sm" style="border-right: 1px solid #bbb; width:1px; height: 20px"></div>
            <button class="btn btn-outline-secondary btn-add-member m-1" data-board-id="{{$board->id}}" id="add-member-modal-btn" style="">
                <i class="bi bi-people"></i> Add members
            </button>

        </div>
    </div>
    <div class="row">
        @if($board->members->count() > 0)
            <div class="ml-3 member" data-board-id="{{$board->id}}" data-member-email="{{$board->user->email}}" title="{{$board->user->email}}" style="display:flex; justify-content: center; align-items: center; width: 32px; height:32px; border-radius:0.25rem; background: rgb({{rand(70, 100)}}, {{rand(70, 100)}}, {{rand(70, 100)}}); color: white; font-weight: bolder; cursor:pointer">{{ strtoupper(substr($board->user->email,0,2)) }}</div>
            @foreach($board->members as $member)
                <div class="ml-3 member" data-board-id="{{$board->id}}" data-member-email="{{$member->email}}" title="{{$member->email}}" style="display:flex; justify-content: center; align-items: center; width: 32px; height:32px; border-radius:0.25rem; background: rgb({{rand(70, 100)}}, {{rand(70, 100)}}, {{rand(70, 100)}}); color: white; font-weight: bolder; cursor:pointer">{{ strtoupper(substr($member->email,0,2)) }}</div>
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
                    <button class="btn btn-outline-primary w-100" data-board-id="{{$board->id}}" style="text-align: left" id="new-listt-btn"><i class="bi bi-plus-lg"></i> Add list</button>
                </div>
            </div>
        </div>

    </div>

<div class="modal fade" id="edit-card-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<script>

    window.onload = () => {
        let cards = document.getElementsByClassName("card-due");
        for(let i=0; i<cards.length; i++) {
            cards.item(i).innerText = makeShortDueDateString(cards.item(i).dataset.cardDue);
        }
    };

</script>

@endsection
