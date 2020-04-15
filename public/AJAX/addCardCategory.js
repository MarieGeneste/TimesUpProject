$(document).ready(function(){
    
    $(function()
    {
        $('.create-card-cat').click(function(e)
        {
            console.log("submit")
            e.preventDefault();
    
            var postdata = $('#newCardForm').serialize();
            var catFormAction = $(this).attr("formaction")
    
            $.ajax({
                type: 'POST',
                url: catFormAction,
                data: postdata,
                dataType: 'json',
                success: function(result)
                {
                    console.log(result);
                    
                    if (result.isNewCat == true) {
                        if (result.newCategoryFound == false) {

                            function getNewCatOption(selectedClass = ""){

                                var newCatOption = `<option ` + selectedClass + ` style="background: ` + result.newCategoryColor + `; color: #fff;" value="` + result.newCategoryId + `">` + result.newCategoryTitle + `</option>`

                                return newCatOption
                            }

                            function getNewPickerOption(ariaSelected = false, selectedClass = ""){

                                var newPickerCatOption = `
                                    <li class="` + selectedClass + `">
                                        <a role="option" class="dropdown-item ` + selectedClass + `" style="background: ` + result.newCategoryColor + `; color: rgb(255, 255, 255);" aria-disabled="false" tabindex="0" aria-selected="` + ariaSelected + `">
                                            <span class=" bs-ok-default check-mark"></span>
                                            <span class="text">` + result.newCategoryTitle + `</span>
                                        </a>
                                    </li>`

                                return newPickerCatOption
                            }

                            var activeCatOption = getNewCatOption("selected")
                            var otherCatOption = getNewCatOption()

                            var activeResponseCard = "#yellow_card_category"
                            var otherResponseCard = "#blue_card_category"

                            // var activePickerOption = getNewPickerOption(true,"selected")
                            // var otherPickerOption = getNewPickerOption()

                            // var activePickerResponse = "#yellow-cat-select"
                            // var otherPickerResponse = "#blue-cat-select"

                            if (!result.isYellowCardCat) {

                                var activeResponseCard = "#blue_card_category"
                                var otherResponseCard = "#yellow_card_category"

                                // var activePickerResponse = "#blue-cat-select"
                                // var otherPickerResponse = "#yellow-cat-select"
                            } 
                            
                            $(activeResponseCard).append(activeCatOption)
                            $(otherResponseCard).append(otherCatOption)

                            
                            // $(activePickerResponse).find('.bs-searchbox').find('ul').prepend(activePickerOption)
                            // $('.bootstrap-select').find('ul.dropdown-menu').prepend(otherPickerOption)
                            
                            $('.selectpicker').selectpicker('refresh');
                            
                        } else {
                            // Sélectionner la catégorie existante trouvée

                            if (result.isYellowCardCat) {
                                var optionIdselector = "#yellow-"+ result.newCategoryId
                                $(optionIdselector).attr("selected", "selected")
                            } else {
                                var optionIdselector = "#blue-"+ result.newCategoryId
                                $(optionIdselector).attr("selected", "selected")
                            } 
                            $('.selectpicker').selectpicker('refresh');
                        }

                    }
                }
            });
        });
    });

})