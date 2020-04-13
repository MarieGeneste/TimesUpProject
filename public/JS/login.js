
$(document).ready(function () {

    $("#openForgetPwd").click(function() {
        $("form#connexion").slideUp().promise().done(function () {
            $("#forgetPwd").slideDown()
        })
    })

    $("#returnToLogin").click(function() {
        $("#forgetPwd").slideUp().promise().done(function () {
            $("form#connexion").slideDown()
        })
    })

});

