window.deleteListt = function(listtId) {
    axios.delete("/l/" + listtId).then(response => {
        if(response.status === 200) {
            location.reload();
        }
    }).catch(error => {

    });
}

window.createNewListt = function(boardId) {
    const listtNameInput = document.getElementById("listt-name");
    const modalError = document.getElementById("new-listt-modal").getElementsByClassName("modal-error").item(0);

    axios.post("/b/" + boardId + "/l/" , { name: listtNameInput.value }).then(response => {
        if(response.status === 200) {
            location.reload();
        } else if(response.status === 302)
            modalError.innerText = "Invalid list name.";
        else
            modalError.innerText = "Something went wrong. Please try again."
    }).catch(error => {
        modalError.innerText = "Something went wrong. Please try again."
    });
}
