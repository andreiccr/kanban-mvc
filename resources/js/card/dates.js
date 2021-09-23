function validateDateTimeInput() {
    const dateInput = document.getElementById("due-date-input-date");
    const timeInput = document.getElementById("due-date-input-time");
    const errorMessage = document.getElementById("date-modal-error");

    let dateObj = dateInput.valueAsDate;
    dateObj.setHours(timeInput.valueAsDate.getUTCHours());
    dateObj.setMinutes(timeInput.valueAsDate.getUTCMinutes());

    if(dateObj < new Date()) {
        errorMessage.innerText = "Due date can't be set in the past";
        dateInput.style.border = "1px solid red";
        timeInput.style.border = "1px solid red";
        return false;
    }
    else {
        errorMessage.innerText = "";
        dateInput.style.border = "1px solid #8f8f9d";
        timeInput.style.border = "1px solid #8f8f9d";
        $("#date-modal").modal("hide");
        return true;
    }
}

window.makeDueDateString = function(d) {
    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    let dateStr;

    let dateObj = new Date(d);
    dateStr = dateObj.getDate() + " " + months[dateObj.getMonth()] + " " + dateObj.getFullYear() + " at " + ("0" + dateObj.getHours()).slice(-2) + ":" + ("0" + dateObj.getMinutes()).slice(-2);

    return dateStr;
}

window.makeShortDueDateString = function(d) {
    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    let dateStr;

    let dateObj = new Date(d);
    dateStr = dateObj.getDate() + " " + months[dateObj.getMonth()];

    return dateStr;
}

window.setDueDate = function(cardId) {

    if(validateDateTimeInput() === false) {
        return;
    }

    //Make the Date object
    const timeInput = document.getElementById("due-date-input-time");
    let dateObj = document.getElementById("due-date-input-date").valueAsDate;
    dateObj.setHours(timeInput.valueAsDate.getUTCHours());
    dateObj.setMinutes(timeInput.valueAsDate.getUTCMinutes());

    //Store date
    const dueDate = document.getElementById("card-due-date");
    dueDate.dataset.storedDate = dateObj.toUTCString();
    dueDate.dataset.completed = "false";

    editCard(cardId);

}

window.markAsComplete = function(value, cardId) {
    if(value === true) {
        document.getElementById("card-due-date").dataset.completed = "true";
    }
    else {
        document.getElementById("card-due-date").dataset.completed = "false";
    }

    editCard(cardId);
}

window.removeDueDate = function(cardId) {
    const dueDate = document.getElementById("card-due-date");
    dueDate.dataset.storedDate = "";
    dueDate.dataset.completed = "false";

    editCard(cardId);
}
