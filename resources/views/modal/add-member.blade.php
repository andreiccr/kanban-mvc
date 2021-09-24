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
