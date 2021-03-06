$( function() {
    $( ".sortable" ).sortable({
        connectWith: ".kanban-cards",
        update: function(event, ui) {

            const targetListtId = ui.item.parent().data("listt-id");

            const targetListtCards = document.querySelectorAll(".kanban-card[data-listt-id='"+targetListtId+"']");

            for(let i=0; i<targetListtCards.length; i++) {
                if(targetListtCards[i].dataset.id == ui.item.data("id")) {
                    reorderCard(ui.item.data("id"), i+1, targetListtId);
                    break;
                }
            }
        },

        receive: function(event, ui) {

            const targetListtId = ui.item.parent().data("listt-id");

            const targetListtCards = document.querySelectorAll(".kanban-cards[data-listt-id='"+targetListtId+"'] .kanban-card");

            ui.item.attr("data-listt-id", targetListtId);

            for(let i=0; i<targetListtCards.length; i++) {
                if(targetListtCards[i].dataset.id == ui.item.data("id")) {
                    reorderCard(ui.item.data("id"), i+1, targetListtId);
                    break;
                }
            }
        }
    }).disableSelection();


} );

$(document).on("click", '.kanban-card', function() {
    modalSize("large");
    $('#edit-card-modal .modal-content').html(modalSpinner);
    axios.get("/c/" + $(this).data("id") + "/display").then(response => {
        $('#edit-card-modal .modal-content').html(response.data);

        axios.get("/c/" + $(this).data("id")).then(card => {
            formatDueDateInModal(card.data);
            $('#edit-card-modal').modal('show');
        });
    });

});



window.formatDueDateInModal = function(card) {
    const dueDate = document.getElementById("card-due-date");
    const doneDate = document.getElementById("card-done-date");

    const dueDateContainer = document.getElementById("card-due-date-container");
    const doneDateContainer = document.getElementById("card-done-date-container");

    if(card["due_date"] !== null && card["due_date"] !== undefined) {

        dueDate.dataset.storedDate = (new Date(card["due_date"])).toUTCString();
        dueDate.innerText = makeDueDateString(card["due_date"]);

        if(card["done_date"] !== null && card["done_date"] !== undefined) {

            dueDate.dataset.completed = "true";

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

            dueDate.dataset.completed = "false";
        }

    } else {
        dueDateContainer.hidden = true;
        doneDateContainer.hidden = true;
        dueDate.innerText = "";
        dueDate.dataset.storedDate = "";
        dueDate.dataset.completed = "false";
        doneDate.innerText = "";
    }
}

window.reorderCard = function(cardId, newPosition, targetListt) {

    axios.patch("/c/" + cardId + "/move", {"position" : newPosition , "listtId" : targetListt} ).then(response => {

    }).catch(err => {

    });
}

window.createCard = function(listtId) {
    const cardContainer = document.querySelector(".kanban-cards[data-listt-id='"+listtId+"']")

    axios.post("/l/" + listtId + "/c").then(response => {
        const newCard = document.createElement("div");
        newCard.classList.add("kanban-card");
        newCard.classList.add("kanban-card-gray");

        newCard.dataset.id = response.data["id"];
        newCard.innerText = response.data["title"];
        newCard.dataset.listtId = response.data["listtId"];

        cardContainer.appendChild(newCard);
    }).catch(err => {

    });
}


window.editCard = function(cardId) {
    const cardTitle = document.getElementById("card-title").value;
    const cardDetails = document.getElementById("card-details").value;
    const cardDueDate = document.getElementById("card-due-date").dataset.storedDate;
    const cardMarkedAsCompleted = document.getElementById("card-due-date").dataset.completed;
    const cardColor = document.getElementById("card-color").dataset.color;

    axios.patch("/c/" + cardId,
        {
            "title" : cardTitle ,
            "details" : cardDetails ,
            "due_date" : cardDueDate.length>0 ? cardDueDate : null ,
            "marked_as_completed" : cardMarkedAsCompleted === "false" ? null : "true" ,
            "color" : cardColor === "null" ? null : cardColor ,

        }).then(response => {

            //Update card name in the list
            const listedCard = document.querySelector(".kanban-card[data-id='"+cardId+"']")
            let listedCardTitle = document.createElement("div");
            listedCardTitle.innerText = response.data["title"];

            let listedCardIcons = document.createElement("div");

            if(response.data['details'] !== null)
                listedCardIcons.innerHTML = "<i class=\"bi bi-justify-left mr-1\" style=\"color:#111; font-size: small\"></i>";

            if(response.data['due_date'] !== null) {
                const shortDateStr = makeShortDueDateString(response.data['due_date']);
                if(response.data['done_date'] !== null) {
                    listedCardIcons.innerHTML += " <div class=\"d-inline-block mr-1\"><i class=\"bi bi-calendar-check\" style=\"color:#0d0dab; font-size: small\"></i><span class=\"p-1\" style=\"font-size: smaller; color:#0d0dab; \">" + shortDateStr + "</span></div>";
                } else {
                    listedCardIcons.innerHTML += " <div class=\"d-inline-block mr-1\"><i class=\"bi bi-calendar\" style=\"color:#4e535e; font-size: small\"></i><span class=\"p-1\" style=\"font-size: smaller; color:#1a202c;\">" + shortDateStr + "</span></div>";
                }
            }

            if(response.data['color'] !== null) {
                listedCard.style.borderTop = "8px solid "+ response.data['color'];
            } else {
                listedCard.style.borderTop = "none";
            }

            listedCard.innerHTML = "";
            listedCard.appendChild(listedCardTitle);
            listedCard.appendChild(listedCardIcons);

            //Update modal info
            formatDueDateInModal(response.data);

        }).catch(err => {

    });
}

window.deleteCard = function(cardId) {
    axios.delete("/c/" + cardId).then(response => {
        let card = document.querySelector(".kanban-card[data-id='"+cardId+"']");
        card.remove();
    }).catch(err => {

    });
}
