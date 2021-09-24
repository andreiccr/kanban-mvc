window.addCardColor = function(colorCode, cardId) {
    const cardColor = document.getElementById("card-color");
    cardColor.dataset.color = colorCode;
    cardColor.style.background = colorCode;
    cardColor.classList.remove("d-none");

    editCard(cardId);

    $(".color-modal").modal("hide");
}

window.removeCardColor = function(cardId) {
    const cardColor = document.getElementById("card-color");
    cardColor.dataset.color = null;
    cardColor.style.background = "none";
    cardColor.classList.add("d-none");

    editCard(cardId);

    $(".color-modal").modal("hide");
}
