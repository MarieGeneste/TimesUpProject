$(document).ready(function(){
    console.log("toto")
    
    $(function()
    {
        $('.friendSearch').submit(function(e)
        {
            e.preventDefault();
    
            var postdata = $('.friendSearch').serialize();
    
            $.ajax({
                type: 'POST',
                url: this.action,
                data: postdata,
                dataType: 'json',
                success: function(result)
                {
                    if (result.friendFound == true) {

                        var friensFoundMessage = "L'utilisateur <strong>" + result.friendUsername + "</strong> a bien été trouvé !"

                        $("#friendFound").find("input[name=friendId]").val(result.friendId)

                        if ($("#friendNotFound").hasClass("open")) {
                            $("#friendNotFound").slideUp().promise().done(function () {
                                $("#friendFound").find(".friendUsername").append(friensFoundMessage).promise().done(function () {
                                    $("#friendFound").slideDown()
                                    $("#friendFound").addClass("open")
                                })
                            })
                            $("#friendNotFound").removeClass("open")
                        } else {

                            $("#friendFound").find(".friendUsername").append(friensFoundMessage).promise().done(function () {
                                $("#friendFound").slideDown()
                                $("#friendFound").addClass("open")
                            })
                        }
                    } else {
                        if ($("#friendFound").hasClass("open")) {
                            $("#friendFound").slideUp().promise().done(function () {
                                $("#friendNotFound").slideDown()
                                $("#friendNotFound").addClass("open")
                            })
                            $("#friendFound").removeClass("open")
                            $("#friendFound").find("input[name=friendId]").val("")
                            $("#friendFound").find(".friendUsername").html("")
                        } else {
                            $("#friendNotFound").slideDown()
                            $("#friendNotFound").addClass("open")
                        }

                    }
                }
            });
        });
    });

})