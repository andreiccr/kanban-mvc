window.createBoard = function() {
    const boardNameInput = document.getElementById("board-name");
    const modalError = document.getElementById("modal-error");

    axios.post("/b/", { name: boardNameInput.value }).then(response => {
        if(response.status === 200) {
            location.reload();
        } else if(response.status === 302)
            modalError.innerText = "Invalid board name.";
        else
            modalError.innerText = "Something went wrong. Please try again."
    }).catch(error => {
        modalError.innerText = "Something went wrong. Please try again."
    });
}

window.editBoard = function(id) {
    const boardNameInput = document.getElementById("board-name");
    const modalError = document.getElementById("edit-workboard-modal").getElementsByClassName("modal-error").item(0);

    axios.patch("/b/" + id , { name: boardNameInput.value }).then(response => {
        if(response.status === 200) {
            location.reload();
        } else if(response.status === 302)
            modalError.innerText = "Invalid board name.";
        else
            modalError.innerText = "Something went wrong. Please try again."
    }).catch(error => {
        modalError.innerText = "Something went wrong. Please try again."
    });
}

window.deleteBoard = function(id) {
    const modalError = document.getElementById("delete-workboard-modal").getElementsByClassName("modal-error").item(0);

    axios.delete("/b/" + id).then(response => {
        if(response.status === 200) {
            window.location = "/";
        }
        else
            modalError.innerText = "Something went wrong. Please try again."
    }).catch(error => {
        modalError.innerText = "Something went wrong. Please try again."
    });
}
