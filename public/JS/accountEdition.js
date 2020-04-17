$(document).ready(function(){

})


/**
 * Insertion du template de formulaire d'EDITION de l'adresse EMAIL de l'ETUDE
 */
function openForm($editionMode) {

    DesactiveOtherButton()

    var form = $editionMode + "Form"

    $($editionMode).fadeOut().promise().done(function () {
        $(form).fadeIn()
    })
    
}