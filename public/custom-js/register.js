$(function () {

    // When a user registers.
    $("#formRegister").submit(function (e) {
        e.preventDefault();

        var error = $(".error");
        var usernameFormGroup = $("#formGroupUsername");
        var passwordFormGroup = $("#formGroupPassword");
        var passwordFormGroup2 = $("#formGroupPasswordVerify");

        // Remove previous warnings
        error.empty();
        usernameFormGroup.removeClass("has-error has-feedback");
        passwordFormGroup.removeClass("has-error has-feedback");
        passwordFormGroup2.removeClass("has-error has-feedback");

        // Get input data
        var username = $("#inputUsername").val();
        var password = $("#inputPassword").val();
        var passwordVerify = $("#inputPasswordVerify").val();
        var role;
        if ($("#formLecturer").is(":checked")) {
            role = 1; // role of lecturer
        } else {
            role = 0; // role of student
        }

        // boolean to check for valid inputs
        var validinput = true;

        // If any input fields are empty, show error.
        if (username == "") {
            usernameFormGroup.addClass("has-error has-feedback");
            validinput = false;
        }
        if (password == "") {
            passwordFormGroup.addClass("has-error has-feedback");
            validinput = false;
        }
        if (passwordVerify == "") {
            passwordFormGroup2.addClass("has-error has-feedback");
            validinput = false;
        }

        // Check password's match.
        if (!(password == passwordVerify)) {
            passwordFormGroup2.addClass("has-error has-feedback");
            passwordFormGroup.addClass("has-error has-feedback");
            error.append("Passwords do not match");
            validinput = false;
        }

        // If all inputs are valid...
        if (validinput) {
            // Send username and password to server to be registered.
            $.ajax({
                type: "POST",
                url: config.API_LOCATION + "register/register.php",
                data: {user: username, pass: password, role: role}
            }).done(function (data) {
                var jsonObj = JSON.parse(data);
                switch (jsonObj.status) {
                    case "success":
                        // If successful, redirect to login page.
                        alert("Successfully Registered");
                        window.location.replace(config.LOGIN);
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
        } else {
            return false;
        }
    });
});