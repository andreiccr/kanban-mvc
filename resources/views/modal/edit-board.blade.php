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
