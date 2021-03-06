<div class="modal-body">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

    <div class="form-group">
        <span class="text-danger modal-error"></span>
        <label for="card-title" style="font-weight: 300; font-size: small; color: #777; margin-left: 0.25rem; margin-bottom: 0 !important;"><i class="bi bi-card-heading"></i> Card Title</label>
        <input type="text" value="{{$card->title}}" placeholder="Card Title" class="form-control p-1 mb-2" onchange="editCard({{$card->id}})" id="card-title" name="card-title" style="font-size: x-large; border: 0;">

        <div id="card-color" class="@if($card->color == null) d-none @endif" data-color="{{$card->color}}" style="width:100px; height:30px; margin-left: 0.25rem; margin-bottom:0.5rem; border-radius:0.25rem; background: {{$card->color ?? "none"}}"></div>

        <div @if($card->due_date == null || $card->done_date != null) hidden @endif style="margin-left: 0.25rem; display:flex;" class="align-items-baseline" id="card-due-date-container">
            <i class="bi bi-calendar"></i><span class="px-1" style="color: #777"> Due date</span>
            <span class="p-1" id="card-due-date" data-completed={{$card->done_date==null ? "false" : "true"}} data-stored-date="{{$card->due_date}}" data-toggle="modal" data-target="#date-modal" style="color: #495057; background: #fafafa; cursor:pointer;">{{$card->due_date}}</span>

            <button class="btn btn-outline-primary px-2 py-1 ml-2" id="card-due-date-check" style="font-size: small; border:none;" onclick="markAsComplete(true, {{$card->id}})"><i class="bi bi-calendar-check"></i></button>
            <button class="btn btn-outline-danger px-2 py-1 ml-1" style="font-size: small; border:none;" onclick="removeDueDate({{$card->id}})"><i class="bi bi-calendar-minus"></i></button>
        </div>

        <div @if($card->done_date == null) hidden @endif style="color: #3490dc; margin-left: 0.25rem;" id="card-done-date-container">
            <i class="bi bi-calendar-check"></i> Marked as completed on
            <span class="p-1" id="card-done-date" onclick="markAsComplete(false, {{$card->id}})" style="color: #495057; background: #fafafa; cursor:pointer;">{{$card->done_date}}</span>
        </div>
    </div>

    <div class="row justify-content-between">
        <div class="col-lg-8">
            <textarea class="w-100 p-2" onchange="editCard({{$card->id}})" id="card-details" name="card-details" placeholder="Add details to this card..." style="border: none; border-radius: 0.5rem; background: #fafafa">{{$card->details}}</textarea>
        </div>
        <div class="col-lg-3 d-flex flex-column">
            <span class="p-1" style="color: #777777; text-align: center">Settings</span>
            <button class="btn btn-outline-dark my-1" data-toggle="modal" data-target="#date-modal" ><i class="bi bi-calendar-plus"></i> Due Date</button>
            <button class="btn btn-outline-dark my-1" data-toggle="modal" data-target=".color-modal" ><i class="bi bi-palette"></i> Color</button>
            <hr>
            <span class="p-1" style="color: #777777; text-align: center">Card Actions</span>
            <button class="btn btn-outline-dark" data-dismiss="modal" onclick="deleteCard({{$card->id}})" ><i class="bi bi-trash"></i> Delete</button>
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
                <button class="btn btn-primary w-100 my-2" onclick="setDueDate({{$card->id}})">Set Due Date</button>
                <button class="btn btn-secondary w-100 my-2" onclick="removeDueDate({{$card->id}})">Remove</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade color-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h6><i class="bi bi-palette"></i> Change color</h6>
                <hr>
                <span id="color-modal-error" class="text-danger"></span>
                <div class="row">
                    <div class="col-6">
                        <div class="my-2" style="height:40px; background: #0d83e7; cursor: pointer" onclick="addCardColor('#0d83e7', {{$card->id}})" ></div>
                    </div>

                    <div class="col-6">
                        <div class="my-2" style="height:40px; background: #e70d0d; cursor: pointer"onclick="addCardColor('#e70d0d', {{$card->id}})" ></div>
                    </div>

                    <div class="col-6">
                        <div class="my-2" style="height:40px; background: #2dce0b; cursor: pointer" onclick="addCardColor('#2dce0b', {{$card->id}})" ></div>
                    </div>

                    <div class="col-6">
                        <div class="my-2" style="height:40px; background: #e7900d; cursor: pointer" onclick="addCardColor('#e7900d', {{$card->id}})" ></div>
                    </div>

                    <div class="col-6">
                        <div class="my-2" style="height:40px; background: #ec16a2; cursor: pointer" onclick="addCardColor('#ec16a2', {{$card->id}})" ></div>
                    </div>

                    <div class="col-6">
                        <div class="my-2" style="height:40px; background: #6f0de7; cursor: pointer" onclick="addCardColor('#6f0de7', {{$card->id}})" ></div>
                    </div>

                </div>
                <hr>
                <button class="btn btn-outline-secondary w-100 my-2" onclick="removeCardColor({{$card->id}})"><i class="bi bi-dash-lg"></i> Remove Color</button>
            </div>
        </div>
    </div>
</div>
