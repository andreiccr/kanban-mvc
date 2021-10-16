$( function() {
    $( ".listt-sortable" ).sortable({
        items: ".listt",
        tolerance: "pointer",
        placeholder: "dragged-listt-placeholder",
        update: function(event, ui) {
            const listts = document.querySelectorAll(".listt");
            for(let i=0; i<listts.length; i++) {
                if(listts[i].dataset.id == ui.item.data("id")) {
                    reorderListt(ui.item.data("id"), i+1);
                    break;
                }
            }
        }

    }).disableSelection();


} );

$(document).on("click", "#new-listt-btn", function() {
    modalSize("normal");
    $('#edit-card-modal .modal-content').html(modalSpinner);
    axios.get("/b/" + $(this).data("boardId") + "/list").then(response => {
        $('#edit-card-modal .modal-content').html(response.data);
        $('#edit-card-modal').modal('show');
    });
});


window.deleteListt = function(listtId) {
    axios.delete("/l/" + listtId).then(response => {
        if(response.status === 200) {
            location.reload();
        }
    }).catch(error => {

    });
}

window.reorderListt = function(listtId, newPosition) {

    axios.patch("/l/" + listtId + "/move", {"position" : newPosition } );

}

window.createNewListt = function(boardId) {
    const listtNameInput = document.getElementById("listt-name");
    const modalError = document.getElementsByClassName("modal-error").item(0);

    axios.post("/b/" + boardId + "/l" , { name: listtNameInput.value }).then(response => {
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
