window.addMember = function(boardId) {

    const modalError = document.querySelector("#add-member-modal .modal-error");
    const userEmail = document.getElementById("member-email").value;
    const roleInput = document.getElementById("member-role-input");
    let role = roleInput.options[roleInput.selectedIndex].value;

    axios.post("/b/" + boardId + "/u/" + userEmail, { "role" : role }).then(resp => {

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
    const role = $("#current-member-role-input");
    const removeBtn = document.getElementById("remove-member-btn");
    axios.get("/b/" + boardId + "/u/" + userId).then(resp => {
        email.value = resp.data['email'];

        if(resp.data['isOwner'] === true) {
            removeBtn.setAttribute("disabled", "true");
            role.val("Board Owner");
        } else {
            removeBtn.removeAttribute("disabled");
            if(resp.data['role'] === 1)
                role.val("Regular");
            else if(resp.data['role'] === 2)
                role.val("Manager");

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
