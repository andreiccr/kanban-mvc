$(document).on("click", "#add-member-modal-btn", function() {
    modalSize("small");
    $('#edit-card-modal .modal-content').html(modalSpinner);
    axios.get("/b/" + $(this).data("boardId") + "/member/add").then(response => {
        $('#edit-card-modal .modal-content').html(response.data);
        $('#edit-card-modal').modal('show');
    });
});

$(document).on("click", ".member", function() {
    modalSize("small");
    $('#edit-card-modal .modal-content').html(modalSpinner);
    axios.get("/b/" + $(this).data("boardId") + "/member/" + $(this).data("memberEmail")).then(response => {
        $('#edit-card-modal .modal-content').html(response.data);
        $('#edit-card-modal').modal('show');
    });
});


window.addMember = function(boardId) {

    const modalError = document.querySelector(".modal-error");
    const userEmail = document.getElementById("member-email").value;

    axios.post("/b/" + boardId + "/u/" + userEmail, { "role" : 1 }).then(resp => {

        if(resp.data["alreadyRegistered"] === true) {
            modalError.innerText = "This user is already a member of this board!"
        } else if (resp.data["success"] !== true) {
            modalError.innerText = "Couldn't add this user as member!"
        } else {
            //TODO: Update page without reloading
            location.reload();
        }
    }).catch(err => {
        modalError.innerText = "An error has occurred. Please try again!"

    });
}

window.removeMember = function(boardId) {
    const email = document.getElementById("current-member-email");
    axios.delete("/b/" + boardId + "/u/" + email.value).then(resp => {
        location.reload();
    }).catch(err => {

    });

}
