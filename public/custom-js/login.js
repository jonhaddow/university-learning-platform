$(function () {
    $("#formLogin").submit(function (e) {
        e.preventDefault();

        var userFormGroup = $("#formGroupUsername");
        var passFormGroup = $("#formGroupPassword");


        // remove errors
        userFormGroup.removeClass("has-error has-feedback");
        passFormGroup.removeClass("has-error has-feedback");

        // get input fields
        var username = $("#inputUsername").val();
        var password = $("#inputPassword").val();

        var valid = true;

        // If username or password fields are empty, send error.
        if (username === "") {
            userFormGroup.addClass("has-error has-feedback");
            valid = false;
        }
        if (password === "") {
            passFormGroup.addClass("has-error has-feedback");
            valid = false;
        }

        if (!valid) {
            return;
        }

        // Submit login details
        $.ajax({
            url: config.API_LOCATION + "login/login.php",
            type: "POST",
            data: $("#formLogin").serialize()
        }).done(function (data) {
            var jsonObj = JSON.parse(data);
            switch (jsonObj.status) {
                case "success":
                    // If successful, refresh page (user should be redirected by server).
                    location.reload();
                    break;
                case "fail":
                    // If unsucessful, clear textboxes and show errorMsg.
                    $("#inputUsername").val("");
                    $("#inputPassword").val("");
                    $("#errorMsg").show();
                    break;
                default:
                    alert("Connection error. Please try again.");
                    break;
            }
        });

    });
});
