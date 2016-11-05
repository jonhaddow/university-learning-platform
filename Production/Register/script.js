$(document).ready(function() {

    $("#formRegister").submit(function() {

        // Remove previous warnings
        $(".error").empty();
        $("#formGroupUsername").removeClass("has-error has-feedback");
        $("#formGroupPassword").removeClass("has-error has-feedback");
        $("#formGroupPasswordVerify").removeClass("has-error has-feedback");

        // Get input data
        var username = $("#inputUsername").val();
        var password = $("#inputPassword").val();
        var passwordVerify = $("#inputPasswordVerify").val();

        // boolean to check for valid inputs
        var validinput = true;

        // If any input fields are empty, show error.
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

        // Check password's match.
        if (!(password == passwordVerify)) {
            $("#formGroupPasswordVerify").addClass("has-error has-feedback");
            $("#formGroupPassword").addClass("has-error has-feedback");
            $(".error").append("Password's don't match!");
            validinput = false;
        }

        // If all inputs are valid...
        if (validinput) {
            // Send username and password to server to be registered.
            $.ajax({
                type: "POST",
                async: false,
                url: API_LOCATION + "register.php",
                data: { user: username, pass: password }
            }).done(function(data) {
                var jsonObj = JSON.parse(data);
                switch (jsonObj.status) {
                    case "success":
                        // If successful, redirect to login page.
                        alert("Successfully Registered");
                        window.location.replace(LOGIN);
                        break;
                    case "fail":
                        // If unsuccessful, check what the cause is and give error message
                        if (jsonObj.data === "Username unavailable") {
                            $("#formGroupUsername").addClass("has-error has-feedback");
                            $(".error").append("Username is unavailable");
                        } else {
                            $("#formGroupPasswordVerify").addClass("has-error has-feedback");
                            $("#formGroupPassword").addClass("has-error has-feedback");
                            $(".error").append("Password's need to be longer than 5 characters");
                        }
                        break;
                    default:
                        alert("Connection error. Please try again.");
                        break;
                }
            });
        }

        // Stop html handling submission.
        return false;;
    });
});
