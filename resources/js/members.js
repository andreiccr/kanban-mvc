window.addMember = function(boardId) {

    const modalError = document.querySelector("#add-member-modal .modal-error");
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



window.loadMemberInModal = function(boardId, userId) {
    const email = document.getElementById("current-member-email");
    const removeBtn = document.getElementById("remove-member-btn");

    axios.get("/b/" + boardId + "/u/" + userId).then(resp => {
        email.value = resp.data['email'];
        removeBtn.innerHTML = "<i class=\"bi bi-dash-lg\"></i> Remove member"
        removeBtn.removeAttribute("disabled");
        if(resp.data['isOwner'] === true) {
            removeBtn.setAttribute("disabled", "true");
        } else if(resp.data['isYou'] === true) {
            removeBtn.innerHTML = "<i class=\"bi bi-dash-lg\"></i> Leave board";
        }
    });
}

window.removeMember = function(boardId) {
    const email = document.getElementById("current-member-email");
    axios.delete("/b/" + boardId + "/u/" + email.value).then(resp => {
        location.reload();
    }).catch(err => {

    });

}
