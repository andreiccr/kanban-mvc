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

window.setDueDate = function() {

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
    _editCard(1);

}

window.markAsComplete = function(value) {
    if(value === true) {
        document.getElementById("card-due-date-check").setAttribute("completed", "true");
    }
    else {
        document.getElementById("card-due-date-check").removeAttribute("completed");
    }

    _editCard(1);
}

window.removeDueDate = function() {
    const dueDate = document.getElementById("card-due-date");
    dueDate.dataset.storedDate = "";
    document.getElementById("card-due-date-check").removeAttribute("completed");

    _editCard(1);
}
