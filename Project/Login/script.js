$(document).ready(function() {
    $("#formLogin").submit(function() {

        username = $("#inputUsername").val();
        password = $("#inputPassword").val();

        // If username or password fields are empty, don't submit. Send error.
        if (username == "") {
            $("#formGroupUsername").addClass("has-error has-feedback");
        } else {
            $("#formGroupUsername").removeClass("has-error has-feedback");
        }
        if (password == "") {
            $("#formGroupPassword").addClass("has-error has-feedback");
        } else {
            $("#formGroupPassword").removeClass("has-error has-feedback");

        }

        if (username == "" || password == "") {
            return false;
        }
    });
});
