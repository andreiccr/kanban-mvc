<div class="modal-body">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <div class="form-group">
        <span class="text-danger modal-error"></span>
        <label for="current-member-email">Board Member</label>
        <hr class="mt-0 mb-3">
        <input type="email" id="current-member-email" value="{{$email}}" class="form-control" readonly>
    </div>

    @if($isOwner == false)
        <button type="button" id="remove-member-btn" onclick="removeMember({{$board->id}})" class="btn btn-outline-danger w-100 my-1"><i class="bi bi-dash-lg"></i> @if($isSelf) Leave board @else Remove member @endif</button>
    @endif
</div>
