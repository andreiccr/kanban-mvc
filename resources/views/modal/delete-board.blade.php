<div class="modal-body">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <span class="text-danger modal-error"></span>
    <p>Are you sure you want to delete this board? <span class="text-danger">All lists and cards associated with it will be permanently deleted!</span></p>
    <button type="button" id="delete-board-btn" onclick="deleteBoard({{ $board->id }})" class="btn btn-danger"><i class="bi bi-trash"></i> Delete</button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
</div>
