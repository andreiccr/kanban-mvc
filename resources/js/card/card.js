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

window.displayCardInfoInModal = function(card) {
    document.getElementById("card-title").value = card["title"];
    document.getElementById("card-details").value = card["details"];

    const dueDate = document.getElementById("card-due-date");
    const doneDate = document.getElementById("card-done-date");

    const dueDateContainer = document.getElementById("card-due-date-container");
    const doneDateContainer = document.getElementById("card-done-date-container");

    if(card["due_date"] !== null && card["due_date"] !== undefined) {

        dueDate.dataset.storedDate = (new Date(card["due_date"])).toUTCString();
        dueDate.innerText = makeDueDateString(card["due_date"]);

        if(card["done_date"] !== null && card["done_date"] !== undefined) {
            doneDateContainer.hidden = false;
            dueDateContainer.hidden = true;
            if(Date.parse(card["done_date"]) > Date.parse(card["due_date"])) {
                doneDateContainer.style.color = "red";
                doneDate.innerHTML = makeDueDateString(card["done_date"]) + " <span style='color: red'>PAST DUE</span>";
            } else {
                doneDateContainer.style.color = "#3490dc";
                doneDate.innerText = makeDueDateString(card["done_date"]);
            }

        } else {
            dueDateContainer.hidden = false;
            doneDateContainer.hidden = true;
            doneDate.innerText = "";
        }

    } else {
        dueDateContainer.hidden = true;
        doneDateContainer.hidden = true;
        dueDate.innerText = "";
        dueDate.dataset.storedDate = "";
        doneDate.innerText = "";
    }
}

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

            axios.get("/b/" + 1 + "/l/" + e.currentTarget.dataset.listtId + "/c/" + e.currentTarget.dataset.id ).then(resp => {
                displayCardInfoInModal(resp.data);
            })
        });

        cardContainer.appendChild(newCard);
    }).catch(err => {

    });
}


window.editCard = function(boardId, listtId, cardId) {
    const cardTitle = document.getElementById("card-title").value;
    const cardDetails = document.getElementById("card-details").value;
    const cardDueDate = document.getElementById("card-due-date").dataset.storedDate;

    const cardMarkedAsCompleted = document.getElementById("card-due-date-check").getAttribute("completed");

    axios.patch("/b/" + boardId + "/l/" + listtId + "/c/" + cardId,
        {
            "title" : cardTitle ,
            "details" : cardDetails ,
            "due_date" : cardDueDate.length>0 ? cardDueDate : null ,
            "marked_as_completed" : cardMarkedAsCompleted ,
        }).then(response => {

            //Update card name in the list
            const card = document.querySelector(".kanban-card[data-id='"+cardId+"']");
            card.innerText = response.data["title"];

            //Update modal info
            displayCardInfoInModal(response.data);

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
