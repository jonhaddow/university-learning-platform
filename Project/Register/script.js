$(document).ready(function() {
    
    $("#formRegister").submit(function() {

        // Remove previous warnings
        $(".error").empty();
        $("#formGroupUsername").removeClass("has-error has-feedback");
        $("#formGroupPassword").removeClass("has-error has-feedback");
        $("#formGroupPasswordVerify").removeClass("has-error has-feedback");

        var username = $("#inputUsername").val();
        var password = $("#inputPassword").val();
        var passwordVerify = $("#inputPasswordVerify").val();

        var validinput = true;

        // If username or password fields are empty show error
        if (username == "") {
            $("#formGroupUsername").addClass("has-error has-feedback");
            validinput = false;
        }
        if (password == "") {
            $("#formGroupPassword").addClass("has-error has-feedback");
            validinput = false;
        }
        if (passwordVerify == "") {
            $("#formGroupPasswordVerify").addClass("has-error has-feedback");
            validinput = false;
        }

        // Check that password is greater than 5 characters
        if (password.length < 6) {
            $("#formGroupPasswordVerify").addClass("has-error has-feedback");
            $("#formGroupPassword").addClass("has-error has-feedback");
            $(".error").append("Password's need to be longer than 5 characters");
            validinput = false;
        } // Check that passwords match
        else if (!(password == passwordVerify)) {
            $("#formGroupPasswordVerify").addClass("has-error has-feedback");
            $("#formGroupPassword").addClass("has-error has-feedback");
            $(".error").append("Password's don't match!");
            validinput = false;
        }

        if (!validinput) {
            return false;
        }
    });
});
