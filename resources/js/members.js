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
