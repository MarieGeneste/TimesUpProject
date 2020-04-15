$(".custom-select").each(function() {
    var classes = $(this).attr("class"),
        id      = $(this).attr("id"),
        name    = $(this).attr("name");
    var template =  '<div class="' + classes + '">';
        template += '<span class="custom-select-trigger">' + $(this).attr("placeholder") + '</span>';
        template += '<div class="custom-options">';
        $(this).find("option").each(function() {
          template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
        });
    template += '</div></div>';
    
    $(this).wrap('<div class="custom-select-wrapper"></div>');
    $(this).hide();
    $(this).after(template);
  });
  $(".custom-option:first-of-type").hover(function() {
    $(this).parents(".custom-options").addClass("option-hover");
  }, function() {
    $(this).parents(".custom-options").removeClass("option-hover");
  });
  $(".custom-select-trigger").on("click", function() {
    $('html').one('click',function() {
      $(".custom-select").removeClass("opened");
      $(this).closest(".form-group").find("label").removeClass("yellow-col");
    });
    $(this).parents(".custom-select").toggleClass("opened");
    $(this).closest(".form-group").find("label").toggleClass("yellow-col");
    event.stopPropagation();
  });
  $(".custom-option").on("click", function() {
    $(this).parents(".custom-select-wrapper").find("select").val($(this).data("value"));
    $(this).parents(".custom-options").find(".custom-option").removeClass("selection");
    $(this).addClass("selection");
    $(this).parents(".custom-select").removeClass("opened");
    $(this).closest(".form-group").find("label").removeClass("yellow-col");
    $(this).parents(".custom-select").find(".custom-select-trigger").text($(this).text());
  });

// Insert la possibilité de créer une nouvelle catégorie via la recherche dansles options des selects
  $('.search-or-create-input').on('loaded.bs.select', function () {
    $(this).closest('.bootstrap-select').find('.bs-searchbox').addClass('row justify-content-around m-0')
    $(this).closest('.bootstrap-select').find('.bs-searchbox input').addClass('col-8 creator-input')
    $(this).closest('.bootstrap-select').find('.bs-searchbox input').attr({'name': 'add-category[]', 'oninput': 'checkExistantCat(this)'})
    var pageAction = $("#newCardForm").attr("action")

    var newCatAction = pageAction.replace("ajout-Carte", "creation-carte-category")
    var createBtn = '<button type="submit" formaction="'+ newCatAction +'" class="create-card-cat btn btn-light col-3">Créer</button>'

    
    $(this).closest('.bootstrap-select').find('.bs-searchbox').append(createBtn)
});

// $(".creator-input").blur(function(){
//   console.log("toto");
  
//   $(this).closest(".search-or-create-input").find('.create-card-cat').attr("disabled", "disabled")
// });

// Active et désactive le bouton de création d'une nouvelle catégorie en fonction des résultats de la recherche.
function checkExistantCat(e){
  // var input = $(e).closest('.dropdown-menu').find('.dropdown-menu.inner.show').children().hasClass('no-results')
  // var btn = $(e).closest('.dropdown-menu').find('.create-card-cat')
  // console.log($(e).closest('.dropdown-menu').find('.dropdown-menu.inner.show').children());
  // console.log(input);
  // console.log(btn);
  

  // if(input){
  //   btn.removeAttr("disabled")
  // } else {
  //   btn.attr("disabled", "disabled")
  // }
}

