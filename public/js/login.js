$(function() {
    $("#formLogin").submit(function() {

        // remove errors
        $("#formGroupUsername").removeClass("has-error has-feedback");
        $("#formGroupPassword").removeClass("has-error has-feedback");

        // get input fields
        var username = $("#inputUsername").val();
        var password = $("#inputPassword").val();

        // If username or password fields are empty, send error.
        if (username === "") {
            $("#formGroupUsername").addClass("has-error has-feedback");
        }
        if (password === "") {
            $("#formGroupPassword").addClass("has-error has-feedback");
        }

        var refreshPage = false;
        // if login details have been entered...
        if (!(username === "" || password === "")) {
            // Submit login details
            $.ajax({
                type: "POST",
                async: false,
                url: config.API_LOCATION + "login.php",
                data: { user: username, pass: password }
            }).done(function(data) {
                var jsonObj = JSON.parse(data);
                switch (jsonObj.status) {
                    case "success":
                        // If successful, refresh page (user should be redirected by server).
                        refreshPage = true;
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
        }

        return refreshPage;
    });
});
