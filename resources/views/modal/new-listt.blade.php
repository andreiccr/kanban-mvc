<div class="modal-body">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <div class="form-group">
        <span class="text-danger modal-error"></span>
        <label for="listt-name">List Name</label>
        <input type="text" class="form-control" id="listt-name" name="listt-name" placeholder="List name">
    </div>
    <button type="button" id="create-listt-btn" onclick="createNewListt({{ $board->id }})" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add list</button>
</div>
