
// Fonction du bouton annulation des formulaires
function EditModeReturn(editElement, parentHide = false) {

    var formToRemove = editElement + "Form"

    // Fait disparaitre le formulaire inséré
    $(formToRemove).fadeOut("slow", function() {

        // Fait réapparaitre les éléments édités
        if (parentHide == true) {
            $(editElement).fadeIn("slow")
        }
    })

    // Réactive tous les boutons désactivés en mode édition
    $(".form-desactivate").each(function() {
        $( this ).attr("disabled", false)
    })
}

// Désactive tous les boutons en dehors du formulaire d'édition en coutrs
function DesactiveOtherButton() {
    
    $(".form-desactivate").each(function() {
        $( this ).attr("disabled", true)
    })
}
