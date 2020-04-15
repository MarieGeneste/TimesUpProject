

// Call the dataTables jQuery plugin$(document).ready(function() {
$(document).ready(function () {
    $('#content_table').DataTable( {
        "columnDefs": [
            { "orderable": false, "targets": 3 },
            { "orderable": false, "targets": 4 }
        ],
        // getLanguages()
        "language": {
            "lengthMenu": "Afficher _MENU_ réponses",
            "search": "Rechercher ",
            "info": "Page _PAGE_ sur _PAGES_",
            "paginate": {
                "first":      "Première",
                "last":       "Dernière",
                "next":       "Suivant",
                "previous":   "Précédent"
            },
            "infoFiltered": " - Résultat(s) sur _MAX_ réponses"
        }
    } );

    
    $('#categories_table').DataTable( {
        "columnDefs": [
            { "orderable": false, "targets": 3 },
            { "orderable": false, "targets": 4 },
            { "orderable": false, "targets": 5 }
        ],
        // getLanguages()
        "language": {
            "lengthMenu": "Afficher _MENU_ réponses",
            "search": "Rechercher ",
            "info": "Page _PAGE_ sur _PAGES_",
            "paginate": {
                "first":      "Première",
                "last":       "Dernière",
                "next":       "Suivant",
                "previous":   "Précédent"
            },
            "infoFiltered": " - Résultat(s) sur _MAX_ catégories"
        }
    } );

    $('.dataTables_length').addClass('bs-select');

    function getLanguages() {
        var language = `
        return language"language": {
            "lengthMenu": "Afficher _MENU_ réponses",
            "search": "Rechercher ",
            "info": "Page _PAGE_ sur _PAGES_",
            "paginate": {
                "first":      "Première",
                "last":       "Dernière",
                "next":       "Suivant",
                "previous":   "Précédent"
            },
            "infoFiltered": " - Résultat(s) sur _MAX_ catégories"
        }`
        return language
    }
});

