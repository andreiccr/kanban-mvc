$( function() {
    $( ".sortable" ).sortable({
        connectWith: ".kanban-cards",
        update: function(event, ui) {

            const currentListtId = ui.item.data("listt-id");
            const targetListtId = ui.item.parent().data("listt-id");

            const targetListtCards = document.querySelectorAll(".kanban-card[data-listt-id='"+targetListtId+"']");

            for(let i=0; i<targetListtCards.length; i++) {
                if(targetListtCards[i].dataset.id == ui.item.data("id")) {
                    reorderCard(ui.item.parent().data("board-id"), currentListtId, ui.item.data("id"), i+1, targetListtId);
                    break;
                }
            }
        },

        receive: function(event, ui) {

            const currentListtId = ui.item.data("listt-id");
            const targetListtId = ui.item.parent().data("listt-id");

            const targetListtCards = document.querySelectorAll(".kanban-cards[data-listt-id='"+targetListtId+"'] .kanban-card");

            ui.item.attr("data-listt-id", targetListtId);

            for(let i=0; i<targetListtCards.length; i++) {
                if(targetListtCards[i].dataset.id == ui.item.data("id")) {
                    reorderCard(ui.item.parent().data("board-id"), currentListtId, ui.item.data("id"), i+1, targetListtId);
                    break;
                }
            }
        }
    }).disableSelection();
} );

window.reorderCard = function(boardId, listtId, cardId, newPosition, targetListt) {

    axios.patch("/b/" + boardId + "/l/" + listtId + "/c/" + cardId + "/move", {"position" : newPosition , "listtId" : targetListt} ).then(response => {

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
        newCard.dataset.listtId = response.data["listtId"];

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
