
// $(document).ready(function () {
//     $("#yellow_card_response").on("click", function() {
//         console.log('toto')
//         $(".yellow-response-selected").on("click", function() {
//             $("#new-yellow-response").fadeOut()
//             console.log('toto')
//         });
        
//         $(".no-yellow-response-selected").on("click", function() {
//             $("#new-yellow-response").fadeIn()
//             console.log('toto')
//         });
//     });

//     $("#blue_card_response").on("click", function() {
//         $(".blue-response-selected").on("click", function() {
//             $("#new-blue-response").fadeOut()
//             console.log('toto')
//         });
        
//         $(".no-blue-response-selected").on("click", function() {
//             $("#new-blue-response").fadeIn()
//             console.log('toto')
//         });
//     });
// })

// Call the dataTables jQuery plugin$(document).ready(function() {
    $(document).ready(function () {
        $('#content_table').DataTable( {
            "columnDefs": [
              { "orderable": false, "targets": 3 },
              { "orderable": false, "targets": 4 }
            ],
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
            }
          } );
        $('.dataTables_length').addClass('bs-select');
    });


    $(document).ready(function () {
        $('#categories_table').DataTable( {
            "columnDefs": [
                { "orderable": false, "targets": 3 },
                { "orderable": false, "targets": 4 },
                { "orderable": false, "targets": 5 }
            ],
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
            }
          } );
        $('.dataTables_length').addClass('bs-select');
    });

