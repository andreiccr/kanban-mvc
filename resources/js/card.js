$( function() {
    $( ".sortable" ).sortable({
        update: function(event, ui) {

            const listtId = ui.item.data("listt-id");
            const cards = document.querySelectorAll(".kanban-card[data-listt-id='"+listtId+"']");

            for(let i=0; i<cards.length; i++) {
                if(cards[i].dataset.id == ui.item.data("id")) {
                    reorderCard(ui.item.parent().data("board-id"), listtId, ui.item.data("id"), i+1);
                    break;
                }
            }
        }
    });
    $( ".sortable" ).disableSelection();
} );

window.reorderCard = function(boardId, listtId, cardId, newPosition) {
    axios.patch("/b/" + boardId + "/l/" + listtId + "/c/" + cardId, {"position" : newPosition} ).then(response => {

    }).catch(err => {

    });
}


window.createCard = function(boardId, listtId) {
    const cardContainer = document.querySelector(".kanban-cards[data-listt-id='"+listtId+"'][data-board-id='"+boardId+"']")

    axios.post("/b/" + boardId + "/l/" + listtId + "/c").then(response => {
        const newCard = document.createElement("div");
        newCard.classList.add("kanban-card");
        newCard.classList.add("kanban-card-gray");

        newCard.dataset.toggle = "modal";
        newCard.dataset.target = "#edit-card-modal";

        newCard.dataset.id = response.data["id"];
        newCard.innerText = response.data["title"];
        newCard.dataset.listtId = response.data["listId"];

        newCard.addEventListener("click", e => {
            editCardModal.dataset.cardId = e.currentTarget.dataset.id;
            editCardModal.dataset.listtId = e.currentTarget.dataset.listtId;
            document.getElementById("card-title").value = e.currentTarget.innerText;
        });

        cardContainer.appendChild(newCard);
    }).catch(err => {

    });
}


window.editCard = function(boardId, listtId, cardId) {
    const cardTitle = document.getElementById("card-title").value;

    axios.patch("/b/" + boardId + "/l/" + listtId + "/c/" + cardId, {"title" : cardTitle} ).then(response => {
        let card = document.querySelector(".kanban-card[data-id='"+cardId+"']");
        card.innerText = response.data["title"];
    }).catch(err => {

    });
}

window.deleteCard = function(boardId, listtId, cardId) {
    axios.delete("/b/" + boardId + "/l/" + listtId + "/c/" + cardId).then(response => {
        let card = document.querySelector(".kanban-card[data-id='"+cardId+"']");
        card.remove();
    }).catch(err => {

    });
}
