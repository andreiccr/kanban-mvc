require('./bootstrap');
require('./jquery-ui.min');

window.modalSpinner = "<div class=\"spinner-border m-4 text-primary\" role=\"status\">\n" +
    "                   <span class=\"sr-only\">Loading...</span>\n" +
    "               </div>";

window.modalSize = function(size = "normal") {
    let modalDialog = $("#edit-card-modal .modal-dialog");
    modalDialog.removeClass("modal-lg").removeClass("modal-sm");
    if(size === "sm" || size === "small"){
        modalDialog.addClass("modal-sm");
    } else if(size === "lg" || size === "large") {
        modalDialog.addClass("modal-lg");
    }
}

require('./workboard');
require('./listt');
require('./members');
require('./card/card');
require('./card/dates');
require('./card/colors');

